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

/**
 * @MongoDB\Document(collection="tbp_export")
 *
 */
class ExporterCompanyTree {

    /** @MongoDB\Id */
    protected $id;

    /** @MongoDB\String */
    protected $slug;

    /** @MongoDB\String */
    protected $name;

    /** @MongoDB\String */
    protected $company_as;

    /** @MongoDB\Int */
    protected $shipment_count;

    /** @MongoDB\Collection */
    protected $child;

    
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
     * Set slug
     *
     * @param string $slug
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get slug
     *
     * @return string $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }


    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $company_as
     * @return self
     */
    public function setCompanyAs($companyAs)
    {
        $this->company_as = $companyAs;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $company_as
     */
    public function getCompanyAs()
    {
        return $this->company_as;
    }


    /**
     * Set shipmentCount
     *
     * @param int $shipmentCount
     * @return self
     */
    public function setShipmentsCount($shipmentsCount)
    {
        $this->shipment_count = $shipmentsCount;
        return $this;
    }

    /**
     * Get shipmentCount
     *
     * @return int $shipmentCount
     */
    public function getShipmentsCount()
    {
        return $this->shipment_count;
    }


    /**
     * Set child
     *
     * @param Collection $child
     * @return self
     */
    public function setChild($child)
    {
        $this->child = $child;
        return $this;
    }

    /**
     * Get child
     *
     * @return Collection $child
     */
    public function getChild()
    {
        return $this->child;
    }

}
