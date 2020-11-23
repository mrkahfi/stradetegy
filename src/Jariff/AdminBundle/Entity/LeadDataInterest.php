<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Jariff\AdminBundle\Entity\DataInterest;

/**
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\LeadDataInterestRepository")
 * @ORM\Table(name="lead_data_interest")
 * @ORM\HasLifecycleCallbacks
 */
class LeadDataInterest
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
     * @ORM\ManyToOne(targetEntity="Lead", inversedBy="dataInterest")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $lead;

    /**
     * @ORM\ManyToOne(targetEntity="DataInterest", inversedBy="lead")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $dataInterest;
    protected $dataInterestOther;

    public function setDataInterestOther($dataInterestOther)
    {
        $this->dataInterestOther = $dataInterestOther;
        return $this;
    }

    public function getDataInterestOther()
    {
        return $this->dataInterestOther;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersistPreUpdate()
    {
        if (is_null($this->dataInterest) && !empty($this->dataInterestOther)) {
            $dataInterest = new DataInterest();
            $dataInterest->setName($this->dataInterestOther);
            $this->setDataInterest($dataInterest);
        }
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {        
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
    }

    public function __construct()
    {
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
     * Set lead
     *
     * @param \Jariff\AdminBundle\Entity\Lead $lead
     * @return LeadDataInterest
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
     * Set dataInterest
     *
     * @param \Jariff\AdminBundle\Entity\DataInterest $dataInterest
     * @return LeadDataInterest
     */
    public function setDataInterest(\Jariff\AdminBundle\Entity\DataInterest $dataInterest = null)
    {
        $this->dataInterest = $dataInterest;
    
        return $this;
    }

    /**
     * Get dataInterest
     *
     * @return \Jariff\AdminBundle\Entity\DataInterest 
     */
    public function getDataInterest()
    {
        return $this->dataInterest;
    }
}