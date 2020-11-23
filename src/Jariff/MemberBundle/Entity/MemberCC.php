<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Jariff\MemberBundle\Entity\MemberCC
 *
 * @ORM\Table(name="member_ccs")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberCCRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberCC
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
    private $addedby;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", name="expired_at")
     */
    private $expired;

    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="cc", cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * @ORM\Column(type="string")
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $securityCode;

    /**
     * active
     * declined
     * invalid (declined twice / more)
     * 
     * @ORM\Column(type="integer", nullable=false, options={"default" = 0})
     */
    private $status;

    /**
     * @ORM\Column(type="string")
     */
    private $tipe;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string")
     */
    private $zipCode;
    

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
     * Set addedby
     *
     * @param string $addedby
     * @return MemberCC
     */
    public function setAddedby($addedby)
    {
        $this->addedby = $addedby;
    
        return $this;
    }

    /**
     * Get addedby
     *
     * @return string 
     */
    public function getAddedby()
    {
        return $this->addedby;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MemberCC
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set expired
     *
     * @param \DateTime $expired
     * @return MemberCC
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;
    
        return $this;
    }

    /**
     * Get expired
     *
     * @return \DateTime 
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return MemberCC
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return MemberCC
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return MemberCC
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set securityCode
     *
     * @param string $securityCode
     * @return MemberCC
     */
    public function setSecurityCode($securityCode)
    {
        $this->securityCode = $securityCode;
    
        return $this;
    }

    /**
     * Get securityCode
     *
     * @return string 
     */
    public function getSecurityCode()
    {
        return $this->securityCode;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return MemberCC
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
     * Set tipe
     *
     * @param string $tipe
     * @return MemberCC
     */
    public function setTipe($tipe)
    {
        $this->tipe = $tipe;
    
        return $this;
    }

    /**
     * Get tipe
     *
     * @return string 
     */
    public function getTipe()
    {
        return $this->tipe;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return MemberCC
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     * @return MemberCC
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    
        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string 
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberCC
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