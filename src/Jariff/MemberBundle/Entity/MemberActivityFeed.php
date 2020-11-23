<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="member_activity_feed")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberActivityFeedRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberActivityFeed
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
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="export_tools")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $urls;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date_activity;


    public function __construct()
    {
        $this->date_activity = new \DateTime('now');

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
     * Set description
     *
     * @param string $description
     * @return MemberActivityFeed
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
     * Set urls
     *
     * @param string $urls
     * @return MemberActivityFeed
     */
    public function setUrls($urls)
    {
        $this->urls = $urls;
    
        return $this;
    }

    /**
     * Get urls
     *
     * @return string 
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * Set date_activity
     *
     * @param \DateTime $dateActivity
     * @return MemberActivityFeed
     */
    public function setDateActivity($dateActivity)
    {
        $this->date_activity = $dateActivity;
    
        return $this;
    }

    /**
     * Get date_activity
     *
     * @return \DateTime 
     */
    public function getDateActivity()
    {
        return $this->date_activity;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberActivityFeed
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