<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 9/25/13
 * Time: 2:03 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @MongoDB\Document(collection="usa_importers")
 *
 */
class UsaImporters {

    /** @MongoDB\Id */
    protected $id;

    /** @MongoDB\String */
    protected $system_identity_id;

    /** @MongoDB\Date */
    protected $estimate_arrival_date;

    /** @MongoDB\Date */
    protected $actual_arrival_date;

    /** @MongoDB\String */
    protected $bill_of_lading;

    /** @MongoDB\String */
    protected $master_bill_of_lading;

    /** @MongoDB\String */
    protected $bill_type_code;

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

    /** @MongoDB\Int */
    protected $country_sure_level;

    /** @MongoDB\Float */
    protected $weight_in_kg;

    /** @MongoDB\Float */
    protected $weight;

    /** @MongoDB\String */
    protected $weight_unit;

    /** @MongoDB\Float */
    protected $teu;

    /** @MongoDB\Int */
    protected $quantity;

    /** @MongoDB\String */
    protected $quantity_unit;

    /** @MongoDB\Int */
    protected $measure_in_cm;

    /** @MongoDB\Int */
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
    protected $raw_shipper_addr1;

    /** @MongoDB\String */
    protected $raw_shipper_addr2;

    /** @MongoDB\String */
    protected $raw_shipper_addr3;

    /** @MongoDB\String */
    protected $raw_shipper_addr4;

    /** @MongoDB\String */
    protected $raw_shipper_addr_others;

    /** @MongoDB\String */
    protected $consignee_name;

    /** @MongoDB\String */
    protected $consignee_address;

    /** @MongoDB\String */
    protected $raw_consignee_name;

    /** @MongoDB\String */
    protected $raw_consignee_addr1;

    /** @MongoDB\String */
    protected $raw_consignee_addr2;

    /** @MongoDB\String */
    protected $raw_consignee_addr3;

    /** @MongoDB\String */
    protected $raw_consignee_addr4;

    /** @MongoDB\String */
    protected $raw_consignee_addr_others;

    /** @MongoDB\String */
    protected $notify_party_name;

    /** @MongoDB\String */
    protected $notify_party_address;

    /** @MongoDB\String */
    protected $raw_notify_party_name;

    /** @MongoDB\String */
    protected $raw_notify_party_addr1;

    /** @MongoDB\String */
    protected $raw_notify_party_addr2;

    /** @MongoDB\String */
    protected $raw_notify_party_addr3;

    /** @MongoDB\String */
    protected $raw_notify_party_addr4;

    /** @MongoDB\String */
    protected $raw_notify_party_addr_others;

    /** @MongoDB\String */
    protected $product_desc;

    /** @MongoDB\String */
    protected $marks_and_numbers;

    /** @MongoDB\String */
    protected $hs_code;

    /** @MongoDB\String */
    protected $hs_code_sure_level;

    /** @MongoDB\String */
    protected $cif;

    /** @MongoDB\String */
    protected $indicator_of_true_supplier;

    /** @MongoDB\String */
    protected $indicator_of_true_buyer;

    /** @MongoDB\String */
    protected $end;

    /** @MongoDB\String */
    protected $slug_country_ori_shipper;

    /** @MongoDB\String */
    protected $slug_country_ori_consignee;

    /** @MongoDB\String */
    protected $slug_consignee_name;

    /** @MongoDB\String */
    protected $slug_shipper_name;

    /** @MongoDB\String */
    protected $identity_shipper_name;

    /** @MongoDB\String */
    protected $identity_consignee_name;



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
     * @param int $countrySureLevel
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
     * @return int $countrySureLevel
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
     * @param string $weightUnit
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
     * @return string $weightUnit
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
     * @param int $quantity
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
     * @return int $quantity
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
     * @param int $measureInCm
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
     * @return int $measureInCm
     */
    public function getMeasureInCm()
    {
        return $this->measure_in_cm;
    }

    /**
     * Set measure
     *
     * @param int $measure
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
     * @return int $measure
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
     * Set rawShipperAddr1
     *
     * @param string $rawShipperAddr1
     * @return self
     */
    public function setRawShipperAddr1($rawShipperAddr1)
    {
        $this->raw_shipper_addr1 = $rawShipperAddr1;
        return $this;
    }

    /**
     * Get rawShipperAddr1
     *
     * @return string $rawShipperAddr1
     */
    public function getRawShipperAddr1()
    {
        return $this->raw_shipper_addr1;
    }

    /**
     * Set rawShipperAddr2
     *
     * @param string $rawShipperAddr2
     * @return self
     */
    public function setRawShipperAddr2($rawShipperAddr2)
    {
        $this->raw_shipper_addr2 = $rawShipperAddr2;
        return $this;
    }

    /**
     * Get rawShipperAddr2
     *
     * @return string $rawShipperAddr2
     */
    public function getRawShipperAddr2()
    {
        return $this->raw_shipper_addr2;
    }

    /**
     * Set rawShipperAddr3
     *
     * @param string $rawShipperAddr3
     * @return self
     */
    public function setRawShipperAddr3($rawShipperAddr3)
    {
        $this->raw_shipper_addr3 = $rawShipperAddr3;
        return $this;
    }

    /**
     * Get rawShipperAddr3
     *
     * @return string $rawShipperAddr3
     */
    public function getRawShipperAddr3()
    {
        return $this->raw_shipper_addr3;
    }

    /**
     * Set rawShipperAddr4
     *
     * @param string $rawShipperAddr4
     * @return self
     */
    public function setRawShipperAddr4($rawShipperAddr4)
    {
        $this->raw_shipper_addr4 = $rawShipperAddr4;
        return $this;
    }

    /**
     * Get rawShipperAddr4
     *
     * @return string $rawShipperAddr4
     */
    public function getRawShipperAddr4()
    {
        return $this->raw_shipper_addr4;
    }

    /**
     * Set rawShipperAddrOthers
     *
     * @param string $rawShipperAddrOthers
     * @return self
     */
    public function setRawShipperAddrOthers($rawShipperAddrOthers)
    {
        $this->raw_shipper_addr_others = $rawShipperAddrOthers;
        return $this;
    }

    /**
     * Get rawShipperAddrOthers
     *
     * @return string $rawShipperAddrOthers
     */
    public function getRawShipperAddrOthers()
    {
        return $this->raw_shipper_addr_others;
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
     * Set rawConsigneeAddr1
     *
     * @param string $rawConsigneeAddr1
     * @return self
     */
    public function setRawConsigneeAddr1($rawConsigneeAddr1)
    {
        $this->raw_consignee_addr1 = $rawConsigneeAddr1;
        return $this;
    }

    /**
     * Get rawConsigneeAddr1
     *
     * @return string $rawConsigneeAddr1
     */
    public function getRawConsigneeAddr1()
    {
        return $this->raw_consignee_addr1;
    }

    /**
     * Set rawConsigneeAddr2
     *
     * @param string $rawConsigneeAddr2
     * @return self
     */
    public function setRawConsigneeAddr2($rawConsigneeAddr2)
    {
        $this->raw_consignee_addr2 = $rawConsigneeAddr2;
        return $this;
    }

    /**
     * Get rawConsigneeAddr2
     *
     * @return string $rawConsigneeAddr2
     */
    public function getRawConsigneeAddr2()
    {
        return $this->raw_consignee_addr2;
    }

    /**
     * Set rawConsigneeAddr3
     *
     * @param string $rawConsigneeAddr3
     * @return self
     */
    public function setRawConsigneeAddr3($rawConsigneeAddr3)
    {
        $this->raw_consignee_addr3 = $rawConsigneeAddr3;
        return $this;
    }

    /**
     * Get rawConsigneeAddr3
     *
     * @return string $rawConsigneeAddr3
     */
    public function getRawConsigneeAddr3()
    {
        return $this->raw_consignee_addr3;
    }

    /**
     * Set rawConsigneeAddr4
     *
     * @param string $rawConsigneeAddr4
     * @return self
     */
    public function setRawConsigneeAddr4($rawConsigneeAddr4)
    {
        $this->raw_consignee_addr4 = $rawConsigneeAddr4;
        return $this;
    }

    /**
     * Get rawConsigneeAddr4
     *
     * @return string $rawConsigneeAddr4
     */
    public function getRawConsigneeAddr4()
    {
        return $this->raw_consignee_addr4;
    }

    /**
     * Set rawConsigneeAddrOthers
     *
     * @param string $rawConsigneeAddrOthers
     * @return self
     */
    public function setRawConsigneeAddrOthers($rawConsigneeAddrOthers)
    {
        $this->raw_consignee_addr_others = $rawConsigneeAddrOthers;
        return $this;
    }

    /**
     * Get rawConsigneeAddrOthers
     *
     * @return string $rawConsigneeAddrOthers
     */
    public function getRawConsigneeAddrOthers()
    {
        return $this->raw_consignee_addr_others;
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
     * Set notifyPartyAddress
     *
     * @param string $notifyPartyAddress
     * @return self
     */
    public function setNotifyPartyAddress($notifyPartyAddress)
    {
        $this->notify_party_address = $notifyPartyAddress;
        return $this;
    }

    /**
     * Get notifyPartyAddress
     *
     * @return string $notifyPartyAddress
     */
    public function getNotifyPartyAddress()
    {
        return $this->notify_party_address;
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
     * Set rawNotifyPartyAddr1
     *
     * @param string $rawNotifyPartyAddr1
     * @return self
     */
    public function setRawNotifyPartyAddr1($rawNotifyPartyAddr1)
    {
        $this->raw_notify_party_addr1 = $rawNotifyPartyAddr1;
        return $this;
    }

    /**
     * Get rawNotifyPartyAddr1
     *
     * @return string $rawNotifyPartyAddr1
     */
    public function getRawNotifyPartyAddr1()
    {
        return $this->raw_notify_party_addr1;
    }

    /**
     * Set rawNotifyPartyAddr2
     *
     * @param string $rawNotifyPartyAddr2
     * @return self
     */
    public function setRawNotifyPartyAddr2($rawNotifyPartyAddr2)
    {
        $this->raw_notify_party_addr2 = $rawNotifyPartyAddr2;
        return $this;
    }

    /**
     * Get rawNotifyPartyAddr2
     *
     * @return string $rawNotifyPartyAddr2
     */
    public function getRawNotifyPartyAddr2()
    {
        return $this->raw_notify_party_addr2;
    }

    /**
     * Set rawNotifyPartyAddr3
     *
     * @param string $rawNotifyPartyAddr3
     * @return self
     */
    public function setRawNotifyPartyAddr3($rawNotifyPartyAddr3)
    {
        $this->raw_notify_party_addr3 = $rawNotifyPartyAddr3;
        return $this;
    }

    /**
     * Get rawNotifyPartyAddr3
     *
     * @return string $rawNotifyPartyAddr3
     */
    public function getRawNotifyPartyAddr3()
    {
        return $this->raw_notify_party_addr3;
    }

    /**
     * Set rawNotifyPartyAddr4
     *
     * @param string $rawNotifyPartyAddr4
     * @return self
     */
    public function setRawNotifyPartyAddr4($rawNotifyPartyAddr4)
    {
        $this->raw_notify_party_addr4 = $rawNotifyPartyAddr4;
        return $this;
    }

    /**
     * Get rawNotifyPartyAddr4
     *
     * @return string $rawNotifyPartyAddr4
     */
    public function getRawNotifyPartyAddr4()
    {
        return $this->raw_notify_party_addr4;
    }

    /**
     * Set rawNotifyPartyAddrOthers
     *
     * @param string $rawNotifyPartyAddrOthers
     * @return self
     */
    public function setRawNotifyPartyAddrOthers($rawNotifyPartyAddrOthers)
    {
        $this->raw_notify_party_addr_others = $rawNotifyPartyAddrOthers;
        return $this;
    }

    /**
     * Get rawNotifyPartyAddrOthers
     *
     * @return string $rawNotifyPartyAddrOthers
     */
    public function getRawNotifyPartyAddrOthers()
    {
        return $this->raw_notify_party_addr_others;
    }

    /**
     * Set productDesc
     *
     * @param string $productDesc
     * @return self
     */
    public function setProductDesc($productDesc)
    {
        $this->product_desc = $productDesc;
        return $this;
    }

    /**
     * Get productDesc
     *
     * @return string $productDesc
     */
    public function getProductDesc()
    {
        return $this->product_desc;
    }

    /**
     * Set marksAndNumbers
     *
     * @param string $marksAndNumbers
     * @return self
     */
    public function setMarksAndNumbers($marksAndNumbers)
    {
        $this->marks_and_numbers = $marksAndNumbers;
        return $this;
    }

    /**
     * Get marksAndNumbers
     *
     * @return string $marksAndNumbers
     */
    public function getMarksAndNumbers()
    {
        return $this->marks_and_numbers;
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
     * Set cif
     *
     * @param string $cif
     * @return self
     */
    public function setCif($cif)
    {
        $this->cif = $cif;
        return $this;
    }

    /**
     * Get cif
     *
     * @return string $cif
     */
    public function getCif()
    {
        return $this->cif;
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
     * Set indicatorOfTrueBuyer
     *
     * @param string $indicatorOfTrueBuyer
     * @return self
     */
    public function setIndicatorOfTrueBuyer($indicatorOfTrueBuyer)
    {
        $this->indicator_of_true_buyer = $indicatorOfTrueBuyer;
        return $this;
    }

    /**
     * Get indicatorOfTrueBuyer
     *
     * @return string $indicatorOfTrueBuyer
     */
    public function getIndicatorOfTrueBuyer()
    {
        return $this->indicator_of_true_buyer;
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
     * Set identityShipperName
     *
     * @param string $identityShipperName
     * @return self
     */
    public function setIdentityShipperName($identityShipperName)
    {
        $this->identity_shipper_name = $identityShipperName;
        return $this;
    }

    /**
     * Get identityShipperName
     *
     * @return string $identityShipperName
     */
    public function getIdentityShipperName()
    {
        return $this->identity_shipper_name;
    }

    /**
     * Set identityConsigneeName
     *
     * @param string $identityConsigneeName
     * @return self
     */
    public function setIdentityConsigneeName($identityConsigneeName)
    {
        $this->identity_consignee_name = $identityConsigneeName;
        return $this;
    }

    /**
     * Get identityConsigneeName
     *
     * @return string $identityConsigneeName
     */
    public function getIdentityConsigneeName()
    {
        return $this->identity_consignee_name;
    }
}
