<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments;

use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomCountryDataFormType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice',
                array(
                    'label' => 'Country',
                    'attr' => array('class' => 'j-choice-country'),
                    'choices' => $this->staticData(),
                    'expanded' => false,

                ));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\CustomCountryData'
        ));
    }

    public function staticData(){
        return  array(
            'us' => 'USA',
            'ar' => 'Argentina',
            'br' => 'Brazil',
            'ca' => 'Canada',
            'cl' => 'Chile',
            'co' => 'Colombia',
            'cr' => 'Costa Rica',
            'ec' => 'Ecuador',
            'jnpt' => 'India',
            'mx' => 'Mexico',
            'pa' => 'Panama',
            'pe' => 'Peru',
            'py' => 'Paraguay',
            'uy' => 'Uruguay',
            've' => 'Venezuela',
        );
    }


    public function getName()
    {
        return 'custom_country_shipments';
    }
}
