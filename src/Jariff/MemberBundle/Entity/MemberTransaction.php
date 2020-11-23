<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Jariff\MemberBundle\Entity\MemberTransaction
 *
 * @ORM\Table(name="member_transactions")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberTransactionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberTransaction
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
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="transaction")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * @ORM\Column(type="string", name="addedby", nullable=true)
     */
    private $addedby;

    /**
     * @ORM\Column(type="integer", name="amount", nullable=true)
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime", name="received_at", nullable=true)
     */
    private $receivedAt;

    /**
     * @ORM\Column(type="datetime", name="start_at", nullable=true)
     */
    private $startAt;

    /**
     * @ORM\Column(type="datetime", name="end_at", nullable=true)
     */
    private $endAt;

    /**
     * @ORM\Column(type="string", name="tipe", nullable=true)
     */
    private $tipe;

    /**
     * @ORM\Column(type="string", name="card_number", nullable=true)
     */
    private $cardNumber;

    /**
     * @ORM\Column(type="string", name="reference_number", nullable=true)
     */
    private $referenceNumber;

    /**
     * @ORM\Column(type="string", name="result", nullable=true)
     */
    private $result;

    /**
     * @ORM\Column(type="string", name="advice", nullable=true)
     */
    private $advice;

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    private $updatedAt;

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
     * Set addedby
     *
     * @param string $addedby
     * @return MemberTransaction
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
     * Set amount
     *
     * @param integer $amount
     * @return MemberTransaction
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
     * Set receivedAt
     *
     * @param \DateTime $receivedAt
     * @return MemberTransaction
     */
    public function setReceivedAt($receivedAt)
    {
        $this->receivedAt = $receivedAt;
    
        return $this;
    }

    /**
     * Get receivedAt
     *
     * @return \DateTime 
     */
    public function getReceivedAt()
    {
        return $this->receivedAt;
    }

    /**
     * Set startAt
     *
     * @param \DateTime $startAt
     * @return MemberTransaction
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    
        return $this;
    }

    /**
     * Get startAt
     *
     * @return \DateTime 
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set endAt
     *
     * @param \DateTime $endAt
     * @return MemberTransaction
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
    
        return $this;
    }

    /**
     * Get endAt
     *
     * @return \DateTime 
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * Set tipe
     *
     * @param string $tipe
     * @return MemberTransaction
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
     * Set cardNumber
     *
     * @param string $cardNumber
     * @return MemberTransaction
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
    
        return $this;
    }

    /**
     * Get cardNumber
     *
     * @return string 
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Set referenceNumber
     *
     * @param string $referenceNumber
     * @return MemberTransaction
     */
    public function setReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;
    
        return $this;
    }

    /**
     * Get referenceNumber
     *
     * @return string 
     */
    public function getReferenceNumber()
    {
        return $this->referenceNumber;
    }

    /**
     * Set result
     *
     * @param string $result
     * @return MemberTransaction
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
     * Set advice
     *
     * @param string $advice
     * @return MemberTransaction
     */
    public function setAdvice($advice)
    {
        $this->advice = $advice;
    
        return $this;
    }

    /**
     * Get advice
     *
     * @return string 
     */
    public function getAdvice()
    {
        return $this->advice;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MemberTransaction
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
     * @return MemberTransaction
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
     * Set docFileName
     *
     * @param string $docFileName
     * @return MemberTransaction
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
     * @return MemberTransaction
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
     * @return MemberTransaction
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
     * @return MemberTransaction
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
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberTransaction
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
     * Set subscription
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscription $subscription
     * @return MemberTransaction
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