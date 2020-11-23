<?php
/**
 * Created by PhpStorm.
 * User: fendithuk
 * Date: 3/4/16
 * Time: 7:54 PM
 */

namespace Jariff\ProjectBundle\Twig;


class NestedMethod
{

    function us_exports_column_style($string)
    {
        $data = array(
            'number' => array('width' => 50,'align' => 'center'),
            'detail_company' => array('width' => 50,'align' => 'center'),
            "source"=>array('width' => 50,'align' => 'center'),
            "shipment_id"=>array('width' => 50,'align' => 'center'),
            "record_date"=>array('width' => 50,'align' => 'center'),
            "departure_date"=>array('width' => 50,'align' => 'center'),
            "booking_location"=>array('width' => 150,'align' => 'left'),
            "us_port_id"=>array('width' => 50,'align' => 'center'),
            "us_port"=>array('width' => 200,'align' => 'left'),
            "destination_port_id"=>array('width' => 50,'align' => 'center'),
            "destination_port"=>array('width' => 200,'align' => 'left'),
            "destination"=>array('width' => 150,'align' => 'left'),
            "destination_country_iso3"=>array('width' => 100,'align' => 'center'),
            "bill_of_lading_number"=>array('width' => 150,'align' => 'left'),
            "manifest_scac"=>array('width' => 50,'align' => 'center'),
            "vessel_scac"=>array('width' => 50,'align' => 'center'),
            "vessel_name"=>array('width' => 150,'align' => 'left'),
            "vessel_imo"=>array('width' => 50,'align' => 'center'),
            "vessel_flag"=>array('width' => 50,'align' => 'center'),
            "flight_code"=>array('width' => 50,'align' => 'center'),
            "shipper_name"=>array('width' => 150,'align' => 'left'),
            "shipper_full_address"=>array('width' => 200,'align' => 'left'),
            "shipper_country_iso3"=>array('width' => 50,'align' => 'center'),
            "container_id"=>array('width' => 50,'align' => 'center'),
            "container_number"=>array('width' => 150,'align' => 'left'),
            "container_quantity"=>array('width' => 50,'align' => 'center'),
            "container_quantity_units"=>array('width' => 50,'align' => 'center'),
            "container_measurement"=>array('width' => 50,'align' => 'center'),
            "container_seal_number"=>array('width' => 50,'align' => 'center'),
            "container_type"=>array('width' => 50,'align' => 'center'),
            "container_tare_weight_kg"=>array('width' => 50,'align' => 'center'),
            "container_gross_weight_kg"=>array('width' => 50,'align' => 'center'),
            "container_teu"=>array('width' => 50,'align' => 'center'),
            "item_gross_weight_kg"=>array('width' => 100,'align' => 'right'),
            "item_measurement"=>array('width' => 50,'align' => 'center'),
            "item_quantity"=>array('width' => 100,'align' => 'right'),
            "item_quantity_units"=>array('width' => 100,'align' => 'center'),
            "item_description"=>array('width' => 300,'align' => 'left')

            );

        if (!isset($data[$string]))
            return false;
        else
            return $data[$string];
    }

    function ar_imports_column_glossary($string)
    {
        $data = array(
            "hs_code" => "A description of the goods defined by the HS Code",
            "importer_name" => "The company importing the shipment into the destination country",
            "product" => "The exporter's description of the shipment as it appears on the bill of lading or shipping manifest",
        // "orig_country" => "The country of the last foreign port the shipment passes through before arriving in the destination country",
            "embarq_port" => "Where the shipment has been loaded and sent from",

            );

        if (!isset($data[$string]))
            return false;
        else
            return $data[$string];
    }

    function ca_exports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "hs_code" => "120",
            "spanish_description" => "300",
            "province" => "120",
            "tipes" => "200",
            "destiny_country" => "120",
            "destiny_state" => "150",
            "quantity" => "80",
            "comercial_unit" => "120",
            "export_fob_value" => "150",
            "port_of_departure" => "200",
            "hs_code_description" => "120",
            "export_type" => "150",
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function ca_imports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "hs_code" => "120",
            "spanish_description" => "300",
            "province" => "120",
            "tipes" => "120",
            "origin_country" => "120",
            "state_of_origin" => "150",
            "quantity" => "80",
            "comercial_unit" => "120",
            "import_fob_value" => "150",
            "port_of_entry" => "200",
            "hs_code_description" => "120",
            );


        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function cl_exports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "export_id" => "150",
            "custom" => "100",
            "exporter_id" => "150",
            "exporter_id_check_digit" => "150",
            "exporter" => "250",
            "hs_code" => "100",
            "hs_code_description" => "250",
            "product" => "500",
            "variety" => "200",
            "brand" => "200",
            "description" => "300",
            "description_2" => "300",
            "description_3" => "300",
            "description_4" => "300",
            "destiny_country" => "150",
            "transport_type" => "150",
            "transport_company" => "200",
            "ship_name" => "250",
            "load_type" => "120",
            "origin_port" => "300",
            "landing_port" => "300",
            "gross_weight" => "120",
            "quantity" => "100",
            "measure_unit" => "150",
            "us_fob" => "100",
            "us_freight" => "150",
            "us_insurance" => "150",
            "us_cif" => "100",
            "us_fob_unit" => "150",
            "package_type" => "300",
            "exporter_region" => "150",
            "packages_quantity" => "150",
            "packages_description" => "150",
            "transport_company_country" => "250",
            "sale_condition" => "150",
            "economic_zone" => "300",
            "exporter_economic_key" => "300",
            "transport_document_number" => "250",
            "transport_document_date" => "250",
            "voyage_number" => "150",
            "incoterms" => "100"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function cl_imports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "custom" => "250",
            "import_id" => "150",
            "importer_id" => "150",
            "importer_id_check_digit" => "200",
            "importer" => "300",
            "hs_code" => "100",
            "hs_code_description" => "400",
            "product" => "300",
            "brand" => "300",
            "variety" => "300",
            "description" => "300",
            "description_2" => "300",
            "description_3" => "300",
            "description_4" => "300",
            "origin_country" => "300",
            "purchase_country" => "300",
            "transport_type" => "300",
            "type_of_payment" => "300",
            "origin_port" => "150",
            "landing_port" => "300",
            "transport_company" => "150",
            "load_type" => "100",
            "package_type" => "250",
            "gross_weight" => "120",
            "incoterm" => "100",
            "tax" => "50",
            "quantity" => "100",
            "measure_unit" => "120",
            "us_fob" => "120",
            "us_freight" => "120",
            "us_insurance" => "120",
            "us_cif" => "120",
            "us_cif_unit" => "200",
            "us_fob_unit" => "200",
            "transport_company_country" => "300",
            "us_tax" => "120",
            "packages_quantity" => "150",
            "economic_zone" => "300",
            "importer_economic_key" => "300",
            "manifest_number" => "150",
            "manifest_date" => "150",
            "transport_document_number" => "300",
            "transport_document_date" => "300",
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function co_exports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "control_id" => "300",
            "custom" => "300",
            "exporter_id" => "300",
            "exporter" => "300",
            "exporter_address" => "300",
            "department_destination" => "300",
            "hs_code" => "300",
            "country_destination" => "300",
            "type_of_transport" => "300",
            "method_of_payment" => "300",
            "weight" => "300",
            "quantity" => "300",
            "unit_of_measure" => "300",
            "us_fob" => "300",
            "us_freight" => "300",
            "us_insurance" => "300",
            "importer" => "300",
            "importer_address" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function co_imports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "custom" => "300",
            "control_id" => "300",
            "importer_id" => "300",
            "importer" => "300",
            "importer_address" => "300",
            "importer_phone" => "300",
            "department_destination" => "300",
            "hs_code" => "300",
            "product" => "300",
            "country_of_origin" => "300",
            "country_of_acquisition" => "300",
            "type_of_transport" => "300",
            "method_of_payment" => "300",
            "transportation_company" => "300",
            "weight" => "300",
            "tax" => "300",
            "exporter" => "300",
            "exporter_address" => "300",
            "exporter_city" => "300",
            "exporter_country" => "300",
            "exporter_phone_email" => "300",
            "quantity" => "300",
            "unit_of_measure" => "300",
            "us_fob" => "300",
            "us_freight" => "300",
            "us_insurance" => "300",
            "us_cif" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }


    function cr_exports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "declaration" => "300",
            "customs" => "300",
            "shipping_number" => "300",
            "tipe" => "300",
            "exporter_id" => "300",
            "exporter" => "300",
            "exporter_address" => "300",
            "total_invoice" => "300",
            "total_cif" => "300",
            "total_gross_weight" => "300",
            "total_net_weight" => "300",
            "transport_type" => "300",
            "exchange_rate" => "300",
            "remarks" => "300",
            "serial_number" => "300",
            "hs_code" => "300",
            "cargo_description" => "300",
            "packages" => "300",
            "packages_type" => "300",
            "brand" => "300",
            "cif_usd" => "300",
            "gross_weight" => "300",
            "net_weight" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function cr_imports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "declaration" => "300",
            "customs" => "300",
            "shipping_number" => "300",
            "tipe" => "300",
            "importer_id" => "300",
            "importer" => "300",
            "importer_address" => "300",
            "total_invoice" => "300",
            "total_cif" => "300",
            "total_gross_weight" => "300",
            "total_net_weight" => "300",
            "transport_type" => "300",
            "exchange_rate" => "300",
            "remarks" => "300",
            "serial_number" => "300",
            "hs_code" => "300",
            "cargo_description" => "300",
            "packages" => "300",
            "packages_type" => "300",
            "brand" => "300",
            "cif_usd" => "300",
            "gross_weight" => "300",
            "net_weight" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function ec_exports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "export_type" => "300",
            "refrendo" => "300",
            "declaration_number_dau" => "300",
            "item_number" => "300",
            "exporter_id" => "300",
            "exporter" => "300",
            "destination_country" => "300",
            "loading_port" => "300",
            "transport_type" => "300",
            "customs" => "300",
            "hs_code" => "300",
            "hs_code_description" => "300",
            "comercial_description" => "300",
            "condition" => "300",
            "container" => "300",
            "packages" => "300",
            "quantity" => "50",
            "unit_of_meassure" => "300",
            "us_fob" => "300",
            "consignee" => "300",
            "customs_agent" => "300",
            "customs_agency" => "300",
            "shipping_agency" => "300",
            "ship" => "300",
            "bl_number" => "300",
            "conocimiento_emb" => "300",
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function ec_imports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "registration_date" => "300",
            "import_type" => "300",
            "refrendo" => "300",
            "declaration_number_dau" => "300",
            "item_number" => "300",
            "importer_id" => "300",
            "importer" => "300",
            "origin_country" => "300",
            "procedence_country" => "300",
            "loading_country" => "300",
            "loading_port" => "300",
            "transport_type" => "300",
            "customs" => "300",
            "hs_code" => "300",
            "hs_code_description" => "300",
            "comercial_description" => "300",
            "brand" => "300",
            "condition" => "300",
            "container" => "300",
            "packages" => "300",
            "quantity" => "300",
            "unit_of_meassure" => "300",
            "advalorem_rate" => "300",
            "us_fob" => "300",
            "us_freight" => "300",
            "us_insurance" => "300",
            "us_cif" => "300",
            "shipper" => "300",
            "customs_agent" => "300",
            "customs_agency" => "300",
            "shipping_agency" => "300",
            "ship" => "300",
            "bl_number" => "300",
            "conocimiento_emb" => "300",
            "commercial_deposit" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function jnpt_imports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "hs_code" => "300",
            "importer_id" => "300",
            "importer_name" => "300",
            "importer_address" => "300",
            "importer_address_2" => "300",
            "city" => "300",
            "zip_cod" => "300",
            "phone" => "300",
            "fax" => "300",
            "e_mail" => "300",
            "item_description" => "300",
            "quantity" => "300",
            "unit" => "300",
            "unit_price_invoice" => "300",
            "unit_price_usd" => "300",
            "unit_price_euro" => "300",
            "custom_agent" => "300",
            "supplier_name" => "300",
            "supplier_address" => "300",
            "supplier_country" => "300",
            "origin_port" => "300",
            "contact_1" => "300",
            "contact_2" => "300",
            "bank" => "300",
            "be_no" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function jnpt_imports_column_glossary($string)
    {
        $data = array(
            "hs_code" => "The	Harmonized System Code is a number designed to classify and describe goods",
            "importer_name" => "The company importing the shipment into the destination country",
            "importer_address" => "The address of the company importing the shipment into the destination country",
            "item_description" => "The exporter's description of the shipment as it appears on the bill of lading or shipping manifest",
            "origin_port" => "The country of the last foreign port the shipment passes through before arriving in the destination country",

            );

        if (!isset($data[$string]))
            return false;
        else
            return $data[$string];
    }

    function mx_imports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "hs_code" => "300",
            "hs_product_dectiption" => "300",
            "product_schedule_b_code" => "300",
            "product_decription_by_schedule_b_code_mexico" => "300",
            "way_of_transport" => "300",
            "country_of_origin" => "300",
            "custom" => "300",
            "total_fob_value" => "300",
            "total_quantity_1" => "300",
            "measure_unit_1" => "300",
            "fob_value_per_unit" => "300",
            "total_gross_weight_kg" => "300",
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function pa_exports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "ruc_exporter_id" => "300",
            "exporter" => "300",
            "hscode" => "300",
            "product_description" => "300",
            "customs_zone" => "300",
            "customs_name" => "300",
            "transport_type" => "300",
            "declaration_number" => "300",
            "destiny_country" => "300",
            "net_weight" => "300",
            "gross_weight" => "300",
            "packages" => "300",
            "quantity" => "300",
            "measure_unit" => "300",
            "us_fob" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function pa_imports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "ruc_importer_id" => "300",
            "importer" => "300",
            "hscode" => "300",
            "product_description" => "300",
            "customs_zone" => "300",
            "customs_name" => "300",
            "transport_type" => "300",
            "declaration_number" => "300",
            "origin_country" => "300",
            "net_weight" => "300",
            "gross_weight" => "300",
            "packages" => "300",
            "quantity" => "300",
            "measure_unit" => "300",
            "us_fob" => "300",
            "us_cif" => "300",
            "us_freight" => "300",
            "us_insurance" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function pe_exports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "dua_id" => "300",
            "serie" => "300",
            "custom" => "300",
            "ruc_exporter_id" => "300",
            "exporter" => "300",
            "exporter_address" => "300",
            "exporter_department" => "300",
            "exporter_state" => "300",
            "exporter_district" => "300",
            "exporter_phone" => "300",
            "exporter_fax" => "300",
            "hs_code" => "300",
            "hs_code_description" => "300",
            "cargo_description" => "300",
            "cargo_description_1" => "300",
            "cargo_description_2" => "300",
            "cargo_description_3" => "300",
            "cargo_description_4" => "300",
            "us_fob" => "300",
            "net_weight" => "300",
            "gross_weight" => "300",
            "quantity" => "300",
            "measure_unit" => "300",
            "us_fob_unit" => "300",
            "commercial_quantity" => "300",
            "commercial_measure_unit" => "300",
            "transport_type" => "300",
            "bank" => "300",
            "destination_country" => "300",
            "destination_port" => "300",
            "customs_agent" => "300",
            "transport_company" => "300",
            "ship" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function pe_imports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "dua_id" => "300",
            "serie" => "300",
            "custom" => "300",
            "ruc_importer_id" => "300",
            "importer" => "300",
            "importer_address" => "300",
            "importer_department" => "300",
            "importer_state" => "300",
            "importer_district" => "300",
            "importer_phone" => "300",
            "importer_fax" => "300",
            "transport_type" => "300",
            "bank" => "300",
            "origin_country" => "300",
            "acquisition_country" => "300",
            "shipping_port" => "300",
            "hs_code" => "300",
            "hs_code_description" => "300",
            "cargo_description" => "300",
            "cargo_description_1" => "300",
            "cargo_description_2" => "300",
            "cargo_description_3" => "300",
            "cargo_description_4" => "300",
            "manufacture_year" => "300",
            "us_fob" => "300",
            "us_freight" => "300",
            "us_insurance" => "300",
            "us_cif" => "300",
            "advalorem" => "300",
            "local_tax" => "300",
            "net_weight" => "300",
            "gross_weight" => "300",
            "quantity" => "300",
            "measure_unit" => "300",
            "us_cif_unit" => "300",
            "commercial_quantity" => "300",
            "commercial_measure_unit" => "300",
            "package_type" => "300",
            "packages_quantity" => "300",
            "product_status" => "300",
            "shipper_name" => "300",
            "customs_agent" => "300",
            "transport_company" => "300",
            "incoterm" => "300",
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function py_exports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "exporter_id" => "300",
            "exporter" => "300",
            "consignee" => "300",
            "destiny_country" => "300",
            "hs_code" => "300",
            "quantity" => "300",
            "measure_unit" => "300",
            "product" => "300",
            "gross_kilo" => "300",
            "net_kilo" => "300",
            "us_fob" => "300",
            "us_freight" => "300",
            "us_insurance" => "300",
            "us_cif" => "300",
            "us_fob_unit" => "300",
            "brand" => "300",
            "custom" => "300",
            "transport_type" => "300",
            "transport_company" => "300",
            "transportist_country" => "300",
            "manifest" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function py_imports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "importer_id" => "300",
            "importer" => "300",
            "supplier" => "300",
            "origin_country" => "300",
            "hs_code" => "300",
            "quantity" => "300",
            "measure_unit" => "300",
            "product" => "300",
            "brand" => "300",
            "gross_kilo" => "300",
            "net_kilo" => "300",
            "us_fob" => "300",
            "us_freight" => "300",
            "us_insurance" => "300",
            "us_cif" => "300",
            "us_fob_unit" => "300",
            "custom" => "300",
            "transport_type" => "300",
            "transport_company" => "300",
            "transportist_country" => "300",
            "acquisition_country" => "300",
            "manifest" => "300",
            "bl_number" => "300",
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function uy_exports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "custom" => "300",
            "exporter" => "300",
            "exporter_id" => "300",
            "hs_code" => "300",
            "product" => "300",
            "country_destination" => "300",
            "type_of_transport" => "300",
            "transportation_company" => "300",
            "gross_weight" => "300",
            "net_weight" => "300",
            "quantity" => "300",
            "unit" => "300",
            "physical_units_quantity" => "300",
            "physical_units" => "300",
            "us_fob" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function uy_imports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "custom" => "300",
            "importer" => "300",
            "importer_id" => "300",
            "hs_code" => "300",
            "product" => "300",
            "country_of_origin" => "300",
            "country_of_acquisition" => "300",
            "type_of_transport" => "300",
            "transportation_company" => "300",
            "gross_weight" => "300",
            "net_weight" => "300",
            "tax" => "300",
            "invalid_field" => "300",
            "quantity" => "300",
            "unit" => "300",
            "physical_units_quantity" => "300",
            "physical_units" => "300",
            "insurance_currency_of_origin" => "300",
            "currency_of_insurance" => "300",
            "freight_currency_of_origin" => "300",
            "currency_of_freight" => "300",
            "us_cif" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }


    function ve_exports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "record" => "300",
            "bl" => "300",
            "chapter" => "300",
            "chapter_description" => "300",
            "hs_code" => "300",
            "payment" => "300",
            "hs_code_description" => "300",
            "exporter" => "300",
            "custom" => "300",
            "transport_type" => "300",
            "dest_country" => "300",
            "dest_port" => "300",
            "gross_weight" => "300",
            "net_weight" => "300",
            "us_fob_bolivare" => "300",
            "us_fob" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }

    function ve_imports_column_style($string)
    {
        $data = array(
            "date" => "100",
            "record" => "300",
            "bl" => "300",
            "chapter" => "300",
            "chapter_description" => "300",
            "hs_code" => "300",
            "hs_code_description" => "300",
            "importer" => "300",
            "custom" => "300",
            "transport_type" => "300",
            "embarq_port" => "300",
            "origin_country" => "300",
            "adq_country" => "300",
            "gross_weight" => "300",
            "net_weight" => "300",
            "us_fob_bolivares" => "300",
            "us_fob" => "300",
            "us_cif_bolivares" => "300",
            "us_cif" => "300"
            );

        if ($string == 'st_show_field')
            return $data;
        else
            return $data[$string];
    }
} 