<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="member_notify")
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\AdminRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberNotify
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
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="member_notify")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $readed;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $read_later;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $intervals;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $last_update;


    public function __construct()
    {
        $this->last_update = new \DateTime('now');

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
     * Set readed
     *
     * @param boolean $readed
     * @return MemberNotify
     */
    public function setReaded($readed)
    {
        $this->readed = $readed;
    
        return $this;
    }

    /**
     * Get readed
     *
     * @return boolean 
     */
    public function getReaded()
    {
        return $this->readed;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return MemberNotify
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set read_later
     *
     * @param boolean $readLater
     * @return MemberNotify
     */
    public function setReadLater($readLater)
    {
        $this->read_later = $readLater;
    
        return $this;
    }

    /**
     * Get read_later
     *
     * @return boolean 
     */
    public function getReadLater()
    {
        return $this->read_later;
    }

    /**
     * Set intervals
     *
     * @param integer $intervals
     * @return MemberNotify
     */
    public function setIntervals($intervals)
    {
        $this->intervals = $intervals;
    
        return $this;
    }

    /**
     * Get intervals
     *
     * @return integer 
     */
    public function getIntervals()
    {
        return $this->intervals;
    }

    /**
     * Set last_update
     *
     * @param \DateTime $lastUpdate
     * @return MemberNotify
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->last_update = $lastUpdate;
    
        return $this;
    }

    /**
     * Get last_update
     *
     * @return \DateTime 
     */
    public function getLastUpdate()
    {
        return $this->last_update;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberNotify
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