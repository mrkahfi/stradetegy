<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Jariff\AdminBundle\Form\Subscriber\SearchFormSubscriber;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('collect','choice',array(
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
            ))
            ->add('condition','choice',array(
                'label_attr' => array('style' => 'display:none'),
                'property_path' => null,
                'mapped' => false,
                'data' => !empty($options['condition']) ? $options['condition'] : 'and',
                'choices' => array(
                    'and' => 'And',
                    'or' => 'Or',
                    'or_not' => 'Or Not',
                )
            ))
            ->add('q','text',array(
                'label_attr' => array('style' => 'display:none'),
                'property_path' => null,
                'data' => $options['q'],
            ))
            ->add('from','text',array(
                'label_attr' => array('style' => 'display:none'),
                'required'      => false,
                'property_path' => null,
                'mapped' => false,
                'data' => $options['range'],
                'attr' => array('class' => 'datepicker')
            ))
            ->add('to','text',array(
                'label_attr' => array('style' => 'display:none'),
                'required'      => false,
                'property_path' => null,
                'mapped' => false,
                'data' => $options['range'],
                'attr' => array('class' => 'datepicker')
            ))
            ->add('review','hidden',array(
                'label_attr' => array('style' => 'display:none'),
                'required'      => false,
                'attr' => array('class' => 'j-load-count'),
                'property_path' => null,
                'data' => $options['rev'],
                'mapped' => false,
            ))
            ->addEventSubscriber(new SearchFormSubscriber());

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data' => null,
            'q' => null,
            'collect' => null,
            'range' => null,
            'rev' => null,
            'condition' => null

        ));
    }


    public function getName()
    {
        return 'search_form';
    }
}
