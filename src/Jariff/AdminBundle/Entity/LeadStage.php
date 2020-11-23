<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\LeadStageRepository")
 * @ORM\Table(name="lead_stage")
 * @ORM\HasLifecycleCallbacks
 */
class LeadStage
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
     * @ORM\OneToMany(targetEntity="Lead", mappedBy="stage", cascade={"all"})
     */
    protected $lead;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * nilai yg digunakan dalam perbandingan algoritma,
     * biasanya dari lowercase property name
     * 
     * @ORM\Column(type="string", unique=true, length=10)
     */
    protected $value;

    /**
     * @ ORM\PrePersist()
     */
    public function prePersist()
    {        
    }

    /**
     * @ ORM\PreUpdate()
     */
    public function preUpdate()
    {
    }

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     * @return LeadStage
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return LeadStage
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Add lead
     *
     * @param \Jariff\AdminBundle\Entity\Lead $lead
     * @return LeadStage
     */
    public function addLead(\Jariff\AdminBundle\Entity\Lead $lead)
    {
        $this->lead[] = $lead;
    
        return $this;
    }

    /**
     * Remove lead
     *
     * @param \Jariff\AdminBundle\Entity\Lead $lead
     */
    public function removeLead(\Jariff\AdminBundle\Entity\Lead $lead)
    {
        $this->lead->removeElement($lead);
    }

    /**
     * Get lead
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLead()
    {
        return $this->lead;
    }
}