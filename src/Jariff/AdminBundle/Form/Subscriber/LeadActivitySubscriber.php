<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Form\Subscriber;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LeadActivitySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if ($data->getEvent()) {
            $form->add('dateTask', 'date', array(
                    'required' => true,
                    'widget'   => 'single_text',
                    'format'   => 'yyyy-MM-dd hh:mm',
                    'attr'     => array(
                        'class' => 'datetime',
                        'data-format' => 'yyyy-MM-dd hh:mm',
                )));
        } elseif ($data->getTask()) {
            $form->add('dateTask', 'date', array(
                    'required' => true,
                    'widget'   => 'single_text',
                    'format'   => 'yyyy-MM-dd ',
                    'attr'     => array(
                        'class' => 'datetime',
                        'data-format' => 'yyyy-MM-dd',
                )));
        }
   }

}
