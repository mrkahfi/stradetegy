<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Form\Subscriber;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MemberAttachmentSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();


        if ($data->getCategory() == 'subscription') {
            $form->add('type', 'choice', array(
                    'required'    => true,
                    'empty_value' => '---',
                    'choices'     => array(
                        'Report' => 'Report',
                        'Contract' => 'Contract',
                )));
        } elseif ($data->getCategory() == 'payment') {
            $form->add('type', 'choice', array(
                    'required'    => true,
                    'empty_value' => '---',
                    'choices'     => array(
                        'Wire Payment Advice' => 'Wire Payment Advice',
                        'Paypal Payment Advice' => 'Paypal Payment Advice',
                        'Credit Card Charge' => 'Credit Card Charge',
                )));
        }
   }

}
