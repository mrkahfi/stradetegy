<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Jariff\ProjectBundle\Util\Util;

/**
 * Jariff\AdminBundle\Entity\LeadHistory
 *
 * @ORM\Table(name="lead_history")
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\LeadHistoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class LeadHistory
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Jariff\AdminBundle\Entity\Admin", inversedBy="leadHistory")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $admin;

    /**
     * @ORM\Column(name="_column", type="string")
     */
    private $column;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Lead", inversedBy="history")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $lead;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $newValue;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $oldValue;

    /**
     * @ORM\Column(name="_table", type="string")
     */
    private $table;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {        
        $this->date = new \DateTime();

        if ( $this->oldValue instanceof \DateTime) {
            $this->oldValue = $this->oldValue->format('Y-m-d H:i:s'); 
        }
        if ( $this->newValue instanceof \DateTime) {
            $this->newValue = $this->newValue->format('Y-m-d H:i:s'); 
        }
        if (empty($this->description)) {
            $this->description = $this->admin->getName().
                ' update lead '.
                $this->lead->getNumber().
                ' on '.
                $this->column.
                ' from "'.
                (string)$this->oldValue.
                '" become "'.
                (string)$this->newValue.
                '"';
        }
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
     * Set column
     *
     * @param string $column
     * @return LeadHistory
     */
    public function setColumn($column)
    {
        $this->column = $column;
    
        return $this;
    }

    /**
     * Get column
     *
     * @return string 
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return LeadHistory
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return LeadHistory
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
     * Set newValue
     *
     * @param string $newValue
     * @return LeadHistory
     */
    public function setNewValue($newValue)
    {
        $this->newValue = $newValue;
    
        return $this;
    }

    /**
     * Get newValue
     *
     * @return string 
     */
    public function getNewValue()
    {
        return $this->newValue;
    }

    /**
     * Set oldValue
     *
     * @param string $oldValue
     * @return LeadHistory
     */
    public function setOldValue($oldValue)
    {
        $this->oldValue = $oldValue;
    
        return $this;
    }

    /**
     * Get oldValue
     *
     * @return string 
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * Set table
     *
     * @param string $table
     * @return LeadHistory
     */
    public function setTable($table)
    {
        $this->table = $table;
    
        return $this;
    }

    /**
     * Get table
     *
     * @return string 
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set admin
     *
     * @param \Jariff\AdminBundle\Entity\Admin $admin
     * @return LeadHistory
     */
    public function setAdmin(\Jariff\AdminBundle\Entity\Admin $admin = null)
    {
        $this->admin = $admin;
    
        return $this;
    }

    /**
     * Get admin
     *
     * @return \Jariff\AdminBundle\Entity\Admin 
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set lead
     *
     * @param \Jariff\AdminBundle\Entity\Lead $lead
     * @return LeadHistory
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
}