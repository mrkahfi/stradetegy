<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\VEExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldVEExportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_us_custom', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('col_md_ch' => 'col-md-4', 'col_md' => 'col', 'style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                    'choices' => array(
                        "date"=>"Date",
                        "record"=>"Record",
                        "bl"=>"BL",
                        "chapter"=>"Chapter",
                        "chapter_description"=>"Chapter Description",
                        "hs_code"=>"HS Code",
                        "payment"=>"Payment",
                        "hs_code_description"=>"HS Code Description",
                        "exporter"=>"Exporter",
                        "custom"=>"Custom",
                        "transport_type"=>"Transport Type",
                        "dest_country"=>"Dest Country",
                        "dest_port"=>"Dest Port",
                        "gross_weight"=>"Gross Weight",
                        "net_weight"=>"Net Weight",
                        "us_fob_bolivare"=>"US FOB Bolivare",
                        "us_fob"=>"US FOB"
                    ),
                    'multiple' => true,
                    'expanded' => true,
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\FieldUsCustom'
        ));
    }


    public function getName()
    {
        return 'field_us_custom';
    }
}
