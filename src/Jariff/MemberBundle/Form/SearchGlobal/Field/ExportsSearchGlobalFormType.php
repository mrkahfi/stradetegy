<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExportsSearchGlobalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file_name', null,
                array(
                    'label' => 'File Name',
                    'label_attr' => array('class' => 'col-md-2'),
                    'attr' => array('class' => 'col-md-6')
                ))
            ->add('file_type', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'choices' => array('xls' => '.xls', 'csv' => '.csv'),
                    'attr' => array('class' => 'col-md-3')
                ))
            ->add('download_from', 'number',
                array(
                    'label' => 'Start Row From',
                    'label_attr' => array('class' => 'col-md-2'),
                    'attr' => array('class' => 'col-md-3')
                ))
            ->add('download_to', 'number',
                array(
                    'label' => 'to',
                    'label_attr' => array('class' => 'col-md-1'),
                    'attr' => array('class' => 'col-md-3')
                ))
            ->add('description', null,
                array(
                    'label' => 'Description (Optional)',
                    'label_attr' => array('class' => 'col-md-2'),
                    'attr' => array('class' => 'col-md-9'),
                    'required'  => false,
                ))
            ->add('email', null,
                array(
                    'label' => 'Email',
                    'label_attr' => array('class' => 'col-md-2'),
                    'attr' => array('class' => 'col-md-9')
                ))
            ->add('send_mail', 'checkbox', array(
                'label'     => 'Email me when download complete',
                'required'  => false,
                'label_attr' => array('class' => 'col-md-3'),
                'attr' => array('class' => 'col-md-1','checked' => 'checked')
            ))
        ;


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\DocumentBundle\Document\ExportsSearchGlobal'
        ));
    }


    public function getName()
    {
        return 'exports_search_global';
    }
}
