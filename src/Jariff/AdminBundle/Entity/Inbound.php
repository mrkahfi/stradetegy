<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\InboundRepository")
 * @ORM\Table(name="inbounds")
 * @ORM\HasLifecycleCallbacks
 * @GRID\Source(columns="id, business, country, dateCreate, description, email, flag, ipAddress, name, phone, queue, source, status")
 */
class Inbound
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
     * @ORM\Column(type="string", name="company", nullable=true)
     */
    protected $business;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $country;

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=true)
     */
    protected $dateCreate;

    /**
     * @ORM\Column(type="datetime", name="date_finish", nullable=true)
     */
    protected $dateFinish;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    protected $dateUpdate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * RED - [Support] customer service
     * BLUE - [Lead] existing lead
     * GREEN  - [Bookkeeping] billing issue or inquiry
     * 
     * @ORM\Column(type="string", nullable=true))
     */
    protected $flag;

    /**
     * Please capture IP addresses 
     * 
     * @ORM\Column(type="string", name="ip_address", nullable=true)
     */
    protected $ipAddress;

    /**
     * @ORM\ManyToOne(targetEntity="Lead", inversedBy="inbound")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $lead;

    /**
     * @ORM\ManyToOne(targetEntity="\Jariff\MemberBundle\Entity\Member", inversedBy="inbound")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $member;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * Billing
     * Sales
     * Sales Pot
     * Customer Service
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    protected $queue;

    /**
     * emails
     * Contact Forms
     * Demo Requests 
     * Incomplete sign ups
     * Suspended user tried to sign in (expired PIF [paid in full] client)
     * Canceled user tried to sign in
     * Privacy Requests
     * Bounce back
     *     
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    protected $source;

    /**
     * qualified -> answered
     * spam
     *
     * status answered berarti sebelumnya sudah qualified, 
     * atau kalau langsung answered berarti otomatis qualified
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    protected $status;

    /**
     * Please attach a label that shows time lapsing from time of receipt - 
     * once sent to appropriate Queue - 
     * stop time lapse as Time Lapsed: 00:05:43 and keep Inbound label attached
     *
     * if is_null dateFinish then
     *     timeLapsed = now - dateStart
     * else
     *     timeLapsed = dateFinish - dateStart
     * 
     * @ORM\Column(type="integer", name="time_lapsed", nullable=true)
     */
    protected $timeLapsed;

    /**
     * Please capture URL/pages viewed on our site
     * 
     * @ORM\Column(type="text", name="visited_page", nullable=true)
     */
    protected $visitedPage;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersistPreUpdate()
    {
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

    public function setPhone($phone)
    {
        if (empty(trim($phone))) {
            return $this;
        }
        $this->phone = $phone;
    
        return $this;
    }
    
    public function setBusiness($business)
    {
        if (empty(trim($business))) {
            return $this;
        }
        $this->business = $business;
    
        return $this;
    }

    public function setName($name)
    {
        if (empty(trim($name))) {
            return $this;
        }
        $this->name = $name;
    
        return $this;
    }

    public function setCountry($country)
    {
        if (empty(trim($country))) {
            return $this;
        }
        $this->country = $country;
    
        return $this;
    }

    public function setIpAddress($ipAddress)
    {
        if (empty(trim($ipAddress))) {
            return $this;
        }
        $this->ipAddress = $ipAddress;
    
        return $this;
    }

    public function setVisitedPage($visitedPage)
    {
        if (empty(trim($visitedPage))) {
            return $this;
        }
        $this->visitedPage = $visitedPage;
    
        return $this;
    }

    /**
     */
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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Inbound
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
     * Set dateFinish
     *
     * @param \DateTime $dateFinish
     * @return Inbound
     */
    public function setDateFinish($dateFinish)
    {
        $this->dateFinish = $dateFinish;
    
        return $this;
    }

    /**
     * Get dateFinish
     *
     * @return \DateTime 
     */
    public function getDateFinish()
    {
        return $this->dateFinish;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * @return Inbound
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
     * @return Inbound
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
     * Set email
     *
     * @param string $email
     * @return Inbound
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set flag
     *
     * @param string $flag
     * @return Inbound
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    
        return $this;
    }

    /**
     * Get flag
     *
     * @return string 
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Get ipAddress
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set queue
     *
     * @param string $queue
     * @return Inbound
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;
    
        return $this;
    }

    /**
     * Get queue
     *
     * @return string 
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return Inbound
     */
    public function setSource($source)
    {
        $this->source = $source;
    
        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set timeLapsed
     *
     * @param string $timeLapsed
     * @return Inbound
     */
    public function setTimeLapsed($timeLapsed)
    {
        $this->timeLapsed = $timeLapsed;
    
        return $this;
    }

    /**
     * Get timeLapsed
     *
     * @return string 
     */
    public function getTimeLapsed()
    {
        return $this->timeLapsed;
    }

    /**
     * Get visitedPage
     *
     * @return string 
     */
    public function getVisitedPage()
    {
        return $this->visitedPage;
    }

    /**
     * Set lead
     *
     * @param \Jariff\AdminBundle\Entity\Lead $lead
     * @return Inbound
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
     * Get business
     *
     * @return string 
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
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
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return Inbound
     */
    public function setMember(\Jariff\MemberBundle\Entity\Member $member = null)
    {
        $this->member = $member;
    
        return $this;
    }

    /**
     * Get member
     *
     * @return \Jariff\MemberBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Inbound
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
}