<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\PYExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldPYExportFormType extends AbstractType
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
                        "exporter_id"=>"Exporter ID",
                        "exporter"=>"Exporter",
                        "consignee"=>"Consignee",
                        "destiny_country"=>"Destiny Country",
                        "hs_code"=>"HS Code",
                        "quantity"=>"Quantity",
                        "measure_unit"=>"Measure Unit",
                        "product"=>"Product",
                        "gross_kilo"=>"Gross Kilo",
                        "net_kilo"=>"Net Kilo",
                        "us_fob"=>"US FOB",
                        "us_freight"=>"US Freight",
                        "us_insurance"=>"US Insurance",
                        "us_cif"=>"US CIF",
                        "us_fob_unit"=>"US FOB Unit",
                        "brand"=>"Brand",
                        "custom"=>"Custom",
                        "transport_type"=>"Transport Type",
                        "transport_company"=>"Transport Company",
                        "transportist_country"=>"Transportist Country",
                        "manifest"=>"Manifest"
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
