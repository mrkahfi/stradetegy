<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="member_cancel_requests")
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\AdminRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberCancelRequest
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
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="member_cancel_request")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="MemberSubscription", inversedBy="member_cancel_request")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member_subscription;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $experience;

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     */
    private $date_created;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=false)
     */
    private $date_updated;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $reason;


    public function __construct()
    {
        $this->date_created = new \DateTime('now');
        $this->date_updated = new \DateTime('now');
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
     * Set experience
     *
     * @param string $experience
     * @return MemberCancelRequest
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    
        return $this;
    }

    /**
     * Get experience
     *
     * @return string 
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set date_created
     *
     * @param \DateTime $dateCreated
     * @return MemberCancelRequest
     */
    public function setDateCreated($dateCreated)
    {
        $this->date_created = $dateCreated;
    
        return $this;
    }

    /**
     * Get date_created
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return MemberCancelRequest
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    
        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberCancelRequest
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
     * Set member_subscription
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscription $memberSubscription
     * @return MemberCancelRequest
     */
    public function setMemberSubscription(\Jariff\MemberBundle\Entity\MemberSubscription $memberSubscription = null)
    {
        $this->member_subscription = $memberSubscription;
    
        return $this;
    }

    /**
     * Get member_subscription
     *
     * @return \Jariff\MemberBundle\Entity\MemberSubscription 
     */
    public function getMemberSubscription()
    {
        return $this->member_subscription;
    }

    /**
     * Set date_updated
     *
     * @param \DateTime $dateUpdated
     * @return MemberCancelRequest
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->date_updated = $dateUpdated;
    
        return $this;
    }

    /**
     * Get date_updated
     *
     * @return \DateTime 
     */
    public function getDateUpdated()
    {
        return $this->date_updated;
    }
}