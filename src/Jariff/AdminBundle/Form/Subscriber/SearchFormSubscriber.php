<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Form\Subscriber;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SearchFormSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // check if the product object is "new"
        // If you didn't pass any data to the form, the data is "null".
        // This should be considered a new "Product"
//        if (!$data || !$data->getId()) {
            $form->add('collect','choice',array(
                'label_attr' => array('style' => 'display:none'),
                'property_path' => null,
                'mapped' => false,
                'block_name' => 'q',
                'data' => !empty($options['collect']) ? $options['collect'] : 'consignee_name',
                'preferred_choices' => array('and'),
                'choices' => array(
                    'consignee_name' => 'Consignee Name',
                    'consignee_address' => 'Consignee Address',
                    'notify_name' => 'Notify Name',
                    'notify_address' => 'Notify Address',
                    'shipper_name' => 'Shipper Name',
                    'shipper_address' => 'Shipper Address',
                    'container_number' => 'Container Number',
                    'product_description' => 'Product Description',
                    'all' => 'All',
                    'carrier' => 'Carrier',
                    'vessel' => 'Vessel',
                    'voyage' => 'Voyage',
                    'us_port' => 'Us Port',
                    'foreign_port' => 'Foreign Port',
                    'country_of_origin' => 'Country Of Origin',
                    'place_of_receipt' => 'Place Of Receipt',
                    'bill_of_lading' => 'Bill Of Landing',
                ),
            ));
        }
//    }

}
