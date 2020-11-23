<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Jariff\ProjectBundle\Util\Util as Util;

/**
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\CallTimeRepository")
 * @ORM\Table(name="call_time")
 * @ORM\HasLifecycleCallbacks
 */
class CallTime
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
     * @ORM\OneToMany(targetEntity="LeadContact", mappedBy="callTime", cascade={"all"})
     */
    protected $leadContact;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

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
     * @return CallTime
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
     * @return CallTime
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
     * Add leadContact
     *
     * @param \Jariff\AdminBundle\Entity\LeadContact $leadContact
     * @return CallTime
     */
    public function addLeadContact(\Jariff\AdminBundle\Entity\LeadContact $leadContact)
    {
        $this->leadContact[] = $leadContact;
    
        return $this;
    }

    /**
     * Remove leadContact
     *
     * @param \Jariff\AdminBundle\Entity\LeadContact $leadContact
     */
    public function removeLeadContact(\Jariff\AdminBundle\Entity\LeadContact $leadContact)
    {
        $this->leadContact->removeElement($leadContact);
    }

    /**
     * Get leadContact
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLeadContact()
    {
        return $this->leadContact;
    }
}