<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\USExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldUSExportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('field_us_custom', 'choice',
            array(
                'label_attr' => array('style' => 'display:none'),
                'attr' => array('col_md_ch' => 'col-md-4','col_md' => 'col','style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                'choices' => array(
                    "source"=>"Source",
                    "shipment_id"=>"Shipment ID",
                    "record_date"=>"Record Date",
                    "departure_date"=>"Departure Date",
                    "booking_location"=>"Booking Location",
                    "us_port_id"=>"US Port ID",
                    "us_port"=>"US Port",
                    "destination_port_id"=>"Destination Port ID",
                    "destination_port"=>"Destination Port",
                    "destination"=>"Destination",
                    "destination_country_iso3"=>"Destination Country Iso 3",
                    "bill_of_lading_number"=>"Bill Of Lading Number",
                    "manifest_scac"=>"Manifest SCAC",
                    "vessel_scac"=>"Vessel SCAC",
                    "vessel_name"=>"Vessel Name",
                    "vessel_imo"=>"Vessel IMO",
                    "vessel_flag"=>"Vessel Flag",
                    "flight_code"=>"Flight Code",
                    "shipper_name"=>"Shipper Name",
                    "shipper_full_address"=>"Shipper Full Address",
                    "shipper_country_iso3"=>"Shipper Country ISO 3",
                    "container_id"=>"Container ID",
                    "container_number"=>"Container Number",
                    "container_quantity"=>"Container Quantity",
                    "container_quantity_units"=>"Container Quantity Units",
                    "container_measurement"=>"Container Measurement",
                    "container_seal_number"=>"Container Seal Number",
                    "container_type"=>"Container Type",
                    "container_tare_weight_kg"=>"Container Tare Weight Kg",
                    "container_gross_weight_kg"=>"Container Gross Weight Kg",
                    "container_teu"=>"Container TEU",
                    "item_gross_weight_kg"=>"Item Gross Weight Kg",
                    "item_measurement"=>"Item Measurement",
                    "item_quantity"=>"Item Quantity",
                    "item_quantity_units"=>"Item Quantity Units",
                    "item_description"=>"Item Description"
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
