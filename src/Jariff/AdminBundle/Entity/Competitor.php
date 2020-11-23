<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Jariff\ProjectBundle\Util\Util as Util;

/**
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\CompetitorRepository")
 * @ORM\Table(name="competitor")
 * @ORM\HasLifecycleCallbacks
 */
class Competitor
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
     * @ORM\Column(type="integer")
     */
    protected $alexa;

    /**
     * @ORM\Column(type="integer")
     */
    protected $count;

    /**
     * @ORM\OneToMany(targetEntity="Lead", mappedBy="competitor", cascade={"all"})
     */
    protected $lead;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $website;

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
        $this->alexa   = 0;
        $this->count   = 0;
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
     * Set alexa
     *
     * @param integer $alexa
     * @return Competitor
     */
    public function setAlexa($alexa)
    {
        $this->alexa = $alexa;
    
        return $this;
    }

    /**
     * Get alexa
     *
     * @return integer 
     */
    public function getAlexa()
    {
        return $this->alexa;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Competitor
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
     * Set name
     *
     * @param string $name
     * @return Competitor
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
     * Set website
     *
     * @param string $website
     * @return Competitor
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Competitor
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
     * @return Competitor
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