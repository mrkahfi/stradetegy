<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\LeadActivityRepository")
 * @ORM\Table(name="lead_activity")
 * @ORM\HasLifecycleCallbacks
 */
class LeadActivity
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
     * @ORM\ManyToOne(targetEntity="Admin", inversedBy="activityAssigned")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $assigned;

    /**
     * tanggal ditandai sebagai selesai
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateCompleted;

    /**
     * in CST
     * waktu entity / data dimasukkan / dibuat ke dalam db
     * 
     * @ORM\Column(type="datetime")
     */
    protected $dateCreate;

    /**
     * untuk scheduled activity harus ada schedule nya
     * warning user kalau sudah mau mendekati
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateScheduled;

    /**
     * tanggal activity ini dikerjakan, kadang tidak sama dengan datetime saat activity ini dibuat
     * Task - not time sensitive - date only
     * Events - time sensitive - editable date and time
     * 
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $dateTask;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateUpdate;

    /**
     * activity description,
     * complete url history before signup
     * search term used,
     * first page / landing page
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * true = event 
     * otherwise = task
     * 
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $event;

    /**
     * @ORM\ManyToOne(targetEntity="Lead", inversedBy="activity")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $lead;

    /**
     * lead can be initiated by 
     * System
     * Employee Name
     * 
     * @ORM\ManyToOne(targetEntity="Admin", inversedBy="activityOwned")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $owner;

    /**
     * low 
     * medium
     * high
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    protected $priority;

    /**
     * No Answer, Bad Phone, Bad Email, Technical Issue, etc
     * 
     * @ORM\ManyToOne(targetEntity="LeadActivityResult", inversedBy="activity", cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $result;

    /**
     * scheduled
     * complete
     * 
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * Internal, Call, Email, New Inquiry, etc
     * 
     * @ORM\ManyToOne(targetEntity="LeadActivityType", inversedBy="activity", cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $type;

    public function setEvent()
    {
        $this->event = true;
    
        return $this;
    }
    public function setTask()
    {
        $this->event = null;
    
        return $this;
    }
    public function getEvent()
    {
        return $this->event;
    }
    public function getTask()
    {
        return !$this->event;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {        
        $this->dateCreate = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->dateUpdate = new \DateTime();
    }

    public function __construct()
    {
        $this->priority = 'low';
        $this->event    = false;
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
     * Set dateCompleted
     *
     * @param \DateTime $dateCompleted
     * @return LeadActivity
     */
    public function setDateCompleted($dateCompleted)
    {
        $this->dateCompleted = $dateCompleted;
    
        return $this;
    }

    /**
     * Get dateCompleted
     *
     * @return \DateTime 
     */
    public function getDateCompleted()
    {
        return $this->dateCompleted;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return LeadActivity
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
     * Set dateScheduled
     *
     * @param \DateTime $dateScheduled
     * @return LeadActivity
     */
    public function setDateScheduled($dateScheduled)
    {
        $this->dateScheduled = $dateScheduled;
    
        return $this;
    }

    /**
     * Get dateScheduled
     *
     * @return \DateTime 
     */
    public function getDateScheduled()
    {
        return $this->dateScheduled;
    }

    /**
     * Set dateTask
     *
     * @param \DateTime $dateTask
     * @return LeadActivity
     */
    public function setDateTask($dateTask)
    {
        $this->dateTask = $dateTask;
    
        return $this;
    }

    /**
     * Get dateTask
     *
     * @return \DateTime 
     */
    public function getDateTask()
    {
        return $this->dateTask;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * @return LeadActivity
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;
    
        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime 
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return LeadActivity
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
     * Set priority
     *
     * @param string $priority
     * @return LeadActivity
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    
        return $this;
    }

    /**
     * Get priority
     *
     * @return string 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return LeadActivity
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set assigned
     *
     * @param \Jariff\AdminBundle\Entity\Admin $assigned
     * @return LeadActivity
     */
    public function setAssigned(\Jariff\AdminBundle\Entity\Admin $assigned = null)
    {
        $this->assigned = $assigned;
    
        return $this;
    }

    /**
     * Get assigned
     *
     * @return \Jariff\AdminBundle\Entity\Admin 
     */
    public function getAssigned()
    {
        return $this->assigned;
    }

    /**
     * Set lead
     *
     * @param \Jariff\AdminBundle\Entity\Lead $lead
     * @return LeadActivity
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
     * Set owner
     *
     * @param \Jariff\AdminBundle\Entity\Admin $owner
     * @return LeadActivity
     */
    public function setOwner(\Jariff\AdminBundle\Entity\Admin $owner = null)
    {
        $this->owner = $owner;
    
        return $this;
    }

    /**
     * Get owner
     *
     * @return \Jariff\AdminBundle\Entity\Admin 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set result
     *
     * @param \Jariff\AdminBundle\Entity\LeadActivityResult $result
     * @return LeadActivity
     */
    public function setResult(\Jariff\AdminBundle\Entity\LeadActivityResult $result = null)
    {
        $this->result = $result;
    
        return $this;
    }

    /**
     * Get result
     *
     * @return \Jariff\AdminBundle\Entity\LeadActivityResult 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set type
     *
     * @param \Jariff\AdminBundle\Entity\LeadActivityType $type
     * @return LeadActivity
     */
    public function setType(\Jariff\AdminBundle\Entity\LeadActivityType $type = null)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return \Jariff\AdminBundle\Entity\LeadActivityType 
     */
    public function getType()
    {
        return $this->type;
    }
}