<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Jariff\ProjectBundle\Util\Util as Util;

/**
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\BusinessTypeRepository")
 * @ORM\Table(name="business_type")
 * @ORM\HasLifecycleCallbacks
 */
class BusinessType
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
     * @ORM\OneToMany(targetEntity="Lead", mappedBy="businessType", cascade={"all"})
     */
    protected $lead;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $count;

    /**
     * nilai yg digunakan dalam perbandingan algoritma,
     * biasanya dari lowercase property name
     * 
     * @ORM\Column(type="string", unique=true, length=20)
     */
    protected $value;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->value = Util::slugify($this->name);
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
        $this->count = 0;
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
     * @return BusinessType
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
     * Set count
     *
     * @param integer $count
     * @return BusinessType
     */
    public function setCount($count)
    {
        $this->count = $count;
    
        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return BusinessType
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
     * @return BusinessType
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