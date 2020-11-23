<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExportsSearchShipmentsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('send_mail', 'choice', array(
            'label_attr' => array('class' => 'col-md-12','style' => 'display:none;margin-top:10px'),

            'choices' => array('1' => 'Email Report', '0' => 'Download Now'),
            'attr' => array( 'col_md' => 'col-md-12','style' => "display:inline-flex",'class' => 'j-choices-now'),
            'expanded' => true
            ))
        ->add('file_name', null,
            array(
                'label' => 'File Name',
                'label_attr' => array('class' => 'col-md-12'),
                'attr' => array( 'col_md' => 'col-md-10')
                ))
        ->add('file_type', 'choice',
            array(
                'label_attr' => array('style' => 'display:none'),
                'choices' => array('xls' => '.xls', 'csv' => '.csv'),
                'attr' => array('class' => 'form-control', 'col_md' => 'col-md-2')
                ))
        ->add('max_download', 'choice',
            array(
                'label_attr' => array('class' => 'col-md-12','style' => 'margin-top:10px'),
                'label' => 'No. Of Records',
                'attr' => array('col_md' => "col-md-4",'help'=>'Maximum of 40,000 records per export'),
                'choices' => array(
                    '1' => 'All',
                    '0' => 'or Start Row From',

                    ),
                'expanded' => true,

                ))
        ->add('download_from', 'number',
            array(

                'label_attr' => array('class' => 'col-md-1'),
                'attr' => array('class' => 'form-control', 'col_md' => 'col-md-2',"value" => 1)
                ))
        ->add('download_to', 'number',
            array(
                'label' => 'to',
                'label_attr' => array('class' => 'col-md-1'),
                'attr' => array('class' => 'form-control', 'col_md' => 'col-md-2',"value" => 10)
                ))
        ->add('description', null,
            array(
                'label' => 'Description (Optional)',
                'label_attr' => array('class' => 'col-md-12','style' => 'margin-top:10px'),
                'attr' => array('class' => 'form-control', 'col_md' => 'col-md-12'),
                'required' => false,
                ))
        ->add('email', null,
            array(
                'label' => 'Email',
                'label_attr' => array('class' => 'col-md-12'),
                'attr' => array('class' => 'form-control j-email-showing', 'col_md' => 'col-md-12')
                ))
            // ->add('send_mail', 'checkbox', array(
            //     'label' => 'Email me when download complete',
            //     'required' => false,
            //     'label_attr' => array('class' => 'col-md-12'),
            //     'attr' => array('checked' => 'checked', 'col_md' => 'col-md-12')
            // ))

        ->add('collection', 'hidden', array(
            'label' => 'Email me when done'

            ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Entity\ExportTools'
            ));
    }


    public function getName()
    {
        return 'exports_search_shipments';
    }
}
