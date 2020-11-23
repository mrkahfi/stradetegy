<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldUsImportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('field_us_custom', 'choice',
            array(
                'label_attr' => array('style' => 'display:none'),
                'attr' => array('col_md_ch' => 'col-md-4','col_md' => 'col','style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                'choices' => array(
                    'actual_arrival_date' => 'Arrival Date',
                    'consignee_name' => 'Consignee',
                    'shipper_name' => 'Shipper',
                    'quantity' => 'Quantity',
                    'weight' => 'weight',
                    'product_desc' => 'Product Description',
                    'marks_and_numbers' => 'Marks Number',
                    'mode_of_transportation' => 'Mode Of Transportation',
                    'last_vist_foreign_port' => 'Last Visit Foreign Port',
                    'teu' => 'TEU',
                    'weight_unit' => 'Weight Unit',
                    'quantity_unit' => 'Quantity Unit',
                    'measure' => 'Measure',
                    'measure_unit' => 'Measure Unit',
                    'container_id' => 'Container Id',
                    'shipper_address' => 'Shipper Address',
                    'consignee_address' => 'Consignee Address',
                    'notify_party_name' => 'Nptify Party Name',
                    'hs_code' => 'HS Code',

                    'estimate_arrival_date' => 'Estimate Arrival Date',
                    'bill_of_lading' => 'Bill Of Lading',
                    'master_bill_of_lading' => 'Master Bill Of Lading',
                    'bill_type_code' => 'Bill Type Code',
                    'carrier_sasc_code' => 'Carrier SASC Code',
                    'vessel_country_code' => 'Vessel Country Code',
                    'vessel_code' => 'Vessel Code',
                    'vessel_name' => 'Vessel Name',
                    'voyage' => 'Voyage',
                    'inbond_type' => 'Inbond Type',
                    'manifest_no' => 'Manifest No',
                    
                    'loading_port' => 'Loading Port',
                    
                    'us_clearing_district' => 'US Clearing District',
                    'unloading_port' => 'Unloading Port',
                    'place_of_receipt' => 'Place Of Receipt',
                    'country' => 'Country',
                    'country_sure_level' => 'Country Sure Level',
                    'weight_in_kg' => 'Weight In KG',
                    
                    
                    'measure_in_cm' => 'Measure In CM',
                    
                    
                    'container_size' => 'Container Size',
                    'container_type' => 'Container Type',
                    'container_desc_code' => 'Container Desc Code',
                    'container_load_status' => 'Container Load Status',
                    'container_type_of_service' => 'Container Type Of Service',
                    
                    'raw_shipper_name' => 'Raw Shipper Name',
                    'raw_shipper_addr1' => 'Raw Shipper Address 1',
                    'raw_shipper_addr2' => 'Raw Shipper Address 2',
                    'raw_shipper_addr3' => 'Raw Shipper Address 3',
                    'raw_shipper_addr4' => 'Raw Shipper Address 4',
                    'raw_shipper_addr_others' => 'Raw Shipper Address Others',
                    
                    'raw_consignee_name' => 'Raw Consignee Name',
//                        'raw_consignee_address' => 'Raw Consignee Address',
                    'raw_consignee_addr1' => 'Raw Consignee Address 1',
                    'raw_consignee_addr2' => 'Raw Consignee Address 2',
                    'raw_consignee_addr3' => 'Raw Consignee Address 3',
                    'raw_consignee_addr4' => 'Raw Consignee Address 4',
                    
                    'raw_notify_party_name' => 'Raw Notify Party Name',
                    'raw_notify_party_addr1' => 'Raw Notify Party Address 1',
                    'raw_notify_party_addr2' => 'Raw Notify Party Address 2',
                    'raw_notify_party_addr3' => 'Raw Notify Party Address 3',
                    'raw_notify_party_addr4' => 'Raw Notify Party Address 4',
                    
                    'hs_code_sure_level' => 'HS Code Sure Level',
                    
                    'cif' => 'CIF',
                    'indicator_of_true_supplier' => 'Indicator Of True Supplier',
                    'end' => 'END',
                    'raw_notify_party_addr_others' => 'Raw Notify Party Address Others',
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
