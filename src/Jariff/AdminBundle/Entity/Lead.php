<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Jariff\AdminBundle\Entity\BusinessType;
use Jariff\AdminBundle\Entity\Competitor;
use Jariff\AdminBundle\Entity\LeadSource;
use Jariff\ProjectBundle\Util\Util;

/**
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\LeadRepository")
 * @ORM\Table(name="leads")
 * @ORM\HasLifecycleCallbacks
 */
class Lead
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="LeadActivity", mappedBy="lead", cascade={"all"})
     */
    protected $activity;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $company;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $competitor;

    /**
     * @ORM\Column(type="datetime", name="converted_at", nullable=true)
     */
    protected $convertedAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $country;

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=true)
     */
    protected $dateCreate;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    protected $dateUpdate;

    /**
     * sekalian untuk :
     * notes,
     * product,
     * stageReason,
     * competitorStatus,
     * competitorDateEnd,
     * businessType,
     * dataInterest
     * dateReady
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * Red
     * Green
     * Orange
     * Blue
     * Yellow
     * Purple
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    protected $flag;

    /**
     * @ORM\OneToMany(targetEntity="LeadHistory", mappedBy="lead", cascade={"all"})
     */
    protected $history;

    /**
     * @ORM\OneToMany(targetEntity="Inbound", mappedBy="lead", cascade={"all"})
     */
    protected $inbound;

    /**
     * @ORM\OneToOne(targetEntity="\Jariff\MemberBundle\Entity\Member", mappedBy="lead")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;

    /**
     * @ORM\Column(type="string", nullable=true)
     */    
    protected $name;

    /**
     * lead should be initiated by 
     * Employee Name
     * System -> registered as root admin
     * 
     * @ORM\ManyToOne(targetEntity="Admin", inversedBy="leadOwned", cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $owner;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $queue;

    /**
     * @ORM\OneToMany(targetEntity="LeadSales", mappedBy="lead", cascade={"all"})
     */
    protected $sales;

    /**
     * juga untuk campaign, 
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    protected $source;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $status;

    /**
     * @ORM\Column(type="integer", name="time_lapsed", nullable=true)
     */
    protected $timeLapsed;

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

    /**
     * semua fungsi yg ada di atas fungsi __construct ini sudah di edit / buat sendiri,
     * yg aman untuk di remove adalah fungsi-fungsi setelah __construct
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
     * Set company
     *
     * @param string $company
     * @return Lead
     */
    public function setCompany($company)
    {
        $this->company = $company;
    
        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Lead
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
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
     * Set email
     *
     * @param string $email
     * @return Lead
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
     * Set name
     *
     * @param string $name
     * @return Lead
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
     * Set phone
     *
     * @param string $phone
     * @return Lead
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
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
     * Set queue
     *
     * @param string $queue
     * @return Lead
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
     * Set competitor
     *
     * @param string $competitor
     * @return Lead
     */
    public function setCompetitor($competitor)
    {
        $this->competitor = $competitor;
    
        return $this;
    }

    /**
     * Get competitor
     *
     * @return string 
     */
    public function getCompetitor()
    {
        return $this->competitor;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Lead
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
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * @return Lead
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
     * @return Lead
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
     * Set flag
     *
     * @param string $flag
     * @return Lead
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
     * Set source
     *
     * @param string $source
     * @return Lead
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
     * Set status
     *
     * @param string $status
     * @return Lead
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
     * Set timeLapsed
     *
     * @param integer $timeLapsed
     * @return Lead
     */
    public function setTimeLapsed($timeLapsed)
    {
        $this->timeLapsed = $timeLapsed;
    
        return $this;
    }

    /**
     * Get timeLapsed
     *
     * @return integer 
     */
    public function getTimeLapsed()
    {
        return $this->timeLapsed;
    }

    /**
     * Add activity
     *
     * @param \Jariff\AdminBundle\Entity\LeadActivity $activity
     * @return Lead
     */
    public function addActivity(\Jariff\AdminBundle\Entity\LeadActivity $activity)
    {
        $this->activity[] = $activity;
    
        return $this;
    }

    /**
     * Remove activity
     *
     * @param \Jariff\AdminBundle\Entity\LeadActivity $activity
     */
    public function removeActivity(\Jariff\AdminBundle\Entity\LeadActivity $activity)
    {
        $this->activity->removeElement($activity);
    }

    /**
     * Get activity
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Add history
     *
     * @param \Jariff\AdminBundle\Entity\LeadHistory $history
     * @return Lead
     */
    public function addHistory(\Jariff\AdminBundle\Entity\LeadHistory $history)
    {
        $this->history[] = $history;
    
        return $this;
    }

    /**
     * Remove history
     *
     * @param \Jariff\AdminBundle\Entity\LeadHistory $history
     */
    public function removeHistory(\Jariff\AdminBundle\Entity\LeadHistory $history)
    {
        $this->history->removeElement($history);
    }

    /**
     * Get history
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Add inbound
     *
     * @param \Jariff\AdminBundle\Entity\Inbound $inbound
     * @return Lead
     */
    public function addInbound(\Jariff\AdminBundle\Entity\Inbound $inbound)
    {
        $this->inbound[] = $inbound;
    
        return $this;
    }

    /**
     * Remove inbound
     *
     * @param \Jariff\AdminBundle\Entity\Inbound $inbound
     */
    public function removeInbound(\Jariff\AdminBundle\Entity\Inbound $inbound)
    {
        $this->inbound->removeElement($inbound);
    }

    /**
     * Get inbound
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInbound()
    {
        return $this->inbound;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return Lead
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
     * Set owner
     *
     * @param \Jariff\AdminBundle\Entity\Admin $owner
     * @return Lead
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
     * Add sales
     *
     * @param \Jariff\AdminBundle\Entity\LeadSales $sales
     * @return Lead
     */
    public function addSale(\Jariff\AdminBundle\Entity\LeadSales $sales)
    {
        $this->sales[] = $sales;
    
        return $this;
    }

    /**
     * Remove sales
     *
     * @param \Jariff\AdminBundle\Entity\LeadSales $sales
     */
    public function removeSale(\Jariff\AdminBundle\Entity\LeadSales $sales)
    {
        $this->sales->removeElement($sales);
    }

    /**
     * Get sales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * Set convertedAt
     *
     * @param \DateTime $convertedAt
     * @return Lead
     */
    public function setConvertedAt($convertedAt)
    {
        $this->convertedAt = $convertedAt;
    
        return $this;
    }

    /**
     * Get convertedAt
     *
     * @return \DateTime 
     */
    public function getConvertedAt()
    {
        return $this->convertedAt;
    }
}