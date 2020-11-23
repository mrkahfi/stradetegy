<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 6/24/13
 * Time: 10:31 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="import_document")
 */
class ImportDocument {

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Int
     */
    protected $ig_id;

    /**
     * @MongoDB\String
     */
    protected $consignee_name;

    /**
     * @MongoDB\String
     */
    protected $consignee_address;


    /**
     * @MongoDB\String
     */
    protected $notify_name;

    /**
     * @MongoDB\String
     */
    protected $notify_address;

    /**
     * @MongoDB\String
     */
    protected $shipper_name;

    /**
     * @MongoDB\String
     */
    protected $shipper_address;

    /**
     * @MongoDB\String
     */
    protected $container_number;

    /**
     * @MongoDB\String
     */
    protected $product_description;
    
    /**
     * Spanish
     * @MongoDB\String
     */
    protected $product_description_es;

    /**
     * French
     * @MongoDB\String
     */
    protected $product_description_fr;

    /**
     * Arabic
     * @MongoDB\String
     */
    protected $product_description_ar;

    /**
     * Chinese
     * @MongoDB\String
     */
    protected $product_description_cn;

    /**
     * Russian
     * @MongoDB\String
     */
    protected $product_description_ru;

    /**
     * @MongoDB\String
     */
    protected $carrier;

    /**
     * @MongoDB\String
     */
    protected $ship_registered_in;

    /**
     * @MongoDB\String
     */
    protected $vessel;

    /**
     * @MongoDB\String
     */
    protected $voyage;

    /**
     * @MongoDB\String
     */
    protected $us_port;

    /**
     * @MongoDB\String
     */
    protected $foreign_port;

    /**
     * @MongoDB\String
     */
    protected $country_of_origin;

    /**
     * @MongoDB\String
     */
    protected $place_of_receipt;

    /**
     * @MongoDB\String
     */
    protected $bill_of_lading;

    /**
     * @MongoDB\Date
     */
    protected $arrival_date;

    /**
     * @MongoDB\Int
     */
    protected $quantity;

    /**
     * @MongoDB\String
     */
    protected $container_count;

    /**
     * @MongoDB\Int
     */
    protected $weight;

    /**
     * @MongoDB\String
     */
    protected $cbm;

    /**
     * @MongoDB\String
     */
    protected $house_vs_master;

    /**
     * @MongoDB\String
     */
    protected $other_info;

    /**
     * digunakan untuk pencarian
     */
    protected $q;


    /** @MongoDB\String */
    private $website;

    /** @MongoDB\String */
    private $phone;

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
     * Set ig_id
     *
     * @param int $igId
     * @return self
     */
    public function setIgId($igId)
    {
        $this->ig_id = $igId;
        return $this;
    }

    /**
     * Get ig_id
     *
     * @return int $igId
     */
    public function getIgId()
    {
        return $this->ig_id;
    }

    /**
     * Set consignee_name
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
     * Get consignee_name
     *
     * @return string $consigneeName
     */
    public function getConsigneeName()
    {
        return $this->consignee_name;
    }

    /**
     * Set consignee_address
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
     * Get consignee_address
     *
     * @return string $consigneeAddress
     */
    public function getConsigneeAddress()
    {
        return $this->consignee_address;
    }

    /**
     * Set notify_name
     *
     * @param string $notifyName
     * @return self
     */
    public function setNotifyName($notifyName)
    {
        $this->notify_name = $notifyName;
        return $this;
    }

    /**
     * Get notify_name
     *
     * @return string $notifyName
     */
    public function getNotifyName()
    {
        return $this->notify_name;
    }

    /**
     * Set notify_address
     *
     * @param string $notifyAddress
     * @return self
     */
    public function setNotifyAddress($notifyAddress)
    {
        $this->notify_address = $notifyAddress;
        return $this;
    }

    /**
     * Get notify_address
     *
     * @return string $notifyAddress
     */
    public function getNotifyAddress()
    {
        return $this->notify_address;
    }

    /**
     * Set shipper_name
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
     * Get shipper_name
     *
     * @return string $shipperName
     */
    public function getShipperName()
    {
        return $this->shipper_name;
    }

    /**
     * Set shipper_address
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
     * Get shipper_address
     *
     * @return string $shipperAddress
     */
    public function getShipperAddress()
    {
        return $this->shipper_address;
    }

    /**
     * Set container_number
     *
     * @param string $containerNumber
     * @return self
     */
    public function setContainerNumber($containerNumber)
    {
        $this->container_number = $containerNumber;
        return $this;
    }

    /**
     * Get container_number
     *
     * @return string $containerNumber
     */
    public function getContainerNumber()
    {
        return $this->container_number;
    }

    /**
     * Set product_description
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
     * Get product_description
     *
     * @return string $productDescription
     */
    public function getProductDescription()
    {
        return $this->product_description;
    }

    /**
     * Set carrier
     *
     * @param string $carrier
     * @return self
     */
    public function setCarrier($carrier)
    {
        $this->carrier = $carrier;
        return $this;
    }

    /**
     * Get carrier
     *
     * @return string $carrier
     */
    public function getCarrier()
    {
        return $this->carrier;
    }

    /**
     * Set ship_registered_in
     *
     * @param string $shipRegisteredIn
     * @return self
     */
    public function setShipRegisteredIn($shipRegisteredIn)
    {
        $this->ship_registered_in = $shipRegisteredIn;
        return $this;
    }

    /**
     * Get ship_registered_in
     *
     * @return string $shipRegisteredIn
     */
    public function getShipRegisteredIn()
    {
        return $this->ship_registered_in;
    }

    /**
     * Set vessel
     *
     * @param string $vessel
     * @return self
     */
    public function setVessel($vessel)
    {
        $this->vessel = $vessel;
        return $this;
    }

    /**
     * Get vessel
     *
     * @return string $vessel
     */
    public function getVessel()
    {
        return $this->vessel;
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
     * Set us_port
     *
     * @param string $usPort
     * @return self
     */
    public function setUsPort($usPort)
    {
        $this->us_port = $usPort;
        return $this;
    }

    /**
     * Get us_port
     *
     * @return string $usPort
     */
    public function getUsPort()
    {
        return $this->us_port;
    }

    /**
     * Set foreign_port
     *
     * @param string $foreignPort
     * @return self
     */
    public function setForeignPort($foreignPort)
    {
        $this->foreign_port = $foreignPort;
        return $this;
    }

    /**
     * Get foreign_port
     *
     * @return string $foreignPort
     */
    public function getForeignPort()
    {
        return $this->foreign_port;
    }

    /**
     * Set country_of_origin
     *
     * @param string $countryOfOrigin
     * @return self
     */
    public function setCountryOfOrigin($countryOfOrigin)
    {
        $this->country_of_origin = $countryOfOrigin;
        return $this;
    }

    /**
     * Get country_of_origin
     *
     * @return string $countryOfOrigin
     */
    public function getCountryOfOrigin()
    {
        return $this->country_of_origin;
    }

    /**
     * Set place_of_receipt
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
     * Get place_of_receipt
     *
     * @return string $placeOfReceipt
     */
    public function getPlaceOfReceipt()
    {
        return $this->place_of_receipt;
    }

    /**
     * Set bill_of_lading
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
     * Get bill_of_lading
     *
     * @return string $billOfLading
     */
    public function getBillOfLading()
    {
        return $this->bill_of_lading;
    }

    /**
     * Set arrival_date
     *
     * @param date $arrivalDate
     * @return self
     */
    public function setArrivalDate($arrivalDate)
    {
        $this->arrival_date = $arrivalDate;
        return $this;
    }

    /**
     * Get arrival_date
     *
     * @return date $arrivalDate
     */
    public function getArrivalDate()
    {
        return $this->arrival_date;
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
     * Set container_count
     *
     * @param string $containerCount
     * @return self
     */
    public function setContainerCount($containerCount)
    {
        $this->container_count = $containerCount;
        return $this;
    }

    /**
     * Get container_count
     *
     * @return string $containerCount
     */
    public function getContainerCount()
    {
        return $this->container_count;
    }

    /**
     * Set weight
     *
     * @param int $weight
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
     * @return int $weight
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set cbm
     *
     * @param string $cbm
     * @return self
     */
    public function setCbm($cbm)
    {
        $this->cbm = $cbm;
        return $this;
    }

    /**
     * Get cbm
     *
     * @return string $cbm
     */
    public function getCbm()
    {
        return $this->cbm;
    }

    /**
     * Set house_vs_master
     *
     * @param string $houseVsMaster
     * @return self
     */
    public function setHouseVsMaster($houseVsMaster)
    {
        $this->house_vs_master = $houseVsMaster;
        return $this;
    }

    /**
     * Get house_vs_master
     *
     * @return string $houseVsMaster
     */
    public function getHouseVsMaster()
    {
        return $this->house_vs_master;
    }

    /**
     * Set other_info
     *
     * @param string $otherInfo
     * @return self
     */
    public function setOtherInfo($otherInfo)
    {
        $this->other_info = $otherInfo;
        return $this;
    }

    /**
     * Get other_info
     *
     * @return string $otherInfo
     */
    public function getOtherInfo()
    {
        return $this->other_info;
    }

    public function setQ($q){
        $this->q = $q;
        return $this;
    }

    public function getQ(){
        return $this->q;
    }

    

    /**
     * Set website
     *
     * @param string $website
     * @return self
     */
    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * Get website
     *
     * @return string $website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return self
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Get phone
     *
     * @return string $phone
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     * Set productDescriptionEs
     *
     * @param string $productDescriptionEs
     * @return self
     */
    public function setProductDescriptionEs($productDescriptionEs)
    {
        $this->product_description_es = $productDescriptionEs;
        return $this;
    }

    /**
     * Get productDescriptionEs
     *
     * @return string $productDescriptionEs
     */
    public function getProductDescriptionEs()
    {
        return $this->product_description_es;
    }

    /**
     * Set productDescriptionFr
     *
     * @param string $productDescriptionFr
     * @return self
     */
    public function setProductDescriptionFr($productDescriptionFr)
    {
        $this->product_description_fr = $productDescriptionFr;
        return $this;
    }

    /**
     * Get productDescriptionFr
     *
     * @return string $productDescriptionFr
     */
    public function getProductDescriptionFr()
    {
        return $this->product_description_fr;
    }

    /**
     * Set productDescriptionAr
     *
     * @param string $productDescriptionAr
     * @return self
     */
    public function setProductDescriptionAr($productDescriptionAr)
    {
        $this->product_description_ar = $productDescriptionAr;
        return $this;
    }

    /**
     * Get productDescriptionAr
     *
     * @return string $productDescriptionAr
     */
    public function getProductDescriptionAr()
    {
        return $this->product_description_ar;
    }

    /**
     * Set productDescriptionCn
     *
     * @param string $productDescriptionCn
     * @return self
     */
    public function setProductDescriptionCn($productDescriptionCn)
    {
        $this->product_description_cn = $productDescriptionCn;
        return $this;
    }

    /**
     * Get productDescriptionCn
     *
     * @return string $productDescriptionCn
     */
    public function getProductDescriptionCn()
    {
        return $this->product_description_cn;
    }

    /**
     * Set productDescriptionRu
     *
     * @param string $productDescriptionRu
     * @return self
     */
    public function setProductDescriptionRu($productDescriptionRu)
    {
        $this->product_description_ru = $productDescriptionRu;
        return $this;
    }

    /**
     * Get productDescriptionRu
     *
     * @return string $productDescriptionRu
     */
    public function getProductDescriptionRu()
    {
        return $this->product_description_ru;
    }
}
