<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\PYImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldPYImportFormType extends AbstractType
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
                        "importer_id"=>"Importer ID",
                        "importer"=>"Importer",
                        "supplier"=>"Supplier",
                        "origin_country"=>"Origin Country",
                        "hs_code"=>"HS Code",
                        "quantity"=>"Quantity",
                        "measure_unit"=>"Measure Unit",
                        "product"=>"Product",
                        "brand"=>"Brand",
                        "gross_kilo"=>"Gross Kilo",
                        "net_kilo"=>"Net Kilo",
                        "us_fob"=>"US FOB",
                        "us_freight"=>"US Freight",
                        "us_insurance"=>"US Insurance",
                        "us_cif"=>"US CIF",
                        "us_fob_unit"=>"US FOB Unit",
                        "custom"=>"Custom",
                        "transport_type"=>"Transport Type",
                        "transport_company"=>"Transport Company",
                        "transportist_country"=>"Transportist Country",
                        "acquisition_country"=>"Acquisition Country",
                        "manifest"=>"Manifest",
                        "bl_number"=>"BL Number"
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
