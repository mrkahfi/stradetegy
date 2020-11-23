<?php

namespace Jariff\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Jariff\AdminBundle\Entity\Inbound;

    /**
     *
     **/

    class TicketController extends Controller
    {

     /**
     * @Route(
     *     "/contact-submit",
     *     name = "contact_submit_inbound",
     *     options = {"expose"=true}
     * )
     * @Method("POST")
     * @Template("JariffMemberBundle:Ticket:contact.html.twig")
     */
     public function contactTicketAction(Request $request) {
        $entity = new Inbound();
        $entity->setSource('ticket');
         $form = $this->createFormBuilder($entity)
        ->add('name', 'text')
        ->add('email', 'text')
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
        $em = $this->getDoctrine()->getManager();
        $data      = $request->get("form");
        if (isset($data['name']) && isset($data['email']) && isset($data['subject'])  && isset($data['message'])) {
            $entity = new Inbound();
            $entity->setEmail($data['email']);
            $entity->setName($data['name']);
            $entity->setDescription('Subject : '.$data['subject'].'<br>Message : '.$data['message']);
            $entity->setSource('ticket');
            $em->persist($entity);
            $em->flush();  
            $request->getSession()->getFlashBag()->add('success', 'Your message has been sent! Thanks!');

            return $this->redirect($this->generateUrl('contact_ticket_inbound_form'));   
        }
    }


    /**
     * @Route(
     *     "/contact-us",
     *     name = "contact_ticket_inbound_form",
     *     options = {"expose"=true}
     * )
     * @Template("JariffMemberBundle:Ticket:contact.html.twig")
     */
    public function contactFormAction() {
        $entity = new Inbound();
        $entity->setSource('ticket');
        $form = $this->createFormBuilder($entity)
        ->add('name', 'text', array(
            'label' => 'Name',
            'attr'  => array(
                'required'  => "required",
                'oninvalid' => "setCustomValidity('Please fill out this field')")))
        ->add('email', 'text')
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
            'form'   => $form->createView(),
            );
    }

    /**
     * @Route(
     *     "/demo-submit",
     *     name = "demo_submit_inbound",
     *     options = {"expose"=true}
     * )
     * @Method("POST")
     * @Template("JariffMemberBundle:Ticket:demo.html.twig")
     */
    public function demoTicketAction(Request $request) {
        $entity = new Inbound();
        $entity->setSource('ticket');
        $form = $this->createFormBuilder($entity)
        ->add('name', 'text')
        ->add('email', 'text')
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
        $em = $this->getDoctrine()->getManager();
        $data      = $request->get("form");
        if (isset($data['name']) && isset($data['email']) && isset($data['subject'])  && isset($data['message'])) {
            $entity = new Inbound();
            $entity->setEmail($data['email']);
            $entity->setName($data['name']);
            $entity->setDescription('Subject : '.$data['subject'].'<br>Message : '.$data['message']);
            $entity->setSource('ticket');
            $em->persist($entity);

            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Your request has been sent! Thanks!');

            return $this->redirect($this->generateUrl('demo_ticket_inbound_form'));      
        }
    }


    /**
     * @Route(
     *     "/demo-form",
     *     name = "demo_ticket_inbound_form",
     *     options = {"expose"=true}
     * )
     * @Template("JariffMemberBundle:Ticket:demo.html.twig")
     */
    public function demoFormAction() {
        $entity = new Inbound();
        $entity->setSource('ticket');
        $form = $this->createFormBuilder($entity)
        ->add('name', 'text')
        ->add('email', 'text')
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
            'form'   => $form->createView(),
            );
    }
}
