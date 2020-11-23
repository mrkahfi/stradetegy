<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 9/27/13
 * Time: 1:01 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/** @MongoDB\EmbeddedDocument */
class USANested
{

    /** @MongoDB\Id */
    protected $id;

    /** @MongoDB\String */
    protected $system_identity_id;

    /** @MongoDB\Date */
    protected $estimate_arrival_date;

    /** @MongoDB\Date */
    protected $actual_arrival_date;

    /** @MongoDB\String */
    protected $bill_type_code;

    /** @MongoDB\String */
    protected $bill_of_lading;

    /** @MongoDB\String */
    protected $bill_type_lading;

    /** @MongoDB\String */
    protected $master_bill_of_lading;

    /** @MongoDB\String */
    protected $carrier_sasc_code;

    /** @MongoDB\String */
    protected $vessel_country_code;

    /** @MongoDB\String */
    protected $vessel_code;

    /** @MongoDB\String */
    protected $vessel_name;

    /** @MongoDB\String */
    protected $voyage;

    /** @MongoDB\String */
    protected $inbond_type;

    /** @MongoDB\String */
    protected $manifest_no;

    /** @MongoDB\String */
    protected $mode_of_transportation;

    /** @MongoDB\String */
    protected $loading_port;

    /** @MongoDB\String */
    protected $last_vist_foreign_port;

    /** @MongoDB\String */
    protected $us_clearing_district;

    /** @MongoDB\String */
    protected $unloading_port;

    /** @MongoDB\String */
    protected $place_of_receipt;

    /** @MongoDB\String */
    protected $country;

    /** @MongoDB\String */
    protected $country_sure_level;

    /** @MongoDB\Float */
    protected $weight_in_kg;

    /** @MongoDB\Float */
    protected $weight;

    /** @MongoDB\Float */
    protected $weight_unit;

    /** @MongoDB\Float */
    protected $teu;

    /** @MongoDB\Float */
    protected $quantity;

    /** @MongoDB\String */
    protected $quantity_unit;

    /** @MongoDB\Float */
    protected $measure_in_cm;

    /** @MongoDB\Float */
    protected $measure;

    /** @MongoDB\String */
    protected $measure_unit;

    /** @MongoDB\String */
    protected $container_id;

    /** @MongoDB\String */
    protected $container_size;

    /** @MongoDB\String */
    protected $container_type;

    /** @MongoDB\String */
    protected $container_desc_code;

    /** @MongoDB\String */
    protected $container_load_status;

    /** @MongoDB\String */
    protected $container_type_of_service;

    /** @MongoDB\String */
    protected $shipper_name;

    /** @MongoDB\String */
    protected $shipper_address;

    /** @MongoDB\String */
    protected $raw_shipper_name;

    /** @MongoDB\String */
    protected $raw_shipper_address_1;

    /** @MongoDB\String */
    protected $raw_shipper_address_2;

    /** @MongoDB\String */
    protected $raw_shipper_address_3;

    /** @MongoDB\String */
    protected $raw_shipper_address_4;

    /** @MongoDB\String */
    protected $raw_shipper_address_others;

    /** @MongoDB\String */
    protected $consignee_name; 

    /** @MongoDB\String */
    protected $consignee_address;

    /** @MongoDB\String */
    protected $raw_consignee_name;

    /** @MongoDB\String */
    protected $raw_consignee_address;

    /** @MongoDB\String */
    protected $raw_consignee_address_1;

    /** @MongoDB\String */
    protected $raw_consignee_address_2;

    /** @MongoDB\String */
    protected $raw_consignee_address_3;

    /** @MongoDB\String */
    protected $raw_consignee_address_4;

    /** @MongoDB\String */
    protected $raw_consignee_address_others;

    /** @MongoDB\String */
    protected $raw_notify_party_name;

    /** @MongoDB\String */
    protected $notify_party_name;

    /** @MongoDB\String */
    protected $raw_notify_party_address_1;

    /** @MongoDB\String */
    protected $raw_notify_party_address_2;

    /** @MongoDB\String */
    protected $raw_notify_party_address_3;

    /** @MongoDB\String */
    protected $raw_notify_party_address_4;

    /** @MongoDB\String */
    protected $raw_notify_party_address_others;

    /** @MongoDB\String */
    protected $product_description;

    /** @MongoDB\String */
    protected $marks_numbers;

    /** @MongoDB\String */
    protected $hs_code;

    /** @MongoDB\String */
    protected $hs_code_sure_level;

    /** @MongoDB\String */
    protected $indicator_of_true_supplier;

    /** @MongoDB\String */
    protected $end;

    /** @MongoDB\String */
    protected $slug_country_ori_shipper;

    /** @MongoDB\String */
    protected $slug_country;

    /** @MongoDB\String */
    protected $slug_country_ori_consignee;

    /** @MongoDB\String */
    protected $slug_consignee_name;

    /** @MongoDB\String */
    protected $slug_shipper_name;

    /** @MongoDB\Int */
    protected $total_quantity;

    /** @MongoDB\Int */
    protected $total_weight;

    /** @MongoDB\Int */
    protected $total_measure;

    /** @MongoDB\Int */
    protected $total_shipment;

    /** @MongoDB\String */
    protected $total_company;



    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set systemIdentityId
     *
     * @param string $systemIdentityId
     * @return self
     */
    public function setSystemIdentityId($systemIdentityId)
    {
        $this->system_identity_id = $systemIdentityId;
        return $this;
    }

    /**
     * Get systemIdentityId
     *
     * @return string $systemIdentityId
     */
    public function getSystemIdentityId()
    {
        return $this->system_identity_id;
    }

    /**
     * Set estimateArrivalDate
     *
     * @param date $estimateArrivalDate
     * @return self
     */
    public function setEstimateArrivalDate($estimateArrivalDate)
    {
        $this->estimate_arrival_date = $estimateArrivalDate;
        return $this;
    }

    /**
     * Get estimateArrivalDate
     *
     * @return date $estimateArrivalDate
     */
    public function getEstimateArrivalDate()
    {
        return $this->estimate_arrival_date;
    }

    /**
     * Set actualArrivalDate
     *
     * @param date $actualArrivalDate
     * @return self
     */
    public function setActualArrivalDate($actualArrivalDate)
    {
        $this->actual_arrival_date = $actualArrivalDate;
        return $this;
    }

    /**
     * Get actualArrivalDate
     *
     * @return date $actualArrivalDate
     */
    public function getActualArrivalDate()
    {
        return $this->actual_arrival_date;
    }

    /**
     * Set billTypeCode
     *
     * @param string $billTypeCode
     * @return self
     */
    public function setBillTypeCode($billTypeCode)
    {
        $this->bill_type_code = $billTypeCode;
        return $this;
    }

    /**
     * Get billTypeCode
     *
     * @return string $billTypeCode
     */
    public function getBillTypeCode()
    {
        return $this->bill_type_code;
    }

    /**
     * Set billOfLading
     *
     * @param string $billOfLading
     * @return self
     */
    public function setBillOfLading($billOfLading)
    {
        $this->bill_of_lading = $billOfLading;
        return $this;
    }

    /**
     * Get billOfLading
     *
     * @return string $billOfLading
     */
    public function getBillOfLading()
    {
        return $this->bill_of_lading;
    }

    /**
     * Set billTypeLading
     *
     * @param string $billTypeLading
     * @return self
     */
    public function setBillTypeLading($billTypeLading)
    {
        $this->bill_type_lading = $billTypeLading;
        return $this;
    }

    /**
     * Get billTypeLading
     *
     * @return string $billTypeLading
     */
    public function getBillTypeLading()
    {
        return $this->bill_type_lading;
    }

    /**
     * Set masterBillOfLading
     *
     * @param string $masterBillOfLading
     * @return self
     */
    public function setMasterBillOfLading($masterBillOfLading)
    {
        $this->master_bill_of_lading = $masterBillOfLading;
        return $this;
    }

    /**
     * Get masterBillOfLading
     *
     * @return string $masterBillOfLading
     */
    public function getMasterBillOfLading()
    {
        return $this->master_bill_of_lading;
    }

    /**
     * Set carrierSascCode
     *
     * @param string $carrierSascCode
     * @return self
     */
    public function setCarrierSascCode($carrierSascCode)
    {
        $this->carrier_sasc_code = $carrierSascCode;
        return $this;
    }

    /**
     * Get carrierSascCode
     *
     * @return string $carrierSascCode
     */
    public function getCarrierSascCode()
    {
        return $this->carrier_sasc_code;
    }

    /**
     * Set vesselCountryCode
     *
     * @param string $vesselCountryCode
     * @return self
     */
    public function setVesselCountryCode($vesselCountryCode)
    {
        $this->vessel_country_code = $vesselCountryCode;
        return $this;
    }

    /**
     * Get vesselCountryCode
     *
     * @return string $vesselCountryCode
     */
    public function getVesselCountryCode()
    {
        return $this->vessel_country_code;
    }

    /**
     * Set vesselCode
     *
     * @param string $vesselCode
     * @return self
     */
    public function setVesselCode($vesselCode)
    {
        $this->vessel_code = $vesselCode;
        return $this;
    }

    /**
     * Get vesselCode
     *
     * @return string $vesselCode
     */
    public function getVesselCode()
    {
        return $this->vessel_code;
    }

    /**
     * Set vesselName
     *
     * @param string $vesselName
     * @return self
     */
    public function setVesselName($vesselName)
    {
        $this->vessel_name = $vesselName;
        return $this;
    }

    /**
     * Get vesselName
     *
     * @return string $vesselName
     */
    public function getVesselName()
    {
        return $this->vessel_name;
    }

    /**
     * Set voyage
     *
     * @param string $voyage
     * @return self
     */
    public function setVoyage($voyage)
    {
        $this->voyage = $voyage;
        return $this;
    }

    /**
     * Get voyage
     *
     * @return string $voyage
     */
    public function getVoyage()
    {
        return $this->voyage;
    }

    /**
     * Set inbondType
     *
     * @param string $inbondType
     * @return self
     */
    public function setInbondType($inbondType)
    {
        $this->inbond_type = $inbondType;
        return $this;
    }

    /**
     * Get inbondType
     *
     * @return string $inbondType
     */
    public function getInbondType()
    {
        return $this->inbond_type;
    }

    /**
     * Set manifestNo
     *
     * @param string $manifestNo
     * @return self
     */
    public function setManifestNo($manifestNo)
    {
        $this->manifest_no = $manifestNo;
        return $this;
    }

    /**
     * Get manifestNo
     *
     * @return string $manifestNo
     */
    public function getManifestNo()
    {
        return $this->manifest_no;
    }

    /**
     * Set modeOfTransportation
     *
     * @param string $modeOfTransportation
     * @return self
     */
    public function setModeOfTransportation($modeOfTransportation)
    {
        $this->mode_of_transportation = $modeOfTransportation;
        return $this;
    }

    /**
     * Get modeOfTransportation
     *
     * @return string $modeOfTransportation
     */
    public function getModeOfTransportation()
    {
        return $this->mode_of_transportation;
    }

    /**
     * Set loadingPort
     *
     * @param string $loadingPort
     * @return self
     */
    public function setLoadingPort($loadingPort)
    {
        $this->loading_port = $loadingPort;
        return $this;
    }

    /**
     * Get loadingPort
     *
     * @return string $loadingPort
     */
    public function getLoadingPort()
    {
        return $this->loading_port;
    }

    /**
     * Set lastVistForeignPort
     *
     * @param string $lastVistForeignPort
     * @return self
     */
    public function setLastVistForeignPort($lastVistForeignPort)
    {
        $this->last_vist_foreign_port = $lastVistForeignPort;
        return $this;
    }

    /**
     * Get lastVistForeignPort
     *
     * @return string $lastVistForeignPort
     */
    public function getLastVistForeignPort()
    {
        return $this->last_vist_foreign_port;
    }

    /**
     * Set usClearingDistrict
     *
     * @param string $usClearingDistrict
     * @return self
     */
    public function setUsClearingDistrict($usClearingDistrict)
    {
        $this->us_clearing_district = $usClearingDistrict;
        return $this;
    }

    /**
     * Get usClearingDistrict
     *
     * @return string $usClearingDistrict
     */
    public function getUsClearingDistrict()
    {
        return $this->us_clearing_district;
    }

    /**
     * Set unloadingPort
     *
     * @param string $unloadingPort
     * @return self
     */
    public function setUnloadingPort($unloadingPort)
    {
        $this->unloading_port = $unloadingPort;
        return $this;
    }

    /**
     * Get unloadingPort
     *
     * @return string $unloadingPort
     */
    public function getUnloadingPort()
    {
        return $this->unloading_port;
    }

    /**
     * Set placeOfReceipt
     *
     * @param string $placeOfReceipt
     * @return self
     */
    public function setPlaceOfReceipt($placeOfReceipt)
    {
        $this->place_of_receipt = $placeOfReceipt;
        return $this;
    }

    /**
     * Get placeOfReceipt
     *
     * @return string $placeOfReceipt
     */
    public function getPlaceOfReceipt()
    {
        return $this->place_of_receipt;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return self
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set countrySureLevel
     *
     * @param string $countrySureLevel
     * @return self
     */
    public function setCountrySureLevel($countrySureLevel)
    {
        $this->country_sure_level = $countrySureLevel;
        return $this;
    }

    /**
     * Get countrySureLevel
     *
     * @return string $countrySureLevel
     */
    public function getCountrySureLevel()
    {
        return $this->country_sure_level;
    }

    /**
     * Set weightInKg
     *
     * @param float $weightInKg
     * @return self
     */
    public function setWeightInKg($weightInKg)
    {
        $this->weight_in_kg = $weightInKg;
        return $this;
    }

    /**
     * Get weightInKg
     *
     * @return float $weightInKg
     */
    public function getWeightInKg()
    {
        return $this->weight_in_kg;
    }

    /**
     * Set weight
     *
     * @param float $weight
     * @return self
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * Get weight
     *
     * @return float $weight
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set weightUnit
     *
     * @param float $weightUnit
     * @return self
     */
    public function setWeightUnit($weightUnit)
    {
        $this->weight_unit = $weightUnit;
        return $this;
    }

    /**
     * Get weightUnit
     *
     * @return float $weightUnit
     */
    public function getWeightUnit()
    {
        return $this->weight_unit;
    }

    /**
     * Set teu
     *
     * @param float $teu
     * @return self
     */
    public function setTeu($teu)
    {
        $this->teu = $teu;
        return $this;
    }

    /**
     * Get teu
     *
     * @return float $teu
     */
    public function getTeu()
    {
        return $this->teu;
    }

    /**
     * Set quantity
     *
     * @param float $quantity
     * @return self
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Get quantity
     *
     * @return float $quantity
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set quantityUnit
     *
     * @param string $quantityUnit
     * @return self
     */
    public function setQuantityUnit($quantityUnit)
    {
        $this->quantity_unit = $quantityUnit;
        return $this;
    }

    /**
     * Get quantityUnit
     *
     * @return string $quantityUnit
     */
    public function getQuantityUnit()
    {
        return $this->quantity_unit;
    }

    /**
     * Set measureInCm
     *
     * @param float $measureInCm
     * @return self
     */
    public function setMeasureInCm($measureInCm)
    {
        $this->measure_in_cm = $measureInCm;
        return $this;
    }

    /**
     * Get measureInCm
     *
     * @return float $measureInCm
     */
    public function getMeasureInCm()
    {
        return $this->measure_in_cm;
    }

    /**
     * Set measure
     *
     * @param float $measure
     * @return self
     */
    public function setMeasure($measure)
    {
        $this->measure = $measure;
        return $this;
    }

    /**
     * Get measure
     *
     * @return float $measure
     */
    public function getMeasure()
    {
        return $this->measure;
    }

    /**
     * Set measureUnit
     *
     * @param string $measureUnit
     * @return self
     */
    public function setMeasureUnit($measureUnit)
    {
        $this->measure_unit = $measureUnit;
        return $this;
    }

    /**
     * Get measureUnit
     *
     * @return string $measureUnit
     */
    public function getMeasureUnit()
    {
        return $this->measure_unit;
    }

    /**
     * Set containerId
     *
     * @param string $containerId
     * @return self
     */
    public function setContainerId($containerId)
    {
        $this->container_id = $containerId;
        return $this;
    }

    /**
     * Get containerId
     *
     * @return string $containerId
     */
    public function getContainerId()
    {
        return $this->container_id;
    }

    /**
     * Set containerSize
     *
     * @param string $containerSize
     * @return self
     */
    public function setContainerSize($containerSize)
    {
        $this->container_size = $containerSize;
        return $this;
    }

    /**
     * Get containerSize
     *
     * @return string $containerSize
     */
    public function getContainerSize()
    {
        return $this->container_size;
    }

    /**
     * Set containerType
     *
     * @param string $containerType
     * @return self
     */
    public function setContainerType($containerType)
    {
        $this->container_type = $containerType;
        return $this;
    }

    /**
     * Get containerType
     *
     * @return string $containerType
     */
    public function getContainerType()
    {
        return $this->container_type;
    }

    /**
     * Set containerDescCode
     *
     * @param string $containerDescCode
     * @return self
     */
    public function setContainerDescCode($containerDescCode)
    {
        $this->container_desc_code = $containerDescCode;
        return $this;
    }

    /**
     * Get containerDescCode
     *
     * @return string $containerDescCode
     */
    public function getContainerDescCode()
    {
        return $this->container_desc_code;
    }

    /**
     * Set containerLoadStatus
     *
     * @param string $containerLoadStatus
     * @return self
     */
    public function setContainerLoadStatus($containerLoadStatus)
    {
        $this->container_load_status = $containerLoadStatus;
        return $this;
    }

    /**
     * Get containerLoadStatus
     *
     * @return string $containerLoadStatus
     */
    public function getContainerLoadStatus()
    {
        return $this->container_load_status;
    }

    /**
     * Set containerTypeOfService
     *
     * @param string $containerTypeOfService
     * @return self
     */
    public function setContainerTypeOfService($containerTypeOfService)
    {
        $this->container_type_of_service = $containerTypeOfService;
        return $this;
    }

    /**
     * Get containerTypeOfService
     *
     * @return string $containerTypeOfService
     */
    public function getContainerTypeOfService()
    {
        return $this->container_type_of_service;
    }

    /**
     * Set shipperName
     *
     * @param string $shipperName
     * @return self
     */
    public function setShipperName($shipperName)
    {
        $this->shipper_name = $shipperName;
        return $this;
    }

    /**
     * Get shipperName
     *
     * @return string $shipperName
     */
    public function getShipperName()
    {
        return $this->shipper_name;
    }

    /**
     * Set shipperAddress
     *
     * @param string $shipperAddress
     * @return self
     */
    public function setShipperAddress($shipperAddress)
    {
        $this->shipper_address = $shipperAddress;
        return $this;
    }

    /**
     * Get shipperAddress
     *
     * @return string $shipperAddress
     */
    public function getShipperAddress()
    {
        return $this->shipper_address;
    }

    /**
     * Set rawShipperName
     *
     * @param string $rawShipperName
     * @return self
     */
    public function setRawShipperName($rawShipperName)
    {
        $this->raw_shipper_name = $rawShipperName;
        return $this;
    }

    /**
     * Get rawShipperName
     *
     * @return string $rawShipperName
     */
    public function getRawShipperName()
    {
        return $this->raw_shipper_name;
    }

    /**
     * Set rawShipperAddress1
     *
     * @param string $rawShipperAddress1
     * @return self
     */
    public function setRawShipperAddress1($rawShipperAddress1)
    {
        $this->raw_shipper_address_1 = $rawShipperAddress1;
        return $this;
    }

    /**
     * Get rawShipperAddress1
     *
     * @return string $rawShipperAddress1
     */
    public function getRawShipperAddress1()
    {
        return $this->raw_shipper_address_1;
    }

    /**
     * Set rawShipperAddress2
     *
     * @param string $rawShipperAddress2
     * @return self
     */
    public function setRawShipperAddress2($rawShipperAddress2)
    {
        $this->raw_shipper_address_2 = $rawShipperAddress2;
        return $this;
    }

    /**
     * Get rawShipperAddress2
     *
     * @return string $rawShipperAddress2
     */
    public function getRawShipperAddress2()
    {
        return $this->raw_shipper_address_2;
    }

    /**
     * Set rawShipperAddress3
     *
     * @param string $rawShipperAddress3
     * @return self
     */
    public function setRawShipperAddress3($rawShipperAddress3)
    {
        $this->raw_shipper_address_3 = $rawShipperAddress3;
        return $this;
    }

    /**
     * Get rawShipperAddress3
     *
     * @return string $rawShipperAddress3
     */
    public function getRawShipperAddress3()
    {
        return $this->raw_shipper_address_3;
    }

    /**
     * Set rawShipperAddress4
     *
     * @param string $rawShipperAddress4
     * @return self
     */
    public function setRawShipperAddress4($rawShipperAddress4)
    {
        $this->raw_shipper_address_4 = $rawShipperAddress4;
        return $this;
    }

    /**
     * Get rawShipperAddress4
     *
     * @return string $rawShipperAddress4
     */
    public function getRawShipperAddress4()
    {
        return $this->raw_shipper_address_4;
    }

    /**
     * Set rawShipperAddressOthers
     *
     * @param string $rawShipperAddressOthers
     * @return self
     */
    public function setRawShipperAddressOthers($rawShipperAddressOthers)
    {
        $this->raw_shipper_address_others = $rawShipperAddressOthers;
        return $this;
    }

    /**
     * Get rawShipperAddressOthers
     *
     * @return string $rawShipperAddressOthers
     */
    public function getRawShipperAddressOthers()
    {
        return $this->raw_shipper_address_others;
    }

    /**
     * Set consigneeName
     *
     * @param string $consigneeName
     * @return self
     */
    public function setConsigneeName($consigneeName)
    {
        $this->consignee_name = $consigneeName;
        return $this;
    }

    /**
     * Get consigneeName
     *
     * @return string $consigneeName
     */
    public function getConsigneeName()
    {
        return $this->consignee_name;
    }

    /**
     * Set consigneeAddress
     *
     * @param string $consigneeAddress
     * @return self
     */
    public function setConsigneeAddress($consigneeAddress)
    {
        $this->consignee_address = $consigneeAddress;
        return $this;
    }

    /**
     * Get consigneeAddress
     *
     * @return string $consigneeAddress
     */
    public function getConsigneeAddress()
    {
        return $this->consignee_address;
    }

    /**
     * Set rawConsigneeName
     *
     * @param string $rawConsigneeName
     * @return self
     */
    public function setRawConsigneeName($rawConsigneeName)
    {
        $this->raw_consignee_name = $rawConsigneeName;
        return $this;
    }

    /**
     * Get rawConsigneeName
     *
     * @return string $rawConsigneeName
     */
    public function getRawConsigneeName()
    {
        return $this->raw_consignee_name;
    }

    /**
     * Set rawConsigneeAddress
     *
     * @param string $rawConsigneeAddress
     * @return self
     */
    public function setRawConsigneeAddress($rawConsigneeAddress)
    {
        $this->raw_consignee_address = $rawConsigneeAddress;
        return $this;
    }

    /**
     * Get rawConsigneeAddress
     *
     * @return string $rawConsigneeAddress
     */
    public function getRawConsigneeAddress()
    {
        return $this->raw_consignee_address;
    }

    /**
     * Set rawConsigneeAddress1
     *
     * @param string $rawConsigneeAddress1
     * @return self
     */
    public function setRawConsigneeAddress1($rawConsigneeAddress1)
    {
        $this->raw_consignee_address_1 = $rawConsigneeAddress1;
        return $this;
    }

    /**
     * Get rawConsigneeAddress1
     *
     * @return string $rawConsigneeAddress1
     */
    public function getRawConsigneeAddress1()
    {
        return $this->raw_consignee_address_1;
    }

    /**
     * Set rawConsigneeAddress2
     *
     * @param string $rawConsigneeAddress2
     * @return self
     */
    public function setRawConsigneeAddress2($rawConsigneeAddress2)
    {
        $this->raw_consignee_address_2 = $rawConsigneeAddress2;
        return $this;
    }

    /**
     * Get rawConsigneeAddress2
     *
     * @return string $rawConsigneeAddress2
     */
    public function getRawConsigneeAddress2()
    {
        return $this->raw_consignee_address_2;
    }

    /**
     * Set rawConsigneeAddress3
     *
     * @param string $rawConsigneeAddress3
     * @return self
     */
    public function setRawConsigneeAddress3($rawConsigneeAddress3)
    {
        $this->raw_consignee_address_3 = $rawConsigneeAddress3;
        return $this;
    }

    /**
     * Get rawConsigneeAddress3
     *
     * @return string $rawConsigneeAddress3
     */
    public function getRawConsigneeAddress3()
    {
        return $this->raw_consignee_address_3;
    }

    /**
     * Set rawConsigneeAddress4
     *
     * @param string $rawConsigneeAddress4
     * @return self
     */
    public function setRawConsigneeAddress4($rawConsigneeAddress4)
    {
        $this->raw_consignee_address_4 = $rawConsigneeAddress4;
        return $this;
    }

    /**
     * Get rawConsigneeAddress4
     *
     * @return string $rawConsigneeAddress4
     */
    public function getRawConsigneeAddress4()
    {
        return $this->raw_consignee_address_4;
    }

    /**
     * Set rawConsigneeAddressOthers
     *
     * @param string $rawConsigneeAddressOthers
     * @return self
     */
    public function setRawConsigneeAddressOthers($rawConsigneeAddressOthers)
    {
        $this->raw_consignee_address_others = $rawConsigneeAddressOthers;
        return $this;
    }

    /**
     * Get rawConsigneeAddressOthers
     *
     * @return string $rawConsigneeAddressOthers
     */
    public function getRawConsigneeAddressOthers()
    {
        return $this->raw_consignee_address_others;
    }

    /**
     * Set rawNotifyPartyName
     *
     * @param string $rawNotifyPartyName
     * @return self
     */
    public function setRawNotifyPartyName($rawNotifyPartyName)
    {
        $this->raw_notify_party_name = $rawNotifyPartyName;
        return $this;
    }

    /**
     * Get rawNotifyPartyName
     *
     * @return string $rawNotifyPartyName
     */
    public function getRawNotifyPartyName()
    {
        return $this->raw_notify_party_name;
    }

    /**
     * Set notifyPartyName
     *
     * @param string $notifyPartyName
     * @return self
     */
    public function setNotifyPartyName($notifyPartyName)
    {
        $this->notify_party_name = $notifyPartyName;
        return $this;
    }

    /**
     * Get notifyPartyName
     *
     * @return string $notifyPartyName
     */
    public function getNotifyPartyName()
    {
        return $this->notify_party_name;
    }

    /**
     * Set rawNotifyPartyAddress1
     *
     * @param string $rawNotifyPartyAddress1
     * @return self
     */
    public function setRawNotifyPartyAddress1($rawNotifyPartyAddress1)
    {
        $this->raw_notify_party_address_1 = $rawNotifyPartyAddress1;
        return $this;
    }

    /**
     * Get rawNotifyPartyAddress1
     *
     * @return string $rawNotifyPartyAddress1
     */
    public function getRawNotifyPartyAddress1()
    {
        return $this->raw_notify_party_address_1;
    }

    /**
     * Set rawNotifyPartyAddress2
     *
     * @param string $rawNotifyPartyAddress2
     * @return self
     */
    public function setRawNotifyPartyAddress2($rawNotifyPartyAddress2)
    {
        $this->raw_notify_party_address_2 = $rawNotifyPartyAddress2;
        return $this;
    }

    /**
     * Get rawNotifyPartyAddress2
     *
     * @return string $rawNotifyPartyAddress2
     */
    public function getRawNotifyPartyAddress2()
    {
        return $this->raw_notify_party_address_2;
    }

    /**
     * Set rawNotifyPartyAddress3
     *
     * @param string $rawNotifyPartyAddress3
     * @return self
     */
    public function setRawNotifyPartyAddress3($rawNotifyPartyAddress3)
    {
        $this->raw_notify_party_address_3 = $rawNotifyPartyAddress3;
        return $this;
    }

    /**
     * Get rawNotifyPartyAddress3
     *
     * @return string $rawNotifyPartyAddress3
     */
    public function getRawNotifyPartyAddress3()
    {
        return $this->raw_notify_party_address_3;
    }

    /**
     * Set rawNotifyPartyAddress4
     *
     * @param string $rawNotifyPartyAddress4
     * @return self
     */
    public function setRawNotifyPartyAddress4($rawNotifyPartyAddress4)
    {
        $this->raw_notify_party_address_4 = $rawNotifyPartyAddress4;
        return $this;
    }

    /**
     * Get rawNotifyPartyAddress4
     *
     * @return string $rawNotifyPartyAddress4
     */
    public function getRawNotifyPartyAddress4()
    {
        return $this->raw_notify_party_address_4;
    }

    /**
     * Set rawNotifyPartyAddressOthers
     *
     * @param string $rawNotifyPartyAddressOthers
     * @return self
     */
    public function setRawNotifyPartyAddressOthers($rawNotifyPartyAddressOthers)
    {
        $this->raw_notify_party_address_others = $rawNotifyPartyAddressOthers;
        return $this;
    }

    /**
     * Get rawNotifyPartyAddressOthers
     *
     * @return string $rawNotifyPartyAddressOthers
     */
    public function getRawNotifyPartyAddressOthers()
    {
        return $this->raw_notify_party_address_others;
    }

    /**
     * Set productDescription
     *
     * @param string $productDescription
     * @return self
     */
    public function setProductDescription($productDescription)
    {
        $this->product_description = $productDescription;
        return $this;
    }

    /**
     * Get productDescription
     *
     * @return string $productDescription
     */
    public function getProductDescription()
    {
        return $this->product_description;
    }

    /**
     * Set marksNumbers
     *
     * @param string $marksNumbers
     * @return self
     */
    public function setMarksNumbers($marksNumbers)
    {
        $this->marks_numbers = $marksNumbers;
        return $this;
    }

    /**
     * Get marksNumbers
     *
     * @return string $marksNumbers
     */
    public function getMarksNumbers()
    {
        return $this->marks_numbers;
    }

    /**
     * Set hsCode
     *
     * @param string $hsCode
     * @return self
     */
    public function setHsCode($hsCode)
    {
        $this->hs_code = $hsCode;
        return $this;
    }

    /**
     * Get hsCode
     *
     * @return string $hsCode
     */
    public function getHsCode()
    {
        return $this->hs_code;
    }

    /**
     * Set hsCodeSureLevel
     *
     * @param string $hsCodeSureLevel
     * @return self
     */
    public function setHsCodeSureLevel($hsCodeSureLevel)
    {
        $this->hs_code_sure_level = $hsCodeSureLevel;
        return $this;
    }

    /**
     * Get hsCodeSureLevel
     *
     * @return string $hsCodeSureLevel
     */
    public function getHsCodeSureLevel()
    {
        return $this->hs_code_sure_level;
    }

    /**
     * Set indicatorOfTrueSupplier
     *
     * @param string $indicatorOfTrueSupplier
     * @return self
     */
    public function setIndicatorOfTrueSupplier($indicatorOfTrueSupplier)
    {
        $this->indicator_of_true_supplier = $indicatorOfTrueSupplier;
        return $this;
    }

    /**
     * Get indicatorOfTrueSupplier
     *
     * @return string $indicatorOfTrueSupplier
     */
    public function getIndicatorOfTrueSupplier()
    {
        return $this->indicator_of_true_supplier;
    }

    /**
     * Set end
     *
     * @param string $end
     * @return self
     */
    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * Get end
     *
     * @return string $end
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set slugCountryOriShipper
     *
     * @param string $slugCountryOriShipper
     * @return self
     */
    public function setSlugCountryOriShipper($slugCountryOriShipper)
    {
        $this->slug_country_ori_shipper = $slugCountryOriShipper;
        return $this;
    }

    /**
     * Get slugCountryOriShipper
     *
     * @return string $slugCountryOriShipper
     */
    public function getSlugCountryOriShipper()
    {
        return $this->slug_country_ori_shipper;
    }

    /**
     * Set slugCountry
     *
     * @param string $slugCountry
     * @return self
     */
    public function setSlugCountry($slugCountry)
    {
        $this->slug_country = $slugCountry;
        return $this;
    }

    /**
     * Get slugCountry
     *
     * @return string $slugCountry
     */
    public function getSlugCountry()
    {
        return $this->slug_country;
    }

    /**
     * Set slugCountryOriConsignee
     *
     * @param string $slugCountryOriConsignee
     * @return self
     */
    public function setSlugCountryOriConsignee($slugCountryOriConsignee)
    {
        $this->slug_country_ori_consignee = $slugCountryOriConsignee;
        return $this;
    }

    /**
     * Get slugCountryOriConsignee
     *
     * @return string $slugCountryOriConsignee
     */
    public function getSlugCountryOriConsignee()
    {
        return $this->slug_country_ori_consignee;
    }

    /**
     * Set slugConsigneeName
     *
     * @param string $slugConsigneeName
     * @return self
     */
    public function setSlugConsigneeName($slugConsigneeName)
    {
        $this->slug_consignee_name = $slugConsigneeName;
        return $this;
    }

    /**
     * Get slugConsigneeName
     *
     * @return string $slugConsigneeName
     */
    public function getSlugConsigneeName()
    {
        return $this->slug_consignee_name;
    }

    /**
     * Set slugShipperName
     *
     * @param string $slugShipperName
     * @return self
     */
    public function setSlugShipperName($slugShipperName)
    {
        $this->slug_shipper_name = $slugShipperName;
        return $this;
    }

    /**
     * Get slugShipperName
     *
     * @return string $slugShipperName
     */
    public function getSlugShipperName()
    {
        return $this->slug_shipper_name;
    }

    /**
     * Set totalQuantity
     *
     * @param int $totalQuantity
     * @return self
     */
    public function setTotalQuantity($totalQuantity)
    {
        $this->total_quantity = $totalQuantity;
        return $this;
    }

    /**
     * Get totalQuantity
     *
     * @return int $totalQuantity
     */
    public function getTotalQuantity()
    {
        return $this->total_quantity;
    }

    /**
     * Set totalWeight
     *
     * @param int $totalWeight
     * @return self
     */
    public function setTotalWeight($totalWeight)
    {
        $this->total_weight = $totalWeight;
        return $this;
    }

    /**
     * Get totalWeight
     *
     * @return int $totalWeight
     */
    public function getTotalWeight()
    {
        return $this->total_weight;
    }

    /**
     * Set totalMeasure
     *
     * @param int $totalMeasure
     * @return self
     */
    public function setTotalMeasure($totalMeasure)
    {
        $this->total_measure = $totalMeasure;
        return $this;
    }

    /**
     * Get totalMeasure
     *
     * @return int $totalMeasure
     */
    public function getTotalMeasure()
    {
        return $this->total_measure;
    }

    /**
     * Set totalShipment
     *
     * @param int $totalShipment
     * @return self
     */
    public function setTotalShipment($totalShipment)
    {
        $this->total_shipment = $totalShipment;
        return $this;
    }

    /**
     * Get totalShipment
     *
     * @return int $totalShipment
     */
    public function getTotalShipment()
    {
        return $this->total_shipment;
    }

    /**
     * Set totalCompany
     *
     * @param string $totalCompany
     * @return self
     */
    public function setTotalCompany($totalCompany)
    {
        $this->total_company = $totalCompany;
        return $this;
    }

    /**
     * Get totalCompany
     *
     * @return string $totalCompany
     */
    public function getTotalCompany()
    {
        return $this->total_company;
    }
}
