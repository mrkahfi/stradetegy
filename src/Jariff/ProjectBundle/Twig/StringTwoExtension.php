<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\ProjectBundle\Twig;

use Symfony\Component\Validator\Constraints\DateTime;
use Twig_Extension;
use Twig_Filter_Method;
use Twig_Function_Method;
use Jariff\ProjectBundle\Util\Util;

class StringTwoExtension extends Twig_Extension
{
    private $event_dispatcher;
    private $pass = "stradetegyriffcorp";

    public function setEd($event_dispatcher)
    {
        $this->event_dispatcher = $event_dispatcher;
    }

    private $sc;
    public function setSc($sc)
    {
        $this->sc = $sc;
    }

    //sebenarnya entity manager belum dibutuhkan di menu

    private $em;

    public function setEm($em)
    {
        $this->em = $em;
    }

    private $session;

    public function setSession($session)
    {
        $this->session = $session;
    }

    public function getFilters()
    {
        return array(
            'setting_gross_weight' => new Twig_Filter_Method($this, 'setting_gross_weight'),
            'us_column_view' => new Twig_Filter_Method($this, 'us_column_view'),
            'us_exports_column_view' => new Twig_Filter_Method($this, 'us_exports_column_view'),

            'us_column_style' => new Twig_Filter_Method($this, 'us_column_style'),
            'us_exports_column_style' => new Twig_Filter_Method($this, 'us_exports_column_style'),
            'us_column_tooltip' => new Twig_Filter_Method($this, 'us_column_tooltip'),
            'us_column_code_transportation' => new Twig_Filter_Method($this, 'us_column_code_transportation'),

            'ar_column_view' => new Twig_Filter_Method($this, 'ar_column_view'),
            'ar_imports_column_style' => new Twig_Filter_Method($this, 'ar_imports_column_style'),
            'ar_imports_column_glossary' => new Twig_Filter_Method($this, 'ar_imports_column_glossary'),

            'ar_exports_column_view' => new Twig_Filter_Method($this, 'ar_exports_column_view'),
            'ar_exports_column_style' => new Twig_Filter_Method($this, 'ar_exports_column_style'),

            'br_column_view' => new Twig_Filter_Method($this, 'br_column_view'),
            'br_column_style' => new Twig_Filter_Method($this, 'br_column_style'),

            'us_column_get' => new Twig_Filter_Method($this, 'us_column_get'),

            'ca_exports_column_view' => new Twig_Filter_Method($this, 'ca_exports_column_view'),
            'ca_exports_column_style' => new Twig_Filter_Method($this, 'ca_exports_column_style'),

            'ca_imports_column_view' => new Twig_Filter_Method($this, 'ca_imports_column_view'),
            'ca_imports_column_style' => new Twig_Filter_Method($this, 'ca_imports_column_style'),

            'cl_exports_column_view' => new Twig_Filter_Method($this, 'cl_exports_column_view'),
            'cl_exports_column_style' => new Twig_Filter_Method($this, 'cl_exports_column_style'),

            'cl_imports_column_view' => new Twig_Filter_Method($this, 'cl_imports_column_view'),
            'cl_imports_column_style' => new Twig_Filter_Method($this, 'cl_imports_column_style'),

            'co_exports_column_view' => new Twig_Filter_Method($this, 'co_exports_column_view'),
            'co_exports_column_style' => new Twig_Filter_Method($this, 'co_exports_column_style'),

            'co_imports_column_view' => new Twig_Filter_Method($this, 'co_imports_column_view'),
            'co_imports_column_style' => new Twig_Filter_Method($this, 'co_imports_column_style'),

            'cr_exports_column_view' => new Twig_Filter_Method($this, 'cr_exports_column_view'),
            'cr_exports_column_style' => new Twig_Filter_Method($this, 'cr_exports_column_style'),

            'cr_imports_column_view' => new Twig_Filter_Method($this, 'cr_imports_column_view'),
            'cr_imports_column_style' => new Twig_Filter_Method($this, 'cr_imports_column_style'),

            'ec_exports_column_view' => new Twig_Filter_Method($this, 'ec_exports_column_view'),
            'ec_exports_column_style' => new Twig_Filter_Method($this, 'ec_exports_column_style'),

            'ec_imports_column_view' => new Twig_Filter_Method($this, 'ec_imports_column_view'),
            'ec_imports_column_style' => new Twig_Filter_Method($this, 'ec_imports_column_style'),

            'jnpt_imports_column_view' => new Twig_Filter_Method($this, 'jnpt_imports_column_view'),
            'jnpt_imports_column_style' => new Twig_Filter_Method($this, 'jnpt_imports_column_style'),
            'jnpt_imports_column_glossary' => new Twig_Filter_Method($this, 'jnpt_imports_column_glossary'),

            'mx_imports_column_view' => new Twig_Filter_Method($this, 'mx_imports_column_view'),
            'mx_imports_column_style' => new Twig_Filter_Method($this, 'mx_imports_column_style'),

            'pa_exports_column_view' => new Twig_Filter_Method($this, 'pa_exports_column_view'),
            'pa_exports_column_style' => new Twig_Filter_Method($this, 'pa_exports_column_style'),

            'pa_imports_column_view' => new Twig_Filter_Method($this, 'pa_imports_column_view'),
            'pa_imports_column_style' => new Twig_Filter_Method($this, 'pa_imports_column_style'),

            'pe_exports_column_view' => new Twig_Filter_Method($this, 'pe_exports_column_view'),
            'pe_exports_column_style' => new Twig_Filter_Method($this, 'pe_exports_column_style'),

            'pe_imports_column_view' => new Twig_Filter_Method($this, 'pe_imports_column_view'),
            'pe_imports_column_style' => new Twig_Filter_Method($this, 'pe_imports_column_style'),

            'py_exports_column_view' => new Twig_Filter_Method($this, 'py_exports_column_view'),
            'py_exports_column_style' => new Twig_Filter_Method($this, 'py_exports_column_style'),

            'py_imports_column_view' => new Twig_Filter_Method($this, 'py_imports_column_view'),
            'py_imports_column_style' => new Twig_Filter_Method($this, 'py_imports_column_style'),

            'uy_exports_column_view' => new Twig_Filter_Method($this, 'uy_exports_column_view'),
            'uy_exports_column_style' => new Twig_Filter_Method($this, 'uy_exports_column_style'),

            'uy_imports_column_view' => new Twig_Filter_Method($this, 'uy_imports_column_view'),
            'uy_imports_column_style' => new Twig_Filter_Method($this, 'uy_imports_column_style'),

            've_exports_column_view' => new Twig_Filter_Method($this, 've_exports_column_view'),
            've_exports_column_style' => new Twig_Filter_Method($this, 've_exports_column_style'),

            've_imports_column_view' => new Twig_Filter_Method($this, 've_imports_column_view'),
            've_imports_column_style' => new Twig_Filter_Method($this, 've_imports_column_style'),

            'decrypted_query' => new Twig_Filter_Method($this, 'decrypted'),
            'twolettercodecountry' => new Twig_Filter_Method($this, 'twolettercodecountry'),
            'descProduct' => new Twig_Filter_Method($this, 'descProduct'),

            'serializetwig' => new Twig_Filter_Method($this, 'serializetwig'),
            'globalfield' => new Twig_Filter_Method($this, 'globalfield'),

            'na' => new Twig_Filter_Method($this, 'na'),
            'rmascii' => new Twig_Filter_Method($this, 'rmascii'),
            );
}

public function na($na)
{
    if(strtoupper($na) == 'N/A' or strtoupper($na) == 'NA')
        return "Not Available";

    return $this->rmascii($na);

}

public function rmascii($na)
{

    return preg_replace('/[[:^print:]]/',"",$na);

}

public function twolettercodecountry($country)
{
    $data = new StringExtension();

    return $data->dataCountry()[strtoupper($country)];

}

public function serializetwig($string, $toArray = true)
{

    if ($toArray)
        return serialize($string);
    else
        return unserialize($string);
}

public function globalfield($string, $country)
{
    $data = array(
        'hs_code' => 'HS Code',
        'shipper_name_pr' => 'Suppliers Name',
        'consignee_name_pr' => 'Buyers Name',
        'product_desc' => 'Product',
        'shipper_address_pr' => 'Suppliers Address',
        'consignee_address_pr' => 'Buyers Address',

        );

    if ($string == 'all')
        return 'All';
    if ($country == 'us_imports')
        return $this->us_column_view($string);
    if ($country == 'us_exports')
        return $this->us_exports_column_view($string);
    if ($country == 'ar_imports')
        return $this->ar_column_view($string);
    if ($country == 'ar_exports')
        return $this->ar_exports_column_view($string);
    if ($country == 'br_imports' or $country == 'br_exports')
        return $this->br_column_view($string);
    if ($country == 'ca_imports')
        return $this->ca_imports_column_view($string);
    if ($country == 'ca_exports')
        return $this->ca_exports_column_view($string);
    if ($country == 'cl_exports')
        return $this->cl_exports_column_view($string);
    if ($country == 'co_exports')
        return $this->co_exports_column_view($string);
    if ($country == 'cr_exports')
        return $this->cr_exports_column_view($string);
    if ($country == 'ec_exports')
        return $this->ec_exports_column_view($string);
    if ($country == 'mx_exports' or $country == 'mx_imports')
        return $this->mx_imports_column_view($string);
    if ($country == 'pa_exports')
        return $this->pa_exports_column_view($string);
    if ($country == 'pe_exports')
        return $this->pe_exports_column_view($string);
    if ($country == 'py_exports')
        return $this->py_exports_column_view($string);
    if ($country == 'uy_exports')
        return $this->uy_exports_column_view($string);
    if ($country == 've_exports')
        return $this->ve_exports_column_view($string);

    if ($country == 'cl_imports')
        return $this->cl_imports_column_view($string);
    if ($country == 'co_imports')
        return $this->co_imports_column_view($string);
    if ($country == 'cr_imports')
        return $this->cr_imports_column_view($string);
    if ($country == 'ec_imports')
        return $this->ec_imports_column_view($string);
    if ($country == 'jnpt_imports')
        return $this->jnpt_imports_column_view($string);
    if ($country == 'pa_imports')
        return $this->pa_imports_column_view($string);
    if ($country == 'pe_imports')
        return $this->pe_imports_column_view($string);
    if ($country == 'py_imports')
        return $this->py_imports_column_view($string);
    if ($country == 'uy_imports')
        return $this->uy_imports_column_view($string);
    if ($country == 've_imports')
        return $this->ve_imports_column_view($string);

    return $data[$string];
}

function us_column_view($string)
{
    $data = array(
        'number' => 'Number',
        'detail_company' => 'View',
        'actual_arrival_date' => 'Arrival Date',
        'consignee_name' => 'Consignee',
        'shipper_name' => 'Shipper',
        'quantity' => 'Quantity',
        'weight' => 'Weight (LB)',
        'product_desc' => 'Product Description',
        'marks_and_numbers' => 'Marks & Numbers',
        'estimate_arrival_date' => 'Estimate Arrival Date',
        'bill_of_lading' => 'Bill Of Lading #',
        'master_bill_of_lading' => 'Bill Of Lading #',
        'bill_type_code' => 'Bill Type Code',
        'carrier_sasc_code' => 'Carrier Code',
        'vessel_country_code' => 'Vessel Country Code',
        'vessel_code' => 'Vessel Code',
        'vessel_name' => 'Vessel Name',
        'voyage' => 'Voyage',
        'inbond_type' => 'Inbond Type',
        'manifest_no' => 'Manifest No',
        'mode_of_transportation' => 'Mode Of Transportation',
        'loading_port' => 'Foreign Port',
        // 'last_vist_foreign_port' => 'Foreign Port',
        'us_clearing_district' => 'US Clearing District',
        'unloading_port' => 'Unloading Port',
        'place_of_receipt' => 'Place Of Receipt',
        'country' => 'Country',
        'country_sure_level' => 'Country Sure Level',
        'weight_in_kg' => 'Weight In KG',
        'teu' => 'TEU',
        'weight_unit' => 'Weight Unit',
        'measure_in_cm' => 'Measure In CM',
        'quantity_unit' => 'Quantity Unit',
        'measure' => 'Measure In CM',
        'measure_unit' => 'Measure Unit',
        'container_id' => 'Container Number',
        'container_size' => 'Container Size',
        'container_type' => 'Container Type',
        'container_desc_code' => 'Container Desc Code',
        'container_load_status' => 'Container Load Status',
        'container_type_of_service' => 'Container Type Of Service',
        'shipper_address' => 'Shipper Address',
        'raw_shipper_name' => 'Raw Shipper Name',
        'raw_shipper_address_1' => 'Raw Shipper Address 1',
        'raw_shipper_address_2' => 'Raw Shipper Address 2',
        'raw_shipper_address_3' => 'Raw Shipper Address 3',
        'raw_shipper_address_4' => 'Raw Shipper Address 4',
        'raw_shipper_address_others' => 'Raw Shipper Address Others',
        'consignee_address' => 'Consignee Address',
        'raw_consignee_name' => 'Raw Consignee Name',
        'raw_consignee_address' => 'Raw Consignee Address',
        'raw_consignee_address_1' => 'Raw Consignee Address 1',
        'raw_consignee_address_2' => 'Raw Consignee Address 2',
        'raw_consignee_address_3' => 'Raw Consignee Address 3',
        'raw_consignee_address_4' => 'Raw Consignee Address 4',
        'notify_party_name' => 'Nofity Party',
        'notify_party_address' => 'Nofity Party Address',
        'raw_notify_party_name' => 'Raw Nofity Party',
        'raw_notify_party_address_1' => 'Raw Nofity Party Address 1',
        'raw_notify_party_address_2' => 'Raw Nofity Party Address 2',
        'raw_notify_party_address_3' => 'Raw Nofity Party Address 3',
        'raw_notify_party_address_4' => 'Raw Nofity Party Address 4',
        'raw_notify_party_address_others' => 'Raw Nofity Party Address Others',
        'hs_code_sure_level' => 'HS Code Sure Level',
        'hs_code' => 'HS Code',
        'cif' => 'CIF',
        'indicator_of_true_supplier' => 'Indicator Of True Supplier',
        'end' => 'END',
        'company_as' => 'Company As',
        'shipper_name_pr' => 'Suppliers Name',
        'consignee_name_pr' => 'Buyers Name',
        'shipper_address_pr' => 'Suppliers Address',
        'consignee_address_pr' => 'Buyers Address',
        'marks_numbers' => 'Marks Numbers',
        'raw_shipper_addr1' => 'Raw Shipper Address 1',
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function us_exports_column_view($string)
{
    $data = array(
        'number' => 'Number',
        'detail_company' => 'View',
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
        "shipper_name"=>"Shipper",
        "shipper_full_address"=>"Shipper Address",
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
        "item_description"=>"Product Description"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function ar_column_view($string)
{
    $data = array(
        'number' => 'Number',
        'detail_company' => 'View',
        "date" => "Arrival Date",
        "import_id" => "Import ID",
        "operation_type" => "Operation Type",
        "custom" => "Customs",
        "consignee_name" => "Consignee",
        "importer_id" => "Importer ID",
        "adq_country" => "Product Purchase Country",
        "type_of_transport" => "Transporation Type",
        "embarq_port" => "Embarkation",
        "incoterms" => "Incoterms",
        "total_fob" => "FOB (USD)",
        "freight_us" => "Freight (USD)",
        "insurance_us" => "Insurance (USD)",
        "total_cif" => "Total CIF (USD)",
        "number_of_packages" => "Number Of Packages",
        "gross_weight" => "Gross Weight",
        "weight_unit" => "Weight Unit ",
        "item_number" => "Product ID",
        "orig_country" => "Country Of Origin",
        "commercial_quantity" => "Gross Weight (KG)",
        "commercial_unit" => "Commercial Unit",
        "fob_item" => " Brand FOB (USD)",
        "freight_item" => "Freight (USD)",
        "insurance_item" => "Insurance",
        "hs_code" => "HS Code",
        "product" => "Product Descriptiom",
        "cif_item" => "CIF (USD)",
        // "subitem_number" => "Subitem Number",
        "brand" => "Brand",
        "variety" => "Variety",
        "attributes" => "Attributes",
        "us_fob_subitem" => "FOB Subitem (USD)",
        "quantity_subitem" => "Brand Quantity"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function ar_exports_column_view($string)
{
    $data = array(
        'number' => 'Number',
        'detail_company' => 'View',
        "date" => "Date",
        "export_id" => "Export ID",
        "operation_type" => "Operation Type",
        "custom" => "Custom",
        "country_destination" => "Country Destination",
        "type_of_transport" => "Type Of Transport",
        "incoterms" => "Incoterms",
        "total_fob" => "Total FOB",
        "total_cif" => "Total CIF",
        "number_of_packages" => "Number Of Packages",
        "gross_weight" => "Gross Weight",
        "weight_unit" => "Weight unit",
        "item_number" => "Item Number",
        "commercial_quantity" => "Commercial Quantity",
        "commercial_unit" => "Commercial Unit",
        "fob_item" => "FOB Item",
        "hs_code" => "HS Code",
        "product" => "Product",
        "cif_item" => "CIF Item",
        "subitem_number" => "Subitem Number",
        "brand" => "Brand",
        "variety" => "Variety",
        "insurance_us" => "Insurance US",
        "attributes" => "Attributes",
        "us_fob_subitem" => "US FOB Subitem",
        "quantity_subitem" => "Quantity Subitem"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function br_column_view($string)
{
    $data = array(
        "date" => "Date",
        "customs" => "Customs",
        "via" => "VIA",
        "country" => "Country",
        "nomen" => "Nomen",
        "product" => "Product",
        "fob" => "FOB",
        "quantity" => "Quantity",
        "measure" => "Measure",
        "net" => "Net"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function ca_exports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "hs_code" => "HS Code",
        "spanish_description" => "Spanish Description",
        "province" => "Province",
        "tipes" => "Tipes",
        "destiny_country" => "Destiny Country",
        "destiny_state" => "Destiny State",
        "quantity" => "Quantity",
        "comercial_unit" => "Comercial Unit",
        "export_fob_value" => "Export FOB Value",
        "port_of_departure" => "Port Of Departure",
        "hs_code_description" => "HS Code Description",
        "export_type" => "Export Type",
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function ca_imports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "hs_code" => "HS Code",
        "spanish_description" => "Spanish Description",
        "province" => "Province",
        "tipes" => "Tipes",
        "origin_country" => "Origin Country",
        "state_of_origin" => "State Of Origin",
        "quantity" => "Quantity",
        "comercial_unit" => "Comercial Unit",
        "import_fob_value" => "Import FOB Value",
        "port_of_entry" => "Port Of Entry",
        "hs_code_description" => "HS Code Description",
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function cl_exports_column_view($string)
{
    $data = array(
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
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function cl_imports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "custom" => "Custom",
        "import_id" => "Import ID",
        "importer_id" => "Importer ID",
        "importer_id_check_digit" => "Importer ID Check Digit",
        "importer" => "Consignee",
        "hs_code" => "HS Code",
        "hs_code_description" => "HS Code Description",
        "product" => "Product",
        "brand" => "Brand",
        "variety" => "Variety",
        "description" => "Description",
        "description_2" => "Description 2",
        "description_3" => "Description 3",
        "description_4" => "Description 4",
        "origin_country" => "Country of Origin",
        "purchase_country" => "Purchase Country",
        "transport_type" => "Transport Type",
        "type_of_payment" => "Type Of Payment",
        "origin_port" => "Origin Port",
        "landing_port" => "Landing Port",
        "transport_company" => "Transport Company",
        "load_type" => "Load Type",
        "package_type" => "Package Type",
        "gross_weight" => "Gross Weight",
        "incoterm" => "Incoterm",
        "tax" => "Tax",
        "quantity" => "Quantity",
        "measure_unit" => "Measure Unit",
        "us_fob" => "US FOB",
        "us_freight" => "US Freight",
        "us_insurance" => "US Insurance",
        "us_cif" => "US CIF",
        "us_cif_unit" => "US CIF Unit",
        "us_fob_unit" => "US FOB Unit",
        "transport_company_country" => "Transport Company Country",
        "us_tax" => "US Tax",
        "packages_quantity" => "Packages Quantity",
        "economic_zone" => "Economic Zone",
        "importer_economic_key" => "Importer Economic Key",
        "manifest_number" => "Manifest Number",
        "manifest_date" => "Manifest Date",
        "transport_document_number" => "Transport Document Number",
        "transport_document_date" => "Transport Document Date",
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function co_exports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "control_id" => "Control ID",
        "custom" => "Custom",
        "exporter_id" => "Exporter ID",
        "exporter" => "Exporter",
        "exporter_address" => "Exporter Address",
        "department_destination" => "Departement Destination",
        "hs_code" => "HS Code",
        "country_destination" => "Country Destination",
        "type_of_transport" => "Type Of Transport",
        "method_of_payment" => "Method Of Payment",
        "weight" => "Weight",
        "quantity" => "Quantity",
        "unit_of_measure" => "Unit Of Measure",
        "us_fob" => "US FOB",
        "us_freight" => "US Freight",
        "us_insurance" => "US Insurance",
        "importer" => "Importer",
        "importer_address" => "Importer Address"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function co_imports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "custom" => "Custom",
        "control_id" => "Control ID",
        "importer_id" => "Importer ID",
        "importer" => "Importer",
        "importer_address" => "Importer Address",
        "importer_phone" => "Importer Phone",
        "department_destination" => "Department Destination",
        "hs_code" => "HS Code",
        "product" => "Product",
        "country_of_origin" => "Country Of Origin",
        "country_of_acquisition" => "Country Of Acquisition",
        "type_of_transport" => "Type Of Transport",
        "method_of_payment" => "Method Of Payment",
        "transportation_company" => "Transportation Company",
        "weight" => "Weight",
        "tax" => "Tax",
        "exporter" => "Exporter",
        "exporter_address" => "Exporter Address",
        "exporter_city" => "Exporter City",
        "exporter_country" => "Exporter Country",
        "exporter_phone_email" => "Exporter Phone Email",
        "quantity" => "Quantity",
        "unit_of_measure" => "Unit Of Measure",
        "us_fob" => "US FOB",
        "us_freight" => "US Freight",
        "us_insurance" => "US Insurance",
        "us_cif" => "US CIF"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}


function cr_exports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "declaration" => "Declaration",
        "customs" => "Customs",
        "shipping_number" => "Shipping Number",
        "tipe" => "Tipe",
        "exporter_id" => "Exporter ID",
        "exporter" => "Exporter",
        "exporter_address" => "Exporter Address",
        "total_invoice" => "Total Invoice",
        "total_cif" => "Total CIF",
        "total_gross_weight" => "Total Gross Weight",
        "total_net_weight" => "Total Net Weight",
        "transport_type" => "Transport Type",
        "exchange_rate" => "Exchange Rate",
        "remarks" => "Remarks",
        "serial_number" => "Serial Number",
        "hs_code" => "HS Code",
        "cargo_description" => "Product Description",
        "packages" => "Packages",
        "packages_type" => "Packages Type",
        "brand" => "Brand",
        "cif_usd" => "CIF USD",
        "gross_weight" => "Gross Weight",
        "net_weight" => "Net Weight"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function cr_imports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "declaration" => "Declaration",
        "customs" => "Customs",
        "shipping_number" => "Shipping Number",
        "tipe" => "Tipe",
        "importer_id" => "Consignee ID",
        "importer" => "Consignee",
        "importer_address" => "Consignee Address",
        "total_invoice" => "Total Invoice",
        "total_cif" => "Total CIF",
        "total_gross_weight" => "Total Gross Weight",
        "total_net_weight" => "Total Net Weight",
        "transport_type" => "Transport Type",
        "exchange_rate" => "Exchange Rate",
        "remarks" => "Remarks",
        "serial_number" => "Serial Number",
        "hs_code" => "HS Code",
        "cargo_description" => "Product Description",
        "packages" => "Packages",
        "packages_type" => "Packages Type",
        "brand" => "Brand",
        "cif_usd" => "CIF USD",
        "gross_weight" => "Gross Weight",
        "net_weight" => "Net Weight"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function ec_exports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "export_type" => "Export Type",
        "refrendo" => "Refrendo",
        "declaration_number_dau" => "Declaration Number Dau",
        "item_number" => "Item Number",
        "exporter_id" => "Exporter ID",
        "exporter" => "Exporter",
        "destination_country" => "Destiantion Country",
        "loading_port" => "Loading Port",
        "transport_type" => "Transport Type",
        "customs" => "Customs",
        "hs_code" => "HS Code",
        "hs_code_description" => "HS Code Description",
        "comercial_description" => "Commercial Description",
        "condition" => "Condition",
        "container" => "Container",
        "packages" => "Packages",
        "quantity" => "Quantity",
        "unit_of_meassure" => "Unit Of Measure",
        "us_fob" => "US FOB",
        "consignee" => "Consignee",
        "customs_agent" => "Custom Agent",
        "customs_agency" => "Customs Agency",
        "shipping_agency" => "Shipping Agency",
        "ship" => "Ship",
        "bl_number" => "Bl Number",
        "conocimiento_emb" => "Conocimento Emb",
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function ec_imports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "registration_date" => "Registration Date",
        "import_type" => "Import Type",
        "refrendo" => "Refrendo",
        "declaration_number_dau" => "Declaration Number Dau",
        "item_number" => "Item Number",
        "importer_id" => "Consignee ID",
        "importer" => "Consignee",
        "origin_country" => "Country Of Origin",
        "procedence_country" => "Procendence Country",
        "loading_country" => "Loading Country",
        "loading_port" => "Loading Port",
        "transport_type" => "Transport Type",
        "customs" => "Customs",
        "hs_code" => "HS Code",
        "hs_code_description" => "HS Code Description",
        "comercial_description" => "Product Description",
        "brand" => "Brand",
        "condition" => "Condition",
        "container" => "Container",
        "packages" => "Packages",
        "quantity" => "Quantity",
        "unit_of_meassure" => "Unit Of Meassure",
        "advalorem_rate" => "Advalorem Rate",
        "us_fob" => "US FOB",
        "us_freight" => "US Freight",
        "us_insurance" => "US Insurance",
        "us_cif" => "US CIF",
        "shipper" => "Shipper",
        "customs_agent" => "Customs Agent",
        "customs_agency" => "Customs Agency",
        "shipping_agency" => "Shipping Agency",
        "ship" => "Ship",
        "bl_number" => "Bl Number",
        "conocimiento_emb" => "Conocimiento Emb",
        "commercial_deposit" => "Commercial Deposit"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function jnpt_imports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "hs_code" => "HS Code",
        "importer_id" => "Consignee ID",
        "importer_name" => "Consignee",
        "importer_address" => "Consignee Address",
        "importer_address_2" => "Consignee Address 2",
        "city" => "City",
        "zip_cod" => "Zip Code",
        "phone" => "Phone",
        "fax" => "FAX",
        "e_mail" => "Email",
        "item_description" => "Product",
        "quantity" => "Quantity",
        "unit" => "Unit",
        "unit_price_invoice" => "Unit Price Invoice",
        "unit_price_usd" => "Unit Price USD",
        "unit_price_euro" => "Unit Price EURO",
        "custom_agent" => "Customs Agent",
        "supplier_name" => "Supplier Name",
        "supplier_address" => "Supplier Address",
        "supplier_country" => "Supplier Country",
        "origin_port" => "Port Of Origin",
        "contact_1" => "Contact 1",
        "contact_2" => "Contact 2",
        "bank" => "Bank",
        "be_no" => "Be No."
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function mx_imports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "hs_code" => "HS Code",
        "hs_product_dectiption" => "HS Product Description",
        "product_schedule_b_code" => "Product Schedule B Code",
        "product_decription_by_schedule_b_code_mexico" => "Product Description By Schedule B Code Mexico",
        "way_of_transport" => "Way Of Transport",
        "country_of_origin" => "Country Of Origin",
        "custom" => "Custom",
        "total_fob_value" => "Total FOB Value",
        "total_quantity_1" => "Total Quantity 1",
        "measure_unit_1" => "Measure Unit 1",
        "fob_value_per_unit" => "FOB Value Per Unit",
        "total_gross_weight_kg" => "Total Gross Weight Kg",
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function pa_exports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "ruc_exporter_id" => "Ruc Exporter ID",
        "exporter" => "Exporter",
        "hscode" => "HS Code",
        "product_description" => "Product Description",
        "customs_zone" => "Customs Zone",
        "customs_name" => "Customs Name",
        "transport_type" => "Transport Type",
        "declaration_number" => "Declaration Number",
        "destiny_country" => "Desctiny Country",
        "net_weight" => "Net Weight",
        "gross_weight" => "Gross Weight",
        "packages" => "Packages",
        "quantity" => "Quantity",
        "measure_unit" => "Measure",
        "us_fob" => "US FOB"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function pa_imports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "ruc_importer_id" => "Ruc Importer ID",
        "importer" => "Consignee",
        "hscode" => "HS Code",
        "product_description" => "Product",
        "customs_zone" => "Customs Zone",
        "customs_name" => "Customs Name",
        "transport_type" => "Transport Type",
        "declaration_number" => "Declaration Number",
        "origin_country" => "Country Of Origin",
        "net_weight" => "Net Weight",
        "gross_weight" => "Gross Weight",
        "packages" => "Packages",
        "quantity" => "Quantity",
        "measure_unit" => "Measure Unit",
        "us_fob" => "US FOB",
        "us_cif" => "US CIF",
        "us_freight" => "US Freight",
        "us_insurance" => "US Insurance"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function pe_exports_column_view($string)
{
    $data = array(
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
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function pe_imports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "dua_id" => "Dua ID",
        "serie" => "Serie",
        "custom" => "Custom",
        "ruc_importer_id" => "Ruc Consignee ID",
        "importer" => "Consignee",
        "importer_address" => "Consignee Address",
        "importer_department" => "Consignee Departement",
        "importer_state" => "Consignee State",
        "importer_district" => "Consignee District",
        "importer_phone" => "Consignee Phone",
        "importer_fax" => "Consignee Fax",
        "transport_type" => "Transport Type",
        "bank" => "Bank",
        "origin_country" => "Country of Origin",
        "acquisition_country" => "Acquisjtion Country",
        "shipping_port" => "Shipping Port",
        "hs_code" => "HS Code",
        "hs_code_description" => "HS Code Description",
        "cargo_description" => "Product",
        "cargo_description_1" => "Product Desctription 1",
        "cargo_description_2" => "Product Desctription 2",
        "cargo_description_3" => "Product Desctription 3",
        "cargo_description_4" => "Product Desctription 4",
        "manufacture_year" => "Manufacture Year",
        "us_fob" => "US FOB",
        "us_freight" => "US Freight",
        "us_insurance" => "US Insurance",
        "us_cif" => "US CIF",
        "advalorem" => "Advalorem",
        "local_tax" => "Local Tax",
        "net_weight" => "Net Weight",
        "gross_weight" => "Gross Weight",
        "quantity" => "Quantity",
        "measure_unit" => "Measure Unit",
        "us_cif_unit" => "US CIF Unit",
        "commercial_quantity" => "Commercial Quantity",
        "commercial_measure_unit" => "Commercial Measure Unit",
        "package_type" => "Package type",
        "packages_quantity" => "Packages Quantity",
        "product_status" => "Product Status",
        "shipper_name" => "Shipper",
        "customs_agent" => "Customs Agent",
        "transport_company" => "Transport Company",
        "incoterm" => "Incoterm",
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function py_exports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "exporter_id" => "Exporter ID",
        "exporter" => "Exporter",
        "consignee" => "Consignee",
        "destiny_country" => "Destiny Country",
        "hs_code" => "HS Code",
        "quantity" => "Quantity",
        "measure_unit" => "Measure Unit",
        "product" => "Product",
        "gross_kilo" => "Gross Kilo",
        "net_kilo" => "Net Kilo",
        "us_fob" => "US FOB",
        "us_freight" => "US Freight",
        "us_insurance" => "US Insurance",
        "us_cif" => "US CIF",
        "us_fob_unit" => "US FOB Unit",
        "brand" => "Brand",
        "custom" => "Custom",
        "transport_type" => "Transport Type",
        "transport_company" => "Transport Company",
        "transportist_country" => "Transportist Country",
        "manifest" => "Manifest"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function py_imports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "importer_id" => "Consignee ID",
        "importer" => "Consignee",
        "supplier" => "Supplier",
        "origin_country" => "Country of Origin",
        "hs_code" => "HS Code",
        "quantity" => "Quantity",
        "measure_unit" => "Measure Unit",
        "product" => "Product",
        "brand" => "Brand",
        "gross_kilo" => "Gross Kilo",
        "net_kilo" => "Net Kilo",
        "us_fob" => "US FOB",
        "us_freight" => "US Freight",
        "us_insurance" => "US Insurance",
        "us_cif" => "US CIF",
        "us_fob_unit" => "US FOB Unit",
        "custom" => "Custom",
        "transport_type" => "Transport Type",
        "transport_company" => "Transport Company",
        "transportist_country" => "Transportist Country",
        "acquisition_country" => "Acquisition Country",
        "manifest" => "Manifest",
        "bl_number" => "BL Number",
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function uy_exports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "custom" => "Custom",
        "exporter" => "Exporter",
        "exporter_id" => "Exporter ID",
        "hs_code" => "HS CODE",
        "product" => "Product",
        "country_destination" => "Country Destination",
        "type_of_transport" => "Type Of Transport",
        "transportation_company" => "Transportation Company",
        "gross_weight" => "Gross Weight",
        "net_weight" => "Net Weight",
        "quantity" => "Quantity",
        "unit" => "Unit",
        "physical_units_quantity" => "Physical Units Quantity",
        "physical_units" => "Physical Units",
        "us_fob" => "US FOB"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function uy_imports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "custom" => "Custom",
        "importer" => "Consignee",
        "importer_id" => "Consignee ID",
        "hs_code" => "HS CODE",
        "product" => "Product",
        "country_of_origin" => "Country Of Origin",
        "country_of_acquisition" => "Country of Acquisition",
        "type_of_transport" => "Type Of Transport",
        "transportation_company" => "Transportation Company",
        "gross_weight" => "Gross Weight",
        "net_weight" => "Net Weight",
        "tax" => "Tax",
        "invalid_field" => "Invalid Field",
        "quantity" => "Quantity",
        "unit" => "Unit",
        "physical_units_quantity" => "Physical Units Quantity",
        "physical_units" => "Physical Units",
        "insurance_currency_of_origin" => "Insurance Currency Of Origin",
        "currency_of_insurance" => "Currency Of Insurance",
        "freight_currency_of_origin" => "Freight Currency Of Origin",
        "currency_of_freight" => "Currency Of Freight",
        "us_cif" => "US CIF"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}


function ve_exports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "record" => "Record",
        "bl" => "BL",
        "chapter" => "Chapter",
        "chapter_description" => "Chapter Description",
        "hs_code" => "HS Code",
        "payment" => "Payment",
        "hs_code_description" => "HS Code Description",
        "exporter" => "Exporter",
        "custom" => "Custom",
        "transport_type" => "Transport Type",
        "dest_country" => "Dest Country",
        "dest_port" => "Dest Port",
        "gross_weight" => "Gross Weight",
        "net_weight" => "Net Weight",
        "us_fob_bolivare" => "US FOB Bolivare",
        "us_fob" => "US FOB"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function ve_imports_column_view($string)
{
    $data = array(
        "date" => "Date",
        "record" => "Record",
        "bl" => "BL",
        "chapter" => "Chapter",
        "chapter_description" => "Chapter Description",
        "hs_code" => "HS Code",
        "hs_code_description" => "HS Code Description",
        "importer" => "Importer",
        "custom" => "Custom",
        "transport_type" => "Transport Type",
        "embarq_port" => "Embarq Port",
        "origin_country" => "Origin Country",
        "adq_country" => "Product Purchase Country",
        "gross_weight" => "Gross Weight",
        "net_weight" => "Net Weight",
        "us_fob_bolivares" => "US FOB Bolivares",
        "us_fob" => "US FOB",
        "us_cif_bolivares" => "US CIF Bolivares",
        "us_cif" => "US CIF"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function us_column_get($res, $string, $user_id, $is_global = false)
{
    $data = array_merge(array(
        'actual_arrival_date' => $res->getActualArrivalDate()->format("Y-m-d"),
        'consignee_name' => $res->getConsigneeName(),
        'shipper_name' => $res->getShipperName(),
        'quantity' => $this->ribuan($res->getQuantity()),
        'weight' => $this->setting_gross_weight($res->getWeight(),$res->getWeightUnit(), $user_id),
        'product_desc' => $res->getProductDesc(),
        'marks_and_numbers' => $res->getMarksAndNumbers(),
        'estimate_arrival_date' => $res->getEstimateArrivalDate()->format("Y-m-d"),
        'bill_of_lading' => $res->getBillOfLading(),
        'master_bill_of_lading' => $res->getMasterBillOfLading(),
        'bill_type_code' => $res->getBillTypeCode(),
        'carrier_sasc_code' => $res->getCarrierSASCCode(),
        'vessel_country_code' => $res->getVesselCountryCode(),
        'vessel_code' => $res->getVesselCode(),
        'vessel_name' => $res->getVesselName(),
        'voyage' => $res->getVoyage(),
        'inbond_type' => $res->getInbondType(),
        'manifest_no' => $res->getManifestNo(),
        'mode_of_transportation' => $res->getModeOfTransportation(),
        'loading_port' => $res->getLoadingPort(),
        'last_vist_foreign_port' => $res->getLastVistForeignPort(),
        'us_clearing_district' => $res->getUsClearingDistrict(),
        'unloading_port' => $res->getUnloadingPort(),
        'place_of_receipt' => $res->getPlaceOfReceipt(),
        'country' => $res->getCountry(),
        'country_sure_level' => $res->getCountrySureLevel(),
        'weight_in_kg' => $res->getWeightInKg(),
        'teu' => $res->getTeu(),
        'weight_unit' => $res->getWeightUnit(),
        'measure_in_cm' => $res->getMeasureInCm(),
        'quantity_unit' => $res->getQuantityUnit(),
        'measure' => $res->getMeasure(),
        'measure_unit' => $res->getMeasureUnit(),
        'container_id' => $res->getContainerId(),
        'container_size' => $res->getContainerSize(),
        'container_type' => $res->getContainerType(),
        'container_desc_code' => $res->getContainerDescCode(),
        'container_load_status' => $res->getContainerLoadStatus(),
        'container_type_of_service' => $res->getContainerTypeOfService(),
        'shipper_address' => $res->getShipperAddress(),
        'raw_shipper_name' => $res->getRawShipperName(),
        'raw_shipper_address_1' => $res->getRawShipperAddr1(),
        'raw_shipper_address_2' => $res->getRawShipperAddr2(),
        'raw_shipper_address_3' => $res->getRawShipperAddr3(),
        'raw_shipper_address_4' => $res->getRawShipperAddr4(),
        'raw_shipper_address_others' => $res->getRawShipperAddrOthers(),
        'consignee_address' => $res->getConsigneeAddress(),
        'raw_consignee_name' => $res->getRawConsigneeName(),
//            'raw_consignee_address' => $res->getRawConsigneeAddr(),
        'raw_consignee_address_1' => $res->getRawConsigneeAddr1(),
        'raw_consignee_address_2' => $res->getRawConsigneeAddr2(),
        'raw_consignee_address_3' => $res->getRawConsigneeAddr3(),
        'raw_consignee_address_4' => $res->getRawConsigneeAddr4(),
        'notify_party_name' => $res->getNotifyPartyName(),
        'raw_notify_party_name' => $res->getRawNotifyPartyName(),
        'raw_notify_party_address_1' => $res->getRawNotifyPartyAddr1(),
        'raw_notify_party_address_2' => $res->getRawNotifyPartyAddr2(),
        'raw_notify_party_address_3' => $res->getRawNotifyPartyAddr3(),
        'raw_notify_party_address_4' => $res->getRawNotifyPartyAddr4(),
        'raw_notify_party_address_others' => $res->getRawNotifyPartyAddrOthers(),
        'hs_code_sure_level' => $res->getHsCodeSureLevel(),
        'hs_code' => $res->getHsCode(),
        'cif' => $res->getCif(),
        'indicator_of_true_supplier' => $res->getIndicatorOfTrueSupplier(),
        'end' => $res->getEnd(),

        ),
    ($is_global) ? array(
        'company_as' => $res->getCompanyAs(),
        'consignee_address_pr' => $res->getConsigneeAddressPr(),
        'consignee_name_pr' => $res->getConsigneeNamePr(),
        'shipper_address_pr' => $res->getShipperAddressPr(),
        'shipper_name_pr' => $res->getShipperNamePr(),
        ) : array()
    );

return $data[$string];
}

function us_column_style($string)
{
    $data = array(
        'number' => '50',
        'detail_company' => '50',
        'actual_arrival_date' => '80',
        'consignee_name' => '300',
        'shipper_name' => '300',
        'quantity' => '100',
        'weight' => '100',
        'product_desc' => '300',
        'marks_and_numbers' => '300',
        'estimate_arrival_date' => '300',
        'bill_of_lading' => '100',
        'master_bill_of_lading' => '100',
        'bill_type_code' => '100',
        'carrier_sasc_code' => '100',
        'vessel_country_code' => '100',
        'vessel_code' => '100',
        'vessel_name' => '100',
        'voyage' => '100',
        'inbond_type' => '100',
        'manifest_no' => '100',
        'mode_of_transportation' => '200',
        'loading_port' => '100',
        'last_vist_foreign_port' => '200',
        'us_clearing_district' => '100',
        'unloading_port' => '100',
        'place_of_receipt' => '100',
        'country' => '100',
        'country_sure_level' => '100',
        'weight_in_kg' => '100',
        'teu' => '50',
        'weight_unit' => '100',
        'measure_in_cm' => '100',
        'quantity_unit' => '120',
        'measure' => '80',
        'measure_unit' => '120',
        'container_id' => '300',
        'container_size' => '100',
        'container_type' => '100',
        'container_desc_code' => '100',
        'container_load_status' => '100',
        'container_type_of_service' => '100',
        'shipper_address' => '300',
        'raw_shipper_name' => '200',
        'raw_shipper_address_1' => '300',
        'raw_shipper_address_2' => '200',
        'raw_shipper_address_3' => '200',
        'raw_shipper_address_4' => '200',
        'raw_shipper_address_others' => '200',
        'consignee_address' => '300',
        'raw_consignee_name' => '200',
        'raw_consignee_address' => '300',
        'raw_consignee_address_1' => '200',
        'raw_consignee_address_2' => '200',
        'raw_consignee_address_3' => '200',
        'raw_consignee_address_4' => '200',
        'notify_party_name' => '300',
        'notify_party_address' => '300',
        'raw_notify_party_name' => '200',
        'raw_notify_party_address_1' => '200',
        'raw_notify_party_address_2' => '200',
        'raw_notify_party_address_3' => '200',
        'raw_notify_party_address_4' => '200',
        'raw_notify_party_address_others' => '200',
        'hs_code_sure_level' => '200',
        'hs_code' => '90',
        'cif' => '200',
        'indicator_of_true_supplier' => '100',
        'end' => '100',
        'marks_numbers' => '200',
        'raw_shipper_addr1' => '200',
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function ar_imports_column_style($string)
{
    $data = array(
        'number' => '50',
        'detail_company' => '50',
        "date" => "100",
        "import_id" => "100",
        "operation_type" => "100",
        "custom" => "169",
        "consignee_name" => "300",
        "importer_id" => "100",
        "adq_country" => "210",
        "type_of_transport" => "150",
        "embarq_port" => "100",
        "incoterms" => "100",
        "total_fob" => "130",
        "freight_us" => "130",
        "insurance_us" => "100",
        "total_cif" => "100",
        "number_of_packages" => "150",
        "gross_weight" => "130",
        "item_number" => "100",
        "orig_country" => "150",
        "commercial_quantity" => "150",
        "commercial_unit" => "127",
        "fob_item" => "100",
        "freight_item" => "100",
        "insurance_item" => "110",
        "hs_code" => "117",
        "product" => "500",
        "cif_item" => "100",
        "subitem_number" => "135",
        "brand" => "200",
        "variety" => "155",
        "attributes" => "120",
        "us_fob_subitem" => "150",
        "quantity_subitem" => "125",
        "weight_unit" => "100"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function us_exports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->us_exports_column_style($string);
    else
        return $data->us_exports_column_style($string);
}

function ar_imports_column_glossary($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->ar_imports_column_glossary($string);
    else
        return $data->ar_imports_column_glossary($string);
}

function ar_exports_column_style($string)
{
    $data = array(
        'number' => array('width' => 50,'align' => 'center'),
        'detail_company' => array('width' => 50,'align' => 'center'),
        "date" => array('width' => 100,'align' => 'center'),
        "export_id" => array('width' => 170,'align' => 'left'),
        "operation_type" => array('width' => 100,'align' => 'left'),
        "custom" => array('width' => 170,'align' => 'left'),
        "country_destination" => array('width' => 150,'align' => 'left'),
        "type_of_transport" => array('width' => 50,'align' => 'left'),
        "incoterms" => array('width' => 50,'align' => 'left'),
        "total_fob" => array('width' => 50,'align' => 'right'),
        "total_cif" => array('width' => 50,'align' => 'right'),
        "number_of_packages" => array('width' => 170,'align' => 'left'),
        "gross_weight" => array('width' => 120,'align' => 'right'),
        "weight_unit" => array('width' => 50,'align' => 'left'),
        "item_number" => array('width' => 170,'align' => 'left'),
        "commercial_quantity" => array('width' => 150,'align' => 'right'),
        "commercial_unit" => array('width' => 50,'align' => 'left'),
        "fob_item" => array('width' => 100,'align' => 'left'),
        "hs_code" => array('width' => 100,'align' => 'left'),
        "product" => array('width' => 500,'align' => 'left'),
        "cif_item" => array('width' => 100,'align' => 'left'),
        "subitem_number" => array('width' => 120,'align' => 'left'),
        "brand" => array('width' => 100,'align' => 'left'),
        "variety" => array('width' => 100,'align' => 'left'),
        "attributes" => array('width' => 120,'align' => 'left'),
        "us_fob_subitem" => array('width' => 120,'align' => 'left'),
        "quantity_subitem" => array('width' => 120,'align' => 'left'),
        "insurance_us" => array('width' => 120,'align' => 'left')
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function br_column_style($string)
{
    $data = array(
        "date" => "100",
        "customs" => "300",
        "via" => "200",
        "country" => "200",
        "nomen" => "120",
        "product" => "300",
        "fob" => "120",
        "quantity" => "120",
        "measure" => "250",
        "net" => "120"
        );

    if ($string == 'st_show_field')
        return $data;
    else
        return $data[$string];
}

function ca_exports_column_style($string)
{
    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->ca_exports_column_style($string);
    else
        return $data->ca_exports_column_style($string);
}

function ca_imports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->ca_imports_column_style($string);
    else
        return $data->ca_imports_column_style($string);
}

function cl_exports_column_style($string)
{
    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->cl_exports_column_style($string);
    else
        return $data->cl_exports_column_style($string);
}

function cl_imports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->cl_imports_column_style($string);
    else
        return $data->cl_imports_column_style($string);
}

function co_exports_column_style($string)
{
    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->co_exports_column_style($string);
    else
        return $data->co_exports_column_style($string);
}

function co_imports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->co_imports_column_style($string);
    else
        return $data->co_imports_column_style($string);
}

function cr_exports_column_style($string)
{
    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->cr_exports_column_style($string);
    else
        return $data->cr_exports_column_style($string);
}

function cr_imports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->cr_imports_column_style($string);
    else
        return $data->cr_imports_column_style($string);
}

function ec_exports_column_style($string)
{
    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->ec_exports_column_style($string);
    else
        return $data->ec_exports_column_style($string);
}

function ec_imports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->ec_imports_column_style($string);
    else
        return $data->ec_imports_column_style($string);
}

function jnpt_imports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->jnpt_imports_column_style($string);
    else
        return $data->jnpt_imports_column_style($string);
}

function jnpt_imports_column_glossary($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->jnpt_imports_column_glossary($string);
    else
        return $data->jnpt_imports_column_glossary($string);
}

function mx_imports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->mx_imports_column_style($string);
    else
        return $data->mx_imports_column_style($string);
}

function pa_exports_column_style($string)
{
    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->pa_exports_column_style($string);
    else
        return $data->pa_exports_column_style($string);
}

function pa_imports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->pa_imports_column_style($string);
    else
        return $data->pa_imports_column_style($string);
}

function pe_exports_column_style($string)
{
    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->pe_exports_column_style($string);
    else
        return $data->pe_exports_column_style($string);
}

function pe_imports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->pe_imports_column_style($string);
    else
        return $data->pe_imports_column_style($string);
}

function py_exports_column_style($string)
{
    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->py_exports_column_style($string);
    else
        return $data->py_exports_column_style($string);
}

function py_imports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->py_imports_column_style($string);
    else
        return $data->py_imports_column_style($string);
}

function uy_exports_column_style($string)
{
    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->uy_exports_column_style($string);
    else
        return $data->uy_exports_column_style($string);
}

function uy_imports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->uy_imports_column_style($string);
    else
        return $data->uy_imports_column_style($string);
}

function ve_exports_column_style($string)
{
    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->ve_exports_column_style($string);
    else
        return $data->ve_exports_column_style($string);
}

function ve_imports_column_style($string)
{

    $data = new NestedMethod();

    if ($string == 'st_show_field')
        return $data->ve_imports_column_style($string);
    else
        return $data->ve_imports_column_style($string);
}

function us_column_code_transportation($code){
    $data = array(
        "10" => "Vessel, Non-containerized",
        "11" => "Vessel, Containerized",
        "12" => "Barge",
        "20" => "Rail",
        "21" => "Rail, Containerized",
        "30" => "Truck",
        "31" => "Truck, Containerized",
        "32" => "Auto",
        "33" => "Pedestrian",
        "34" => "Road, Other",
        "40" => "Air",
        "41" => "Air, Containerized",
        "50" => "Mail",
        "60" => "Passenger, Hand-carried",
        "70" => "Fixed Transport Installations (Pipeline,Powerhouses"
        );

    if (isset($data[$code]))
        return $data[$code];
    else
        return "N/A";
}

function us_column_tooltip($string)
{
    $data = array(
        'last_vist_foreign_port' => 'The last port the shipments passes through before arriving in the U.S.',
        'country' => 'The country of the last foreign port the shipment passes through before arriving in the U.S.',

        );

    if (isset($data[$string]))
        return $data[$string];
    else
        return false;
}

public function rowXls($i)
{
    $data = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
        'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',);

    return $data[$i];
}

public function setting_gross_weight($weight, $weight_unit, $user_id, $lb = false)
{

    if($lb){        
       $weight = $weight * 0.453592;
   }else{
    $entity = $this->em->getRepository('JariffMemberBundle:MemberSetting')->findOneByMember($user_id);

    if ($entity) {
        if ($entity->getWeight() == 'LB') {
            if ($weight_unit != 'LB')
                $weight = $weight * 2.20462262;
        }elseif ($entity->getWeight() == 'KG') {
            if ($weight_unit == 'LB')
                $weight = $weight * 0.453592;
        }
        $weightOri = $entity->getWeight();
    } else {
        $weightOri = 'KG';
    }
}


return $this->ribuan($weight);
}

public function ribuan($money)
{
    return number_format($money, 0, ',', ',');
}

public function encrypted($string)
{
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->pass), $string, MCRYPT_MODE_CBC, md5(md5($this->pass))));
}

public function decrypted($string)
{
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->pass), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($this->pass))), "\0");
}

public function descProduct($string)
{
    $splitText = preg_split('/(<[^>]*[^\/]>)/i', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

    $splitTextFirstLen = strlen($splitText[0]);

    $textFirst = $splitText[0];
    $endText = isset($splitText[4]) ? $splitText[4] : ' ';
    $middleText = isset($splitText[3]) ? $splitText[3] : ' ';


    if ($splitTextFirstLen > 30 and $textFirst != '<strong>') {
        $textFirst = substr($splitText[0], $splitTextFirstLen - 30);
        $fullText = '...' . $textFirst . ' ' . $splitText[1] . $splitText[2] . "...";
    } else {
        $fullText = $this->readmore($textFirst . ' ' . $splitText[1] . $splitText[2] . $middleText . $endText);
    }

    return preg_replace('/\=/', '', $fullText);
}

public function readmore($readmore)
{
    $string = $readmore;

    if (strlen($string) > 30) {

            // truncate string
        $stringCut = substr($string, 0, 30);

            // make sure it ends in a word so assassinate doesn't become ass...
        $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
    }
    return $string;
}

public function getName()
{
    return 'jariff_string_two_extension';
}

}