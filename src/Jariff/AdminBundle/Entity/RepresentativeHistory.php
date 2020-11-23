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
 * Jariff\AdminBundle\Entity\RepresentativeHistory
 *
 * @ORM\Table(name="representative_history")
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\RepresentativeHistoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class RepresentativeHistory
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Admin", inversedBy="representative")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL")
     */
    private $admin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="\Jariff\MemberBundle\Entity\Member", inversedBy="history")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL")
     */
    private $member;

    /**
     * @ORM\Column(type="string")
     */
    private $result;

    /**
     * @ORM\Column(type="string")
     */
    private $subscription;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $status;

    /**
     * 
     * Manual Upgrade
     * Manual Downgrade
     * Logged Completed Activity
     * Scheduled New Task Or Event
     * Updated Contact Info
     * Processed Cancel Request
     * Performed Training
     * Reset Password
     * Added Users
     *
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {        
        $this->date = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
    }

    public function setTypeVal($type)
    {
        switch ($type) {         
            case 1: $this->type = 'Manual Upgrade'; break;
            case 2: $this->type = 'Manual Downgrade'; break;
            case 3: $this->type = 'Logged Completed Activity'; break;
            case 4: $this->type = 'Scheduled New Task Or Event'; break;
            case 5: $this->type = 'Updated Contact Info'; break;
            case 6: $this->type = 'Processed Cancel Request'; break;
            case 7: $this->type = 'Performed Training'; break;
            case 8: $this->type = 'Reset Password'; break;
            case 9: $this->type = 'Added Users'; break;
            case 10: $this->type = 'New Subscription'; break;
            default: throw new \Exception('Invalid type value'); break;
        }
    
        return $this;
    }

    public function __construct()
    {
        $this->result = 'Success';
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
     * Set date
     *
     * @param \DateTime $date
     * @return RepresentativeHistory
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
     * @return RepresentativeHistory
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
     * Set result
     *
     * @param string $result
     * @return RepresentativeHistory
     */
    public function setResult($result)
    {
        $this->result = $result;
    
        return $this;
    }

    /**
     * Get result
     *
     * @return string 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set subscription
     *
     * @param string $subscription
     * @return RepresentativeHistory
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;
    
        return $this;
    }

    /**
     * Get subscription
     *
     * @return string 
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return RepresentativeHistory
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
     * Set type
     *
     * @param string $type
     * @return RepresentativeHistory
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set admin
     *
     * @param \Jariff\AdminBundle\Entity\Admin $admin
     * @return RepresentativeHistory
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
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return RepresentativeHistory
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
}