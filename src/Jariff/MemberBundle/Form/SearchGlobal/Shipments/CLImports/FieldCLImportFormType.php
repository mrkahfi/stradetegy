<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\CLImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldClImportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_us_custom', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('col_md_ch' => 'col-md-4','col_md' => 'col','style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                    'choices' => array(
                        "date"=>"Date",
                        "custom"=>"Custom",
                        "import_id"=>"Import ID",
                        "importer_id"=>"Importer ID",
                        "importer_id_check_digit"=>"Importer ID Check Digit",
                        "importer"=>"Importer",
                        "hs_code"=>"HS Code",
                        "hs_code_description"=>"HS Code Description",
                        "product"=>"Product",
                        "brand"=>"Brand",
                        "variety"=>"Variety",
                        "description"=>"Description",
                        "description_2"=>"Description 2",
                        "description_3"=>"Description 3",
                        "description_4"=>"Description 4",
                        "origin_country"=>"Origin Country",
                        "purchase_country"=>"Purchase Country",
                        "transport_type"=>"Transport Type",
                        "type_of_payment"=>"Type Of Payment",
                        "origin_port"=>"Origin Port",
                        "landing_port"=>"Landing Port",
                        "transport_company"=>"Transport Company",
                        "load_type"=>"Load Type",
                        "package_type"=>"Package Type",
                        "gross_weight"=>"Gross Weight",
                        "incoterm"=>"Incoterm",
                        "tax"=>"Tax",
                        "quantity"=>"Quantity",
                        "measure_unit"=>"Measure Unit",
                        "us_fob"=>"US FOB",
                        "us_freight"=>"US Freight",
                        "us_insurance"=>"US Insurance",
                        "us_cif"=>"US CIF",
                        "us_cif_unit"=>"US CIF Unit",
                        "us_fob_unit"=>"US FOB Unit",
                        "transport_company_country"=>"Transport Company Country",
                        "us_tax"=>"US Tax",
                        "packages_quantity"=>"Packages Quantity",
                        "economic_zone"=>"Economic Zone",
                        "importer_economic_key"=>"Importer Economic Key",
                        "manifest_number"=>"Manifest Number",
                        "manifest_date"=>"Manifest Date",
                        "transport_document_number"=>"Transport Document Number",
                        "transport_document_date"=>"Transport Document Date",
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
