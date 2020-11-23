<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\CLExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCLExportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_us_custom', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('col_md_ch' => 'col-md-4', 'col_md' => 'col', 'style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                    'choices' => array(
                        "date" => "Date",
                        "export_id" => "Export ID",
                        "custom" => "Custom",
                        "exporter_id" => "Exporter ID",
                        "exporter_id_check_digit" => "Exporter ID Check Digit",
                        "exporter" => "Exporter",
                        "hs_code" => "HS Code",
                        "hs_code_description" => "HS Code Description",
                        "product" => "Product",
                        "variety" => "Variety",
                        "brand" => "Brand",
                        "description" => "Description",
                        "description_2" => "Description 2",
                        "description_3" => "Description 3",
                        "description_4" => "Description 4",
                        "destiny_country" => "Destiny Country",
                        "transport_type" => "Transport Type",
                        "transport_company" => "Transport Company",
                        "ship_name" => "Ship Name",
                        "load_type" => "Load Type",
                        "origin_port" => "Origin Port",
                        "landing_port" => "Landing Port",
                        "gross_weight" => "Gross Weight",
                        "quantity" => "Quantity",
                        "measure_unit" => "Measure Unit",
                        "us_fob" => "US FOB",
                        "us_freight" => "US Freight",
                        "us_insurance" => "US Insurance",
                        "us_cif" => "US CIF",
                        "us_fob_unit" => "US FOB Unit",
                        "package_type" => "Package Type",
                        "exporter_region" => "Exporter Region",
                        "packages_quantity" => "Packages Quantity",
                        "packages_description" => "Packages Description",
                        "transport_company_country" => "Transport Company Country",
                        "sale_condition" => "Sale Condition",
                        "economic_zone" => "Economic Zone",
                        "exporter_economic_key" => "Exporter Economic Key",
                        "transport_document_number" => "Transport Document Number",
                        "transport_document_date" => "Transport Document Date",
                        "voyage_number" => "Voyage Number",
                        "incoterms" => "Incoterms"
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
