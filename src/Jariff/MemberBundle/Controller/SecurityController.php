<?php 

namespace Jariff\MemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\MemberBundle\Entity\Member;
use Jariff\MemberBundle\Entity\MemberHistory;

class SecurityController extends BaseController
{
    /**
     * @Route(
     *     "/member/change-password/update",
     *     name = "member_change_password_update"
     * )
     * @Template("JariffMemberBundle:MemberSubscription:changePassword.html.twig")
     */
    public function changePasswordUpdateAction(Request $request)
    {
        $member = $this->user();
        $form = $this->createChangeForm($member);
        $form->handleRequest($request);

        $member->setPassword($this->get('jariff_password_encoder')->encodePassword($member->getPassword(), $member->getSalt()));

        if ($form->isValid()) {

            $uow = $this->em()->getUnitOfWork();
            $uow->computeChangeSets();
            $changesets = $uow->getEntityChangeSet($member);
            foreach ($changesets as $key => $value) {
                $history = new MemberHistory();
                $history->setColumn($key);
                $history->setNewValue($value[1]);
                $history->setOldValue($value[0]);
                $history->setTable('member');
                // $history->setAdmin($this->user());
                $history->setMember($member);

                $this->em()->persist($history);
            }

            $this->success('Ok, password changed');
            $this->em()->flush();
            
            return $this->redirectUrl('dashboard_member');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    private function createChangeForm(Member $member)
    {
        return $this->createFormBuilder($member, array('data_class' => 'Jariff\MemberBundle\Entity\Member'))
            ->setAction($this->generateUrl('member_change_password_update'))
            ->setMethod('PUT')
            ->add('password', 'repeated', array(
                'type'            => 'password',
                'invalid_message' => 'The password fields must match.',
                'options'         => array('attr' => array('class' => 'password-field')),
                'required'        => true,
                'first_options'   => array('label' => 'Password'),
                'second_options'  => array('label' => 'Repeat Password'),
            ))
            ->add('submit', 'submit', array('label' => 'Save'))
            ->getForm()
        ;
    }

    /**
     * @Route(
     *     "/member/change-password",
     *     name = "member_change_password"
     * )
     * @Template()
     */
    public function changePasswordAction()
    {
        $form = $this->createChangeForm($this->user());

        return array(
            'member' => $this->user(),
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route("/login", name="member_login")
     * @Template()
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            );
    }

    /**
     * @Route("/reset-password", name="member_password_reset")
     * @Template()
     */
    public function resetpasswordAction(Request $request)
    {
        // var_dump();die();
        $member = $this->repo('JariffMemberBundle:Member')->findOneByEmail($request->request->get('email'));

        if (!$member) {
            return $this->errorRedirect('Internal server error, please contact Customer Service', 'dashboard');
        }

        $data['email'] = $member->getEmail();
        $data['password'] = rand(100000, 999999);

        $hashed = $this->get('jariff_password_encoder')->encodePassword($data['password'], $member->getSalt());
        $member->setPassword($hashed);
        $this->em()->persist($member);
        $this->em()->flush();

        $message = \Swift_Message::newInstance()
            ->setSubject('Stradetegy Password Reset')
            ->setFrom('no-reply@stradetegy.com')
            ->setTo(array($member->getEmail()))
            ->setBcc(array('ardianys@outlook.com'))
            ->setReplyTo('info@stradetegy.com')
            ->setBody(
                $this->renderView('JariffMemberBundle:Email:reset.html.twig', array('data' => $data), 'text/html'))
            ->addPart(
                $this->renderView('JariffMemberBundle:Email:reset.txt.twig', array('data' => $data)))
        ;
        $this->get('mailer')->send($message);
        return $this->successRedirect('Please check your email for new password', 'dashboard');
    }

}