<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Jariff\ProjectBundle\Util\Util;

/**
 * Jariff\MemberBundle\Entity\MemberARB
 *
 * @ORM\Table(name="member_arbs")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberARBRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberARB
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", options={"default" = 0})
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime", name="charge_at")
     */
    private $chargeAt;

    /**
     * @ORM\ManyToOne(targetEntity="MemberSubscription", inversedBy="member_arb")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member_subscription;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="arb")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

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
     * Set status
     *
     * @param integer $status
     * @return MemberARB
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return MemberARB
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set chargeAt
     *
     * @param \DateTime $chargeAt
     * @return MemberARB
     */
    public function setChargeAt($chargeAt)
    {
        $this->chargeAt = $chargeAt;
    
        return $this;
    }

    /**
     * Get chargeAt
     *
     * @return \DateTime 
     */
    public function getChargeAt()
    {
        return $this->chargeAt;
    }

    /**
     * Set member_subscription
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscription $memberSubscription
     * @return MemberARB
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
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberARB
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