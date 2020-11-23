<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Jariff\ProjectBundle\Util as Util;

/**
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\FlagRepository")
 * @ORM\Table(name="flag")
 * @ORM\HasLifecycleCallbacks
 */
class Flag
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
     * @ORM\Column(type="string")
     */
    protected $color;

    /**
     * @ORM\Column(type="integer")
     */
    protected $count;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     */
    protected $icon;

    /**
     * @ORM\OneToMany(targetEntity="Lead", mappedBy="flag", cascade={"all"})
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
     * Set color
     *
     * @param string $color
     * @return Flag
     */
    public function setColor($color)
    {
        $this->color = $color;
    
        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Flag
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
     * Set description
     *
     * @param string $description
     * @return Flag
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return Flag
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    
        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Flag
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
     * @return Flag
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
     * @return Flag
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