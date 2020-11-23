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
 * Jariff\MemberBundle\Entity\Invoice
 *
 * @ORM\Table(name="invoice")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\InvoiceRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Invoice
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * copied from member profile
     * 
     * @ORM\Column(type="string")
     */
    private $billToName;

    /**
     * copied from member profile
     * 
     * @ORM\Column(type="string")
     */
    private $billToAdress;

    /**
     * copied from member profile
     * 
     * @ORM\Column(type="string")
     */
    private $billToEmail;

    /**
     * copied from member profile
     * 
     * @ORM\Column(type="string")
     */
    private $billToPhone;
    
    /**
     * tanggal invoice ini dibuat
     * kalau datePaid adalah tanggal invoice ini dibayar,
     * diambil dari payment
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="invoice")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * @ORM\Column(type="string")
     */
    private $number;

    /**
     * @ORM\OneToOne(targetEntity="Payment", inversedBy="invoice")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $payment;

    /**
     * @ORM\ManyToOne(targetEntity="\Jariff\AdminBundle\Entity\Admin", inversedBy="invoice")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL")
     */
    private $sales;

    /**
     * @ORM\ManyToOne(targetEntity="MemberSubscription", inversedBy="invoice")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $subscription;

    /**
     * paid => Paid
     * outstanding => Outstanding
     * 
     * @ORM\Column(type="string")
     */
    private $type;

    public function setPayment(\Jariff\MemberBundle\Entity\Payment $payment = null)
    {
        // kode set disini dihilangkan karena bisa looping
        // if (is_null($payment->getInvoice())) {
        //     $payment->setInvoice($this);
        // }
        $this->payment = $payment;
    
        return $this;
    }

    public function __construct()
    {
        $this->transactionId = Util::token('member-history');
        $this->type          = 'paid';
        $this->date          = new \DateTime();
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
     * Set amount
     *
     * @param integer $amount
     * @return Invoice
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
     * Set date
     *
     * @param \DateTime $date
     * @return Invoice
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
     * @return Invoice
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
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return Invoice
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
     * Set billToName
     *
     * @param string $billToName
     * @return Invoice
     */
    public function setBillToName($billToName)
    {
        $this->billToName = $billToName;
    
        return $this;
    }

    /**
     * Get billToName
     *
     * @return string 
     */
    public function getBillToName()
    {
        return $this->billToName;
    }

    /**
     * Set billToAdress
     *
     * @param string $billToAdress
     * @return Invoice
     */
    public function setBillToAdress($billToAdress)
    {
        $this->billToAdress = $billToAdress;
    
        return $this;
    }

    /**
     * Get billToAdress
     *
     * @return string 
     */
    public function getBillToAdress()
    {
        return $this->billToAdress;
    }

    /**
     * Set billToEmail
     *
     * @param string $billToEmail
     * @return Invoice
     */
    public function setBillToEmail($billToEmail)
    {
        $this->billToEmail = $billToEmail;
    
        return $this;
    }

    /**
     * Get billToEmail
     *
     * @return string 
     */
    public function getBillToEmail()
    {
        return $this->billToEmail;
    }

    /**
     * Set billToPhone
     *
     * @param string $billToPhone
     * @return Invoice
     */
    public function setBillToPhone($billToPhone)
    {
        $this->billToPhone = $billToPhone;
    
        return $this;
    }

    /**
     * Get billToPhone
     *
     * @return string 
     */
    public function getBillToPhone()
    {
        return $this->billToPhone;
    }

    /**
     * Set datePaid
     *
     * @param \DateTime $datePaid
     * @return Invoice
     */
    public function setDatePaid($datePaid)
    {
        $this->datePaid = $datePaid;
    
        return $this;
    }

    /**
     * Get datePaid
     *
     * @return \DateTime 
     */
    public function getDatePaid()
    {
        return $this->datePaid;
    }

    /**
     * Set dateIssued
     *
     * @param \DateTime $dateIssued
     * @return Invoice
     */
    public function setDateIssued($dateIssued)
    {
        $this->dateIssued = $dateIssued;
    
        return $this;
    }

    /**
     * Get dateIssued
     *
     * @return \DateTime 
     */
    public function getDateIssued()
    {
        return $this->dateIssued;
    }

    /**
     * Set subscription
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscription $subscription
     * @return Invoice
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

    /**
     * Get payment
     *
     * @return \Jariff\MemberBundle\Entity\Payment 
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set sales
     *
     * @param \Jariff\AdminBundle\Entity\Admin $sales
     * @return Invoice
     */
    public function setSales(\Jariff\AdminBundle\Entity\Admin $sales = null)
    {
        $this->sales = $sales;
    
        return $this;
    }

    /**
     * Get sales
     *
     * @return \Jariff\AdminBundle\Entity\Admin 
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return Invoice
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
     * Set type
     *
     * @param string $type
     * @return Invoice
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
}