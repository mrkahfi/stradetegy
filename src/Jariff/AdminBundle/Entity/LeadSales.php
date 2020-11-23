<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\LeadSalesRepository")
 * @ORM\Table(name="lead_sales")
 * @ORM\HasLifecycleCallbacks
 */
class LeadSales
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $dateCreate;

    /**
     * @ORM\ManyToOne(targetEntity="Lead", inversedBy="sales")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $lead;

    /**
     * @ORM\ManyToOne(targetEntity="Admin", inversedBy="leads")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $sales;

    /**
     * @ORM\Column(name="_primary", type="boolean", nullable=true)
     */
    protected $primary;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {        
        $this->dateCreate = new \DateTime();
    }

    /**
     * @ ORM\PreUpdate()
     */
    public function preUpdate()
    {
    }

    public function __construct()
    {
        $this->primary = false;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return LeadSales
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;
    
        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime 
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set primary
     *
     * @param boolean $primary
     * @return LeadSales
     */
    public function setPrimary($primary)
    {
        $this->primary = $primary;
    
        return $this;
    }

    /**
     * Get primary
     *
     * @return boolean 
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * Set lead
     *
     * @param \Jariff\AdminBundle\Entity\Lead $lead
     * @return LeadSales
     */
    public function setLead(\Jariff\AdminBundle\Entity\Lead $lead = null)
    {
        $this->lead = $lead;
    
        return $this;
    }

    /**
     * Get lead
     *
     * @return \Jariff\AdminBundle\Entity\Lead 
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * Set sales
     *
     * @param \Jariff\AdminBundle\Entity\Admin $sales
     * @return LeadSales
     */
    public function setSales(\Jariff\AdminBundle\Entity\Admin $sales = null)
    {
        $this->sales = $sales;
    
        return $this;
    }

    /**
     * Get sales
     *
     * @return \Jariff\AdminBundle\Entity\Admin 
     */
    public function getSales()
    {
        return $this->sales;
    }
}