<?php

namespace Jariff\MemberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Jariff\AdminBundle\Entity\Inbound;
use Jariff\MemberBundle\Entity\MemberNotify;
use Jariff\ProjectBundle\Controller\BaseController;

    /**
     * @Route("/member")
     *
     **/

    class TicketController extends BaseController
    {

     /**
     * @Route(
     *     "/support-submit",
     *     name = "support_submit_inbound",
     *     options = {"expose"=true}
     * )
     * @Method("POST")
     */
     public function supportTicketAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $data      = $request->get("form");
        if (isset($data['subject'])  && isset($data['message'])) {
            $user = $this->get('security.context')->getToken()->getUser();
            $email = $user->getEmail();
            $entity = new Inbound();

            $entity->setEmail($user->getName());
            $entity->setName($user->getEmail());
            $entity->setMember($user);
            $entity->setDescription('Subject : '.$data['subject'].'<br>Message : '.$data['message']);
            $entity->setSource('ticket');
            $em->persist($entity);

            $em->flush();

            $dataMember = array(
                'subject' => $data['subject'],
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'message' => $data['message']
            );

            $templating = $this->get('templating');

            $message = \Swift_Message::newInstance()
                ->setSubject("Ticket From Member ".$user->getName()." ".$user->getEmail())
                ->setFrom('no-reply@stradetegy.com')
                ->setTo("info@stradetegy.com")
                ->setCc('fendithuk@gmail.com')
//                    ->setBcc('ardianys@outlook.com')
                ->setBody($templating->render('JariffMemberBundle:Email:ticket.txt.twig', $dataMember))
                ->addPart($this->renderView('JariffMemberBundle:Email:ticket.html.twig', $dataMember), 'text/html')
            ;
            $this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add('success', 'Your request has been sent! Thanks!');
            return $this->redirect($this->generateUrl('support_ticket_inbound_form'));      
        }
    }

    /**
     * @Route(
     *     "/support-reply",
     *     name = "support_reply",
     *     options = {"expose"=true}
     * )
     * @Method("POST")
     */
     public function supportReplyAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $data      = $request->get("form");
        if (isset($data['subject'])  && isset($data['message'])) {
            $user =$this->getUser();
            $email = $user->getEmail();
            $entity = new Inbound();

            $entity->setEmail($user->getEmail());
            $entity->setName($user->getName());
            $entity->setMember($user);
            $entity->setDescription('Subject : '.$data['subject'].'<br>Message : '.$data['message']);
            $entity->setSource('ticket');
            $em->persist($entity);

            $em->flush();



            $request->getSession()->getFlashBag()->add('success', 'Your request has been sent! Thanks!');
            return $this->redirect($this->generateUrl('support_ticket_inbound_form'));      
        }
    }


    /**
     * @Route(
     *     "/support-form",
     *     name = "support_ticket_inbound_form",
     *     options = {"expose"=true}
     * )
     * @Template("JariffMemberBundle:Ticket:support.html.twig")
     */
    public function supportFormAction() {
        $entity = new Inbound();
        $user = $this->get('security.context')->getToken()->getUser();
        $entity->setMember($user);
        $entity->setSource('ticket');
        $form = $this->createFormBuilder($entity)
        ->add('subject', 'text', array(
            'label' => 'Subject',
            'mapped' => false))
        ->add('message', 'textarea', array(
            'label' => 'Message',
            'attr' => array('cols' => '5', 'rows' => '19'),
            'mapped' => false))
        ->add('submit', 'submit', array(
            'attr' => array(
                'class' => 'button_small'))
        )
        ->getForm();

        $checkNotify = $this->repo("JariffMemberBundle:MemberNotify")->findOneBy(array("member" => $this->user()->getId()));

        if (!$checkNotify) {

            $newNotify = new MemberNotify();
            $newNotify->setMember($this->user());
            $newNotify->setCategory("noticed dashboard");
            $newNotify->setIntervals(48);
            $newNotify->setReaded(1);
            $newNotify->setReadLater(1);
            $this->persist($newNotify);
            $this->flush();


        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            );
    }

    /**
     * @Route(
     *     "/support-thread/{id}",
     *     name = "support_thread",
     *     options = {"expose"=true}
     * )
     * @Template("JariffMemberBundle:Ticket:thread.html.twig")
     */
    public function supportThreadAction($id) {
        $em = $this->getDoctrine()->getManager();
        $inbound = $em->getRepository('JariffAdminBundle:Inbound')->find($id);
        $entity = new Inbound();
        $user = $this->get('security.context')->getToken()->getUser();
        $entity->setMember($user);
        $entity->setSource('ticket');
        $form = $this->createFormBuilder($entity)
        ->add('subject', 'text', array(
            'label' => 'Subject',
            'mapped' => false))
        ->add('message', 'textarea', array(
            'label' => 'Message',
            'attr' => array('cols' => '5', 'rows' => '19'),
            'mapped' => false))
        ->add('submit', 'submit', array(
            'attr' => array(
                'class' => 'button_small'))
        )
        ->getForm();

        return array(
            'entity' => $entity,
            'inbound' => $inbound,
            'form'   => $form->createView(),
            );
    }

    /**
     * @Route(
     *     "/test-template",
     *     name = "test_template"
     *    
     * )
     *  @Template("JariffMemberBundle:Email:base_template_email.html.twig")
     */
     public function testTemplateAction(Request $request) {
            return array();
     }

}
