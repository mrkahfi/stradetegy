<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\PAExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldPAExportFormType extends AbstractType
{    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_us_custom', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('col_md_ch' => 'col-md-4', 'col_md' => 'col', 'style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                    'choices' => array(
                        "date"=>"Date",
                        "ruc_exporter_id"=>"Ruc Exporter ID",
                        "exporter"=>"Exporter",
                        "hscode"=>"HS Code",
                        "product_description"=>"Product Description",
                        "customs_zone"=>"Customs Zone",
                        "customs_name"=>"Customs Name",
                        "transport_type"=>"Transport Type",
                        "declaration_number"=>"Declaration Number",
                        "destiny_country"=>"Desctiny Country",
                        "net_weight"=>"Net Weight",
                        "gross_weight"=>"Gross Weight",
                        "packages"=>"Packages",
                        "quantity"=>"Quantity",
                        "measure_unit"=>"Measure",
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
