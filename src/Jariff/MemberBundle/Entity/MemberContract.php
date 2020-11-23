<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Jariff\MemberBundle\Entity\MemberContract
 *
 * @ORM\Table(name="member_contracts")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberContractRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberContract
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
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="contract")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Jariff\AdminBundle\Entity\Admin", inversedBy="contract")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $admin;

    /**
     * @ORM\Column(type="integer", name="tipe", nullable=true)
     */
    private $tipe;
    /**
     * @ORM\Column(type="string", name="title", nullable=true)
     */
    private $title;
    /**
     * @ORM\Column(type="string", name="result", nullable=true)
     */
    private $result;
    /**
     * @ORM\Column(type="string", name="doc_file_name", nullable=true)
     */
    private $docFileName;
    /**
     * @ORM\Column(type="string", name="doc_content_type", nullable=true)
     */
    private $docContentType;
    /**
     * @ORM\Column(type="integer", name="doc_file_size", nullable=true)
     */
    private $docFileSize;
    /**
     * @ORM\Column(type="datetime", name="doc_updated_at", nullable=true)
     */
    private $docUpdatedAt;
    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=true)
     */
    private $createdAt;
    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    private $updatedAt; 

    /**
     * @ORM\OneToOne(targetEntity="MemberSubscription", mappedBy="contract")
     * @ORM\JoinColumn(name="member_subscription_id", referencedColumnName="id")
     */
    protected $subscription;


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
     * Set tipe
     *
     * @param integer $tipe
     * @return MemberContract
     */
    public function setTipe($tipe)
    {
        $this->tipe = $tipe;
    
        return $this;
    }

    /**
     * Get tipe
     *
     * @return integer 
     */
    public function getTipe()
    {
        return $this->tipe;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return MemberContract
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set result
     *
     * @param string $result
     * @return MemberContract
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
     * Set docFileName
     *
     * @param string $docFileName
     * @return MemberContract
     */
    public function setDocFileName($docFileName)
    {
        $this->docFileName = $docFileName;
    
        return $this;
    }

    /**
     * Get docFileName
     *
     * @return string 
     */
    public function getDocFileName()
    {
        return $this->docFileName;
    }

    /**
     * Set docContentType
     *
     * @param string $docContentType
     * @return MemberContract
     */
    public function setDocContentType($docContentType)
    {
        $this->docContentType = $docContentType;
    
        return $this;
    }

    /**
     * Get docContentType
     *
     * @return string 
     */
    public function getDocContentType()
    {
        return $this->docContentType;
    }

    /**
     * Set docFileSize
     *
     * @param integer $docFileSize
     * @return MemberContract
     */
    public function setDocFileSize($docFileSize)
    {
        $this->docFileSize = $docFileSize;
    
        return $this;
    }

    /**
     * Get docFileSize
     *
     * @return integer 
     */
    public function getDocFileSize()
    {
        return $this->docFileSize;
    }

    /**
     * Set docUpdatedAt
     *
     * @param \DateTime $docUpdatedAt
     * @return MemberContract
     */
    public function setDocUpdatedAt($docUpdatedAt)
    {
        $this->docUpdatedAt = $docUpdatedAt;
    
        return $this;
    }

    /**
     * Get docUpdatedAt
     *
     * @return \DateTime 
     */
    public function getDocUpdatedAt()
    {
        return $this->docUpdatedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MemberContract
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return MemberContract
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
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberContract
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
     * Set admin
     *
     * @param \Jariff\AdminBundle\Entity\Admin $admin
     * @return MemberContract
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
     * Set subscription
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscription $subscription
     * @return MemberContract
     */
    public function setSubscription(\Jariff\MemberBundle\Entity\MemberSubscription $subscription = null)
    {
        $this->subscription = $subscription;
    
        return $this;
    }

    /**
     * Get subscription
     *
     * @return \Jariff\MemberBundle\Entity\MemberSubscription 
     */
    public function getSubscription()
    {
        return $this->subscription;
    }
}