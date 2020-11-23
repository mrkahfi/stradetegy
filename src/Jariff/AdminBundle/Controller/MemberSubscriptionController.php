<?php

namespace Jariff\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Jariff\AdminBundle\Entity\Inbound;
use Jariff\AdminBundle\Entity\RepresentativeHistory;
use Jariff\AdminBundle\Form\MemberSubscriptionType;
use Jariff\MemberBundle\Entity\Invoice;
use Jariff\MemberBundle\Entity\Member;
use Jariff\MemberBundle\Entity\MemberHistory;
use Jariff\MemberBundle\Entity\MemberSubscription;

/**
 * MemberSubscription controller.
 */
class MemberSubscriptionController extends BaseController
{
    /**
     * ping from adobe
     *
     * @Route("/esign/member-subscription/{subscription}", name="esign_ping_member_subscription")
     */
    public function pingMemberAction(Request $request, $subscription)
    {
        $entity = $this->repo('JariffMemberBundle:MemberSubscription')->find($subscription);
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

            $member = $entity->getMember();
            $contact = $member->getProfile();
            $inbound = new Inbound();
            $inbound->setEmail($contact->getEmail());
            $inbound->setPhone($contact->getPhone());
            $inbound->setBusiness($contact->getCompanyName());
            $inbound->setName($contact->getFirstName().' '.$contact->getLastName());
            $inbound->setSource('contract_signed');
            $inbound->setDescription('Member #'.$member->getNumber().' new contract document signed.');
            $inbound->setMember($member);

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
        $content  = $this->renderView('JariffMemberBundle:Esign:subscription-agreement.html.twig', array('subscription'  => $entity));
        $dir      = $this->get('kernel')->getRootDir().'/contracts/'.$entity->getDateCreate()->format('Y/m/d/');
        $filename = $dir.$entity->getToken().'.pdf';

        if(!is_dir($dir)){
            if(!mkdir($dir, 0755, true)) {
                throw new \Exception('Failed to create directory '.$dir);
            }
        }

        file_put_contents($filename, $this->get('knp_snappy.pdf')->getOutputFromHtml($content));

        $params = $this->container->getParameter('jariff_admin');
        $S      = new \SOAPClient($params['echosign_url']);
        $r      = $S->sendDocument(array(
                    'apiKey'               => $params['echosign_api_key'],
                    'documentCreationInfo' => array(
                        'recipients' => array(
                            'RecipientInfo' => array(
                                'email' => $entity->getMember()->getEmail(),
                                'role'  => 'SIGNER'
                            )
                        ),
                        'ccs'           => 'ardianys@outlook.com',
                        'name'          => "Stradetegy New Contract",
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
                                'signedDocumentUrl' => $this->generateUrl('esign_ping_member_subscription', array('subscription' => $entity->getId()), true)
                            )
                        )
                    ),
                ));

        $entity->setDocumentKey($r->documentKeys->DocumentKey->documentKey);
        $entity->setDocumentStatus('OUT_FOR_SIGNATURE');
        $this->em()->flush();
        return true;
    }

    /**
     * @Route(
     *     "/admin/member/{id}/cancel-request/update",
     *     name = "admin_member_cancel_request_update",
     *     requirements = {
     *       "id"  : "\d+"
     *     }
     * )
     * @Template("JariffMemberBundle:MemberSubscription:cancel.html.twig")
     */
    public function cancelUpdateAction(Request $request, $id)
    {
        $member = $this->repo('JariffMemberBundle:Member')->find($id);
        $entity = $this->repo('JariffMemberBundle:MemberSubscription')->findOneBy(array(
            'member'  => $id,
            'active' => true
        ));

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        $form = $this->createCancelForm($entity);
        $form->handleRequest($request);

        // $entity->setActive(false);
        $entity->setCanceled(true);
        $entity->setCancelDate(new \DateTime());

        if ($form->isValid()) {

            $uow = $this->em()->getUnitOfWork();
            $uow->computeChangeSets();
            $changesets = $uow->getEntityChangeSet($entity);
            foreach ($changesets as $key => $value) {
                $oldvalue = ($value[0] instanceof \DateTime) ? $value[0]->format('Y-m-d H:i:s') : $value[0];
                $newvalue = ($value[1] instanceof \DateTime) ? $value[1]->format('Y-m-d H:i:s') : $value[1];
                $history = new MemberHistory();
                $history->setColumn($key);
                $history->setDescription($this->user()->getName().' update member subscription '.$key.' from "'.$oldvalue.'" become "'.$newvalue.'"');
                $history->setNewValue($newvalue);
                $history->setOldValue($oldvalue);
                $history->setTable('member_subscription');
                $history->setAdmin($this->user())    ;
                $history->setMember($member);

                $this->persist($history);
            }

            $this->success('Ok');
            $this->flush();
            return $this->redirectUrl('admin_member_subscription_show', array('id' => $id));
        }

        return array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        );
    }

    private function createCancelForm(MemberSubscription $subscription)
    {
        return $this->createFormBuilder($subscription, array('data_class' => 'Jariff\MemberBundle\Entity\MemberSubscription'))
            ->setAction($this->generateUrl('admin_member_cancel_request_update', array('id' => $subscription->getMember()->getId())))
            ->setMethod('PUT')
            ->add('cancelReason', 'textarea', array(
                    'attr' => array(
                        'style' => 'width: 90%;',
                        'rows'  => '5',
                    ),
                ))
            ->add('member', 'hidden_member')
            ->add('submit', 'submit', array('label' => 'Save'))
            ->getForm()
        ;
    }

    /**
     * @Route(
     *     "/admin/member/{id}/cancel-request",
     *     name = "admin_member_cancel_request",
     *     requirements = {
     *       "id"  : "\d+"
     *     }
     * )
     * @Method("GET")
     * @Template()
     */
    public function cancelAction($id)
    {
        $member = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$member) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        $entity = $this->repo('JariffMemberBundle:MemberSubscription')->findOneBy(array(
            'member' => $id,
            'active' => true
        ));

        if (!$entity) {
            return $this->errorRedirect('This user doesn\'t have active subscription', 'admin_member_subscription_show', array('id' => $id));
        }

        $form = $this->createCancelForm($entity);

        return array(
            'member' => $member,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route(
     *     "/admin/member/{id}/subscription/json-index", 
     *     name = "admin_member_subscription_json_index",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     */
    public function jsonIndexAction(Request $request, $id)
    {

        $qb = $this->repo('JariffMemberBundle:MemberSubscription')
            ->createQueryBuilder('subscription')
            ->select('subscription')
            ->andWhere('subscription.member = '.$id)
            // ->leftJoin('subscription.activity', 'activity', Expr\Join::WITH, 'activity.status = \'Complete\'')
            ;

        // sorting
        $sorters = json_decode($request->get('pq_sort'));
        foreach ($sorters as $sorter){
            $dir = ($sorter->dir == "up") ? 'asc' : 'desc';
            $qb->addOrderBy($sorter->dataIndx, $dir);
        }
        // end sortingMemberEmailAlias
        // filtering
        if (($pq_filter = $request->get('pq_filter')) !== null) {
            $filterObj = json_decode($pq_filter);
            $filters   = $filterObj->data;
             
            foreach ($filters as $filter) {            
                $dataIndx = $filter->dataIndx;  
                $qb->andWhere('subscription.'.$dataIndx.' like :'.$dataIndx)
                   ->setParameter($dataIndx, '%'.$filter->value.'%');
            }
        }
        // end filtering
        
        // get total amount
        $entities = $qb->getQuery()->getResult();
        // prepare output data stats
        $identify = array(
            'totalRecords' => count($entities),
        );

        // paging
        $pq_curpage = ($request->get('pq_curpage') != '') ? intval($request->get('pq_curpage')) : 1;
        $pq_rpp     = ($request->get('pq_rpp') != '') ? intval($request->get('pq_rpp')) : 20;
        $qb->setFirstResult(($pq_curpage * $pq_rpp) - $pq_rpp)
            ->setMaxResults($pq_rpp);
        // end paging

        // get the result
        $entities = $qb->getQuery()->getResult();

        // prepare output data stats
        $identify['curPage'] = $pq_curpage;

        // prepare output data content
        $data = array();
        foreach ($entities as $r) {
            $data[] = array(
                'subscription.id'              => $r->getId(),
                'subscription.active'         => $r->getActive(),
                'subscription.total'           => $r->getTotal(),
                'subscription.discount'        => $r->getDiscount(),
                'subscription.customDiscount'  => $r->getCustomDiscount(),
                'subscription.bigPicture'      => $r->getBigPicture(),
                'subscription.bigPictureValue' => $r->getBigPictureValue(),
                'subscription.dateActivated'   => is_null($r->getDateActivated())?'':$r->getDateActivated()->format('Y/m/d'),
                // 'subscription.cancelDate'      => is_null($r->getCancelDate())?'':$r->getCancelDate()->format('Y/m/d'),
                'subscription.dateExpired'     => is_null($r->getDateExpired())?'':$r->getDateExpired()->format('Y/m/d'),
                'subscription.download'        => $r->getDownload(),
                'subscription.downloadValue'   => $r->getDownloadValue(),
                'subscription.everythingPlan'  => $r->getEverythingPlan(),
                'subscription.history'         => $r->getHistory(),
                'subscription.historyValue'    => $r->getHistoryValue(),
                'subscription.number'          => $r->getNumber(),
                'subscription.search'          => $r->getSearch(),
                'subscription.searchValue'     => $r->getSearchValue(),
                'subscription.training'        => $r->getTraining(),
                'subscription.owner'           => is_null($r->getOwner())?'':$r->getOwner()->getName(),
                'subscription.sales2'          => is_null($r->getSales2())?'':$r->getSales2()->getName(),
            );
        }

        $merge = array_merge($identify, array('data' => $data));

        return $this->json($merge);
    }

    /**
     * @Route(
     *     "/admin/member/{id}/subscription", 
     *     name="admin_member_subscription_show"
     * )
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $member = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$member) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        return array(
            'member' => $member,
        );
    }

    /**
     * Creates a new MemberSubscription entity.
     *
     * @Route("/admin/member/subscription/create", name="admin_subscription_create")
     * @Method("POST")
     * @Template("JariffMemberBundle:MemberSubscription:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new MemberSubscription();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            // update nilai subscription,
            $subscriptions = $this->repo('JariffAdminBundle:Subscription')->findAll();
            foreach ($subscriptions as $subscription) {
                $data[$subscription->getCategory()][$subscription->getPrice()] = $subscription->getValue();
            }
            $entity->updateValue($data);

            // get subscription sebelumnya
            $memberSubscriptions = $this->repo('JariffMemberBundle:MemberSubscription')->findByMember($entity->getMember());
            // set number untuk subscription ini
            $entity->setNumber($entity->getMember()->getNumber().'-'.(count($memberSubscriptions) + 1));

            $memberSubscription = $form->getData();

            $subscription_month_length = 1;

            // calculate monthly price
            $price = $memberSubscription->getHistory() + $memberSubscription->getSearch() + $memberSubscription->getDownload() + $memberSubscription->getBigPicture();
            if ($memberSubscription->getEverythingPlan()){
                $price = 200;
            }

            // calculate discount based on pif / mtm
            $total_discount = 0;
            if($memberSubscription->getPaymentTerm() == 'pif') {
                if ($memberSubscription->getMonth() == '20' ) {
                    $subscription_month_length = 12;
                }
                if ($memberSubscription->getMonth() == '15' ) {
                    $subscription_month_length = 6;
                }
                if ($memberSubscription->getMonth() == '10' ) {
                    $subscription_month_length = 3;
                }
                $subscription_month_percent = intval($memberSubscription->getMonth()) / 100;
                $total_discount             = round($price * $subscription_month_percent * $subscription_month_length);
            }

            // update total price
            $total_payment = $price * $subscription_month_length - $total_discount - $memberSubscription->getCustomDiscount();

            $entity->setTotal(($total_payment < 0)?0:$total_payment);
            $entity->setDiscount($total_discount);
            $this->persist($entity);

            $representativeHistory = new RepresentativeHistory();
            $representativeHistory->setTypeVal(10);
            $representativeHistory->setDescription('New subscription for member #'.$entity->getMember()->getNumber());
            $representativeHistory->setSubscription($entity->getNumber());
            // $representativeHistory->setStatus();
            $representativeHistory->setAdmin($this->user());
            $representativeHistory->setMember($entity->getMember());
            $this->persist($representativeHistory);

            // start new invoice
            $member = $entity->getMember();
            $company = $member->getCompany();

            $invoice = new Invoice();
            $invoice->setAmount($entity->getTotal());
            $invoice->setDescription('New invoice generated by system automatically for new subscription submited');
            $invoice->setMember($member);
            $invoice->setBillToName($company->getBillToName());
            $invoice->setBillToAdress($company->getBillToAdress());
            $invoice->setBillToEmail($company->getBillToEmail());
            $invoice->setBillToPhone($company->getBillToPhone());
            $invoice->setDateIssued(new \DateTime());
            $invoice->setSubscription($entity);
            $invoice->setSales($entity->getOwner());
            $invoice->setType($company->getInvoiceType());

            $invoiceAmount = $this->conn()->fetchColumn('
                SELECT count(i.id) as count
                FROM invoice i
                WHERE 
                i.member_id = '.$member->getId()
            );
            $invoice->setNumber($member->getNumber().'-'.(intval($invoiceAmount)+1));
            $this->persist($invoice);
            $this->success('New subscription created');
            $this->success('New invoice automatically generated');
            // end new invoice

            $this->flush();

            if ($this->echoSignSend($memberSubscription)) {
                $this->success('New contract sent to member email address, ensure they check their inbox');
            } else {
                $this->error('Unable generate new contract, please contact TS.');
            }

            $this->success('Ok');
            return $this->redirectUrl('admin_member_subscription_show', array('id' => $entity->getMember()->getId()));
        }

        return array(
            'entity' => $entity,
            'member' => $entity->getMember(),
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a MemberSubscription entity.
    *
    * @param MemberSubscription $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(MemberSubscription $entity)
    {
        $form = $this->createForm(new MemberSubscriptionType(), $entity, array(
            'action' => $this->generateUrl('admin_subscription_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MemberSubscription entity.
     *
     * @Route(
     *     "/admin/member/{id}/subscription/new",
     *     name = "admin_member_subscription_new",
     *     requirements = {
     *       "id"  : "\d+"
     *     }
     * )
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $member = $this->repo('JariffMemberBundle:Member')->find($id);
        
        $entity = new MemberSubscription();
        $entity->setMember($member);
        $form   = $this->createCreateForm($entity);

        return array(
            'member' => $member,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a MemberSubscription entity.
     *
     * @Route("/super-admin/member/subscription/delete/{id}", name="admin_subscription_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->em()->getRepository('JariffMemberBundle:MemberSubscription')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MemberSubscription entity.');
            }

            $this->remove($entity);
            $this->flush();
        }
        $this->success('Ok');
        return $this->redirectUrl('admin_subscription');
    }

    /**
     * Creates a form to delete a MemberSubscription entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_subscription_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
