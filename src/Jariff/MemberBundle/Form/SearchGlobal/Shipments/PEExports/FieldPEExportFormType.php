<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\PEExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldPEExportFormType extends AbstractType
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
                        "dua_id" => "Dua ID",
                        "serie" => "Serie",
                        "custom" => "Custom",
                        "ruc_exporter_id" => "Ruc Exporter ID",
                        "exporter" => "Exporter",
                        "exporter_address" => "Exporter Address",
                        "exporter_department" => "Exporter Department",
                        "exporter_state" => "Exporter State",
                        "exporter_district" => "Exporter District",
                        "exporter_phone" => "Exporter Phone",
                        "exporter_fax" => "Exporter Fax",
                        "hs_code" => "HS Code",
                        "hs_code_description" => "HS Code Description",
                        "cargo_description" => "Cargo Desctription",
                        "cargo_description_1" => "Cargo Desctription 1",
                        "cargo_description_2" => "Cargo Desctription 2",
                        "cargo_description_3" => "Cargo Desctription 3",
                        "cargo_description_4" => "Cargo Desctription 4",
                        "us_fob" => "US FOB",
                        "net_weight" => "Net Weight",
                        "gross_weight" => "Gross Weight",
                        "quantity" => "Quantity",
                        "measure_unit" => "Measure",
                        "us_fob_unit" => "US FOB Unit",
                        "commercial_quantity" => "Commercial Quantity",
                        "commercial_measure_unit" => "Commercial Measure Unit",
                        "transport_type" => "Transport Type",
                        "bank" => "Bank",
                        "destination_country" => "Desctination Country",
                        "destination_port" => "Destination Port",
                        "customs_agent" => "Customs Agent",
                        "transport_company" => "Transport Company",
                        "ship" => "Ship"
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
