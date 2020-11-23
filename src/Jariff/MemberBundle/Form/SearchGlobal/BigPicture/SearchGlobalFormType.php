<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\BigPicture;

use Jariff\MemberBundle\Form\Demo\Field\FieldConditionFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Jariff\MemberBundle\Form\Demo\Field\FieldCollectFormType;
use Jariff\MemberBundle\Form\Demo\Field\FieldQFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomCountryDataFormType;


class SearchGlobalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', 'choice',
                array(
                'label' => 'Country',
                    'attr' => array('class' => 'form-control'),
                    'choices' => $this->staticData(),
                    'expanded' => false,

                ))
        ->add('category', 'choice',
            array(
                'label' => 'Category',
                'choices'  => array( 
                    'suppliers' => 'Exports',
                    'buyers' => 'Imports'),
               'expanded' => false,
               'multiple' => false,
               'attr' => array('class' => 'form-control')
                ))
        ->add('q', 'text',
            array(
                'label' => 'Company',
                'attr' => array('class' => 'form-control','placeholder' => 'TYPE AND HIT ENTER')
                ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\SearchBigPictureModel'
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
        return 'search_global_form_type';
    }
}
