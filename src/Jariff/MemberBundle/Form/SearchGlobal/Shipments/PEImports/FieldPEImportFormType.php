<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\PEImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldPEImportFormType extends AbstractType
{    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_us_custom', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('col_md_ch' => 'col-md-4', 'col_md' => 'col', 'style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                    'choices' => array(
                        "date"=>"Date",
                        "dua_id"=>"Dua ID",
                        "serie"=>"Serie",
                        "custom"=>"Custom",
                        "ruc_importer_id"=>"Ruc Importer ID",
                        "importer"=>"Importer",
                        "importer_address"=>"Importer Address",
                        "importer_department"=>"Importer Departement",
                        "importer_state"=>"Importer State",
                        "importer_district"=>"Importer District",
                        "importer_phone"=>"Importer Phone",
                        "importer_fax"=>"Importer Fax",
                        "transport_type"=>"Transport Type",
                        "bank"=>"Bank",
                        "origin_country"=>"Origin Country",
                        "acquisition_country"=>"Acquistion Country",
                        "shipping_port"=>"Shipping Port",
                        "hs_code"=>"HS Code",
                        "hs_code_description"=>"HS Code Description",
                        "cargo_description"=>"Cargo Desctription",
                        "cargo_description_1"=>"Cargo Desctription 1",
                        "cargo_description_2"=>"Cargo Desctription 2",
                        "cargo_description_3"=>"Cargo Desctription 3",
                        "cargo_description_4"=>"Cargo Desctription 4",
                        "manufacture_year"=>"Manufacture Year",
                        "us_fob"=>"US FOB",
                        "us_freight"=>"US Freight",
                        "us_insurance"=>"US Insurance",
                        "us_cif"=>"US CIF",
                        "advalorem"=>"Advalorem",
                        "local_tax"=>"Local Tax",
                        "net_weight"=>"Net Weight",
                        "gross_weight"=>"Gross Weight",
                        "quantity"=>"Quantity",
                        "measure_unit"=>"Measure Unit",
                        "us_cif_unit"=>"US CIF Unit",
                        "commercial_quantity"=>"Commercial Quantity",
                        "commercial_measure_unit"=>"Commercial Measure Unit",
                        "package_type"=>"Package type",
                        "packages_quantity"=>"Packages Quantity",
                        "product_status"=>"Product Status",
                        "shipper_name"=>"Shipper Name",
                        "customs_agent"=>"Customs Agent",
                        "transport_company"=>"Transport Company",
                        "incoterm"=>"Incoterm"
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
