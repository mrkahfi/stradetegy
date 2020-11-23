<?php

namespace Jariff\MemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Jariff\MemberBundle\Entity\Pending;
use Jariff\MemberBundle\Form\PendingType;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\AdminBundle\Entity\Inbound;
use Jariff\AdminBundle\Repository\AdminRepository;
use Jariff\MemberBundle\Entity\Member;
use Jariff\MemberBundle\Entity\MemberSubscription;
use Jariff\MemberBundle\Entity\MemberProfile;
use Jariff\MemberBundle\Entity\MemberCC;
use Jariff\MemberBundle\Entity\Company;

/**
 * Pending controller.
 */
class PendingController extends BaseController
{
    /**
     * ping from adobe
     *
     * @Route("/esign/lead-subscription/{subscription}", name="esign_ping_lead_subscription")
     */
    public function pingLeadAction(Request $request, $subscription)
    {
        $entity = $this->repo('JariffAdminBundle:LeadSubscription')->find($subscription);
        $params = $this->container->getParameter('jariff_admin');
        $S      = new \SOAPClient($params['echosign_url']);
        $r      = $S->getDocumentInfo(array(
            'apiKey'      => $params['echosign_api_key'],
            'documentKey' => $entity->getDocumentKey(),
            ));

        $entity->setDocumentStatus($r->documentInfo->status);

        if ($r->documentInfo->status == 'SIGNED') {
            $documentKey       = $r->documentInfo->documentKey;
            $latestDocumentKey = $r->documentInfo->latestDocumentKey;
            $r                 = $S->getDocumentUrls(array(
                'apiKey'      => $params['echosign_api_key'],
                'documentKey' => $documentKey,
                'options' => array(
                    'versionKey' => $latestDocumentKey
                    ),
                ));
            $entity->setDocumentUrl($r->getDocumentUrlsResult->urls->DocumentUrl->url);

            $lead = $entity->getLead();
            $contact = $lead->getContact();
            $inbound = new Inbound();
            $inbound->setEmail($contact->getEmail());
            $inbound->setPhone($contact->getPhone());
            $inbound->setBusiness($lead->getBusiness());
            $inbound->setName($contact->getFirstName().' '.$contact->getLastName());
            $inbound->setSource('contract_signed');
            $inbound->setDescription('Lead #'.$lead->getNumber().' contract document signed.');
            // $inbound->setMember();

            $this->em()->persist($inbound);
        }
        $this->em()->flush();
    }


    /**
     * send the echo sign
     * @param  lead subscription $entity 
     * @return boolean         sent or not
     */
    public function echoSignSend($entity){
        if ($entity->getPaymentTerm() == 'mtm') {
            $content  = $this->renderView('JariffMemberBundle:Esign:subscription-agreement.html.twig', array('subscription'  => $entity));
        } else {
            $content  = $this->renderView('JariffMemberBundle:Esign:subscription-agreement-pif.html.twig', array('subscription'  => $entity));
        }
        $dir      = $this->get('kernel')->getRootDir().'/contracts/'.$entity->getDateCreate()->format('Y/m/d/');
        $filename = $dir.$entity->getToken().'.pdf';

        if(!is_dir($dir)){
            if(!mkdir($dir, 0755, true)) {
                // mail('ardianys@gmail.com', 'mkdir contract error', date('Y-m-d H:i:s') + 'mkdir contract error');
                mail('yanmi.inc@gmail.com', 'mkdir contract error', date('Y-m-d H:i:s') + 'mkdir contract error');
                return false;
                // throw new \Exception('Failed to create directory '.$dir);
            }
        }

        file_put_contents($filename, $this->get('knp_snappy.pdf')->getOutputFromHtml($content));

        $params = $this->container->getParameter('jariff_admin');
        $S      = new \SOAPClient($params['echosign_url']);
        try {

            $r  = $S->sendDocument(array(
                'apiKey'               => $params['echosign_api_key'],
                'documentCreationInfo' => array(
                    'recipients' => array(
                        'RecipientInfo' => array(
                            'email' => $entity->getMember()->getEmail(),
                            'role'  => 'SIGNER'
                            )
                        ),
                    // 'ccs'           => 'ardianys@outlook.com',
                    'ccs'           => 'yanmi.inc@gmail.com',
                    'name'          =>  $entity->getMember()->getEmail() . " New Contract Agreement",
                        // 'message'    => '',
                    'signatureType' => 'ESIGN',
                    'signatureFlow' => 'SENDER_SIGNATURE_NOT_REQUIRED',
                    'fileInfos'     => array(
                        'FileInfo' => array(
                            'file'     => file_get_contents($filename),
                            'fileName' => 'Contract #'.$entity->getId().'.pdf',
                            ),
                        ),
                    'callbackInfo' => array(
                        'CallbackInfo' => array(
                            'signedDocumentUrl' => $this->generateUrl('esign_ping_lead_subscription', array('subscription' => $entity->getId()), true)
                            )
                        )
                    ),
            ));
            $entity->setDocumentKey($r->documentKeys->DocumentKey->documentKey);
            $entity->setDocumentStatus('OUT_FOR_SIGNATURE');
            $this->em()->flush();
            $body = $entity->getMember()->getEmail()." Waiting for Contract".
            "\n\nName: ".$entity->getMember()->getFirstName()." ".$entity->getMember()->getLastName().
            "\nPhone: ".$entity->getMember()->getPhone().
            "\nCompany: ".$entity->getMember()->getCompany().
            "\nCountry: ".$entity->getMember()->getCountry().
            "\nSource: Signup Form";
            $subject = $entity->getMember()->getEmail().' Waiting for Contract';
            $this->get('mail_helper')->sendEmail($entity->getMember()->getEmail(), 'info@stradetegy.com', $entity->getMember()->getEmail(), $body, $subject);
            // $this->get('mail_helper')->sendEmail($entity->getMember()->getEmail(), 'yanmi.inc@gmail.com', $entity->getMember()->getEmail(), $body, $subject);
            return $content;
        } catch (Exception $e) {
             mail('ardianys@gmail.com', 'mkdir contract error', date('Y-m-d H:i:s') + 'mkdir contract error');
             mail('yanmi.inc@gmail.com', 'mkdir contract error', date('Y-m-d H:i:s') + 'mkdir contract error');
             return null;
        }
    }

    /**
     * Creates a new Pending entity.
     *
     * @Route("/signup-save", name="pending_create")
     * @Method("POST")
     * @Template("JariffMemberBundle:Pending:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Pending();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $this->em()->persist($entity);
        $this->em()->flush();

        if ($form->isValid()) {
            // also save the data in members
            $member = new Member();
            $member->setEmail($entity->getEmail());
            // $member->setStatus(0);
            $member->setSalutation($entity->getSalutation());
            $member->setFirstName($entity->getFirstName());
            $member->setLastName($entity->getLastName());
            $member->setCountry($entity->getCountry());
            $member->setPhone($entity->getPhone());
            $member->setCompany($entity->getCompanyName());
            $member->setPassword($this->get('jariff_password_encoder')->encodePassword($entity->getPassword(), $member->getSalt()));

            // if user signup directly from web without any help from marketing team, we'll honor this
            // geek's sales as 'online admin'
            $admin = $this->repo('JariffAdminBundle:Admin')->findOneById(1);
            if ($admin) {
                $member->setSales($admin); 
            }

            // profile
            $memberProfile = new MemberProfile();
            $member->setProfile($memberProfile);
            $memberProfile->setMember($member);
            $memberProfile->setSalutation($entity->getSalutation());
            $memberProfile->setFirstName($entity->getFirstName());
            $memberProfile->setLastName($entity->getLastName());
            $memberProfile->setCountry($entity->getCountry());
            $memberProfile->setPhone($entity->getPhone());
            $memberProfile->setDateRegistration($entity->getDateRegistration());

            if ($entity->getPayment() == 'cc') {
                $memberCC = new MemberCC();
                $memberCC->setNumber($entity->getNumber());
                $memberCC->setExpired($entity->getExpired());
                $memberCC->setSecurityCode($entity->getSecureCode());
                // $memberCC->setType($entity->getCcType());
                $memberCC->setMember($member);
            }

            // Subscription
            $memberSubscription = new MemberSubscription();
            $member->addSubscription($memberSubscription);
            $memberSubscription->setEverythingPlan($entity->getEverythingPlan());
            $memberSubscription->setHistory($entity->getHistory());
            $memberSubscription->setSearch($entity->getSearch());
            $memberSubscription->setDownload($entity->getDownload());
            $memberSubscription->setDownloadLimit($entity->getDownload());
            $memberSubscription->setBigPicture($entity->getBigPicture());
            $memberSubscription->setMonth($entity->getMonth());
            $memberSubscription->setPaymentTerm($entity->getPaymentTerm());
            $memberSubscription->setDiscount($entity->getDiscount());

            // mengambil data harga per feature dari database
            $subscriptions = $this->repo('JariffAdminBundle:Subscription')->findAll();
            foreach ($subscriptions as $subscription) {
                $data[$subscription->getCategory()][$subscription->getPrice()] = $subscription->getValue();
            }
            $memberSubscription->updateValue($data);
            $this->em()->persist($member);
            // $this->em()->persist($company);
            // $this->em()->persist($entity);
            $this->em()->flush();

            $date   = new \DateTime();
            $amount = $this->repo('JariffMemberBundle:Member')->findTotalToday();
            $number = date('mdY') . str_pad(strval($amount), 3, '0', STR_PAD_LEFT);
            $member->setNumber($number);
            $memberSubscription->setNumber($member->getNumber() + '001');
            $this->em()->flush();

            $body = "<h2></h2>".
                                "\n<br>\n<br>\n<br>A new user didn’t finish signing up! Please check the account information to identify a possible issue. Then call the user immediately to help them activate their account. Follow up with email recap.".
                                "\n<br>\n<br><b>Account Number</b>: ".$member->getNumber().
                                "\n<br><b>Account Name</b>: ".$entity->getFirstName()." ".$entity->getLastName().
                                "\n<br><b>Account Email</b>: ".$entity->getEmail().
                                "\n<br>\n<br>";



            $body = "<html><head></head><body>".$body."</body></html>";
            $subject = 'ATTENTION! No Contract Returned (NCR)';
            $this->get('mail_helper')->sendEmail($entity->getEmail(), 'info@stradetegy.com', $entity->getEmail(), $body, $subject);
                // $this->get('mail_helper')->sendEmail($entity->getEmail(), 'yanmi.inc@gmail.com', $entity->getEmail(), $body, $subject);


            $this->success('Congratulation, Your registration data has been saved. Our CS will contact you soon.');
            $content = $this->echoSignSend($memberSubscription);
            
            if ($content != null) {
               $this->success('New contract sent to your email address, please check your inbox');
            } else {
                $this->error('Unable generate your contract, please contact our CS.');
            }
        return $this->redirectUrl('pending_show', array('token' => $entity->getToken()));
    }

    return array(
        'entity' => $entity,
        'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Pending entity.
     *
     * @Route("/member/subscribe-save", name="subscription_create")
     * @Method("POST")
     * @Template("JariffMemberBundle:Pending:new.html.twig")
     */
    public function createSubscriptionAction(Request $request)
    {
        $member = $this->get('security.context')->getToken()->getUser();
        // var_dump($member);
        // die();
        $entity = $this->repo('JariffMemberBundle:Pending')->findOneByEmail($member->getEmail());
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $this->em()->persist($entity);
        $this->em()->flush();

        if ($form->isValid()) {
            // also save the data in members

            // profile
            $memberProfile = $member->getProfile();
            $memberProfile->setMember($member);
            $memberProfile->setSalutation($entity->getSalutation());
            $memberProfile->setFirstName($entity->getFirstName());
            $memberProfile->setLastName($entity->getLastName());
            $memberProfile->setCountry($entity->getCountry());
            $memberProfile->setPhone($entity->getPhone());
            $memberProfile->setDateRegistration($entity->getDateRegistration());
            $member->setProfile($memberProfile);

            if ($entity->getPayment() == 'cc') {
                $memberCC = new MemberCC();
                $memberCC->setNumber($entity->getNumber());
                $memberCC->setExpired($entity->getExpired());
                $memberCC->setSecurityCode($entity->getSecureCode());
                // $memberCC->setType($entity->getCcType());
                $memberCC->setMember($member);
            }

            // Subscription
            $memberSubscription = new MemberSubscription();
            $member->addSubscription($memberSubscription);
            $memberSubscription->setEverythingPlan($entity->getEverythingPlan());
            $memberSubscription->setHistory($entity->getHistory());
            $memberSubscription->setSearch($entity->getSearch());
            $memberSubscription->setDownload($entity->getDownload());
            $memberSubscription->setDownloadLimit($entity->getDownload());
            $memberSubscription->setBigPicture($entity->getBigPicture());
            $memberSubscription->setMonth($entity->getMonth());
            $memberSubscription->setPaymentTerm($entity->getPaymentTerm());
            $memberSubscription->setDiscount($entity->getDiscount());

            // mengambil data harga per feature dari database
            $subscriptions = $this->repo('JariffAdminBundle:Subscription')->findAll();
            foreach ($subscriptions as $subscription) {
                $data[$subscription->getCategory()][$subscription->getPrice()] = $subscription->getValue();
            }
            $memberSubscription->updateValue($data);
            $this->em()->persist($member);
            // $this->em()->persist($company);
            // $this->em()->persist($entity);
            $this->em()->flush();

            $date   = new \DateTime();
            $amount = $this->repo('JariffMemberBundle:Member')->findTotalToday();
            $number = date('mdY') . str_pad(strval($amount), 3, '0', STR_PAD_LEFT);
            $member->setNumber($number);
            $memberSubscription->setNumber($member->getNumber() + '001');
            $this->em()->flush();

            // $body = "<h2></h2>".
            //                     "\n<br>\n<br>\n<br>A new user didn’t finish signing up! Please check the account information to identify a possible issue. Then call the user immediately to help them activate their account. Follow up with email recap.".
            //                     "\n<br>\n<br><b>Account Number</b>: ".$member->getNumber().
            //                     "\n<br><b>Account Name</b>: ".$entity->getFirstName()." ".$entity->getLastName().
            //                     "\n<br><b>Account Email</b>: ".$entity->getEmail().
            //                     "\n<br>\n<br>";



            //     $body = "<html><head></head><body>".$body."</body></html>";
            //     $subject = 'ATTENTION! No Contract Returned (NCR)';
            //     $this->get('mail_helper')->sendEmail($entity->getEmail(), 'info@stradetegy.com', $entity->getEmail(), $body, $subject);
                // $this->get('mail_helper')->sendEmail($entity->getEmail(), 'yanmi.inc@gmail.com', $entity->getEmail(), $body, $subject);


            $this->success('Congratulation, Your registration data has been saved. Our CS will contact you soon.');
            $content = $this->echoSignSend($memberSubscription);
            
            if ($content != null) {
               $this->success('New contract sent to your email address, please check your inbox');
            } else {
                $this->error('Unable generate your contract, please contact our CS.');
            }
            return $this->redirectUrl('pending_show', array('token' => $entity->getToken()));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            );
    }

    /**
    * Creates a form to create a Pending entity.
    *
    * @param Pending $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Pending $entity)
    {
        $form = $this->createForm(new PendingType(), $entity, array(
            'action' => $this->generateUrl('pending_create'),
            'method' => 'POST',
            ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Pending entity.
     *
     * @Route("/signup", name="signup")
     * @Method("GET")
     * @Template("JariffMemberBundle:Pending:new.html.twig")
     */
    public function newAction()
    {
        $entity = new Pending();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            );
    }

        /**
     * Displays a form to create a new Pending entity.
     *
     * @Route("/signup/v2", name="signup2")
     * @Method("GET")
     * @Template("JariffMemberBundle:Pending:new2.html.twig")
     */
        public function new2Action()
        {
            $entity = new Pending();
            $form   = $this->createCreateForm($entity);

            return array(
                'entity' => $entity,
                'form'   => $form->createView(),
                );
        }


    /**
     * Displays a form to create a new Pending entity.
     *
     * @Route("member/subscription/new", name="new_subcsription")
     * @Method("GET")
     * @Template("JariffMemberBundle:Pending:new2.html.twig")
     */
        public function newSubscriptionAction()
        {
            $entity = new Pending();
            $form   = $this->createCreateForm($entity);

            return array(
                'entity' => $entity,
                'form'   => $form->createView(),
                );
        }

    /**
     * Displays a form to create a new Pending entity.
     *
     * @Route("/member/subscribe", name="member_subscribe")
     * @Method("GET")
     * @Template("JariffMemberBundle:Pending:new2.html.twig")
     */
    public function subscribeAction()
    {
        $member = $this->get('security.context')->getToken()->getUser();
        $entity = $this->repo('JariffMemberBundle:Pending')->findOneByEmail($member->getEmail());
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * disini record-ajax digunakan dalam filtering untuk landing pages session
     * cek Jariff\MemberBundle\Event\LandingListener
     * 
     * @Route(
     *     "/signup/record-ajax", 
     *     name="signup_record_ajax"
     * )
     */
    public function pendingRecordAction(Request $request)
    {
        $data = $request->get("pending");
        $toLeave = $request->request->get("toLeave");
        // echo $toLeave;

        if (!empty($data['email'])) {
            $entity = $this->repo("JariffAdminBundle:Inbound")->findOneByEmail($data['email']);

            if (!$entity) {
                $entity = new Inbound();
                $entity->setEmail($data['email']);
                $this->em()->persist($entity);
            }
            $body = "<h2></h2>".
                                "\n<br>\n<br>\n<br>A new user just didn’t finish signing up! Please check the account/payment information to identify a possible issue. Then call the user immediately to help them activate their account. Follow up with email recap".
                                "\n<br>\n<br><b>Account Name</b>: ".$entity->getName().
                                "\n<br><b>Account Email</b>: ".$data['email'].
                                "\n<br><b>Phone</b>: ".$entity->getPhone().
                                "\n<br><b>Company</b>: ".$entity->getBusiness().
                                "\n<br><b>Country</b>: ".$entity->getCountry().
                                "\n<br><b>Visited Page</b>: ".$request->getSession()->get('visitedPage').
                                "\n<br><b>Source</b>: Signup Form\n<br>\n<br>";

            $entity->setPhone($data['phone']);
            $entity->setBusiness($data['companyName']);
            $entity->setName($data['firstName'].' '.$data['firstName']);
            $entity->setCountry($data['country']);
            $entity->setDescription($body);
            $entity->setIpAddress($request->getClientIp());
            $entity->setVisitedPage($request->getSession()->get('visitedPage'));
            $entity->setSource('incomplete_sign_up');
            $this->em()->persist($entity);

            $this->em()->flush();

            if ($toLeave == 1) {

                $body = "<html><head></head><body>".$body."</body></html>";
                $subject = 'ATTENTION! Incomplete Sign Up (ISU)';
                $this->get('mail_helper')->sendEmail($entity->getEmail(), 'info@stradetegy.com', $entity->getEmail(), $body, $subject);
                // $this->get('mail_helper')->sendEmail($entity->getEmail(), 'yanmi.inc@gmail.com', $entity->getEmail(), $body, $subject);
            }
            return new Response('ok');
        } else {
            return new Response('no');
        }
    }

    /**
     * Finds and displays a Pending entity.
     *
     * @Route("/activate/{token}", name="pending_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($token)
    {
        $entity = $this->repo('JariffMemberBundle:Pending')->findOneByToken($token);

        if (!$entity) {
            return $this->errorRedirect('Data invalid');
        }

        return array(
            'entity'      => $entity,
            );
    }
}
