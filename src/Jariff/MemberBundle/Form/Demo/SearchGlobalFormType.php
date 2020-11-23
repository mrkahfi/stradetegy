<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\Demo;

use Jariff\MemberBundle\Form\Demo\Field\FieldConditionFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Jariff\MemberBundle\Form\Demo\Field\FieldCollectFormType;
use Jariff\MemberBundle\Form\Demo\Field\FieldQFormType;


class SearchGlobalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('category', 'choice',
            array(
                'label_attr' => array('style' =>'display:none'),
                'choices'  => array('buyers' => 'Buyers', 
                    'suppliers' => 'Suppliers', 'logistics' => 'Logistics', 'shipments' => 'Shipments'),
               'expanded' => true,
               'multiple' => false,
               'preferred_choices' => array('buyers')
                ))
        ->add('q', 'text',
            array(
                'label_attr' => array('style' =>'display:none'),
                'attr' => array('class' => 'sm-form-control','placeholder' => 'TYPE AND HIT ENTER')
                ))

        ;


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\SearchGlobalModel'
            ));
    }


    public function getName()
    {
        return 'search_global_form_type';
    }
}
