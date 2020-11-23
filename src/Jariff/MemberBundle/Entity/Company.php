<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Jariff\MemberBundle\Entity\Download
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\CompanyRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Company
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * setiap company akan memiliki akun manager yg akan turun tangan langsung untuk masalah2 kritis
     * 
     * @ORM\ManyToOne(targetEntity="\Jariff\AdminBundle\Entity\Admin", inversedBy="company", cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL")
     */
    private $accountManager;

    /**
     * nama penerima tagihan
     * 
     * @ORM\Column(type="string")
     */
    private $billToName;

    /**
     * alamat penerima tagihan
     * 
     * @ORM\Column(type="string")
     */
    private $billToAdress;

    /**
     * email penerima tagihan
     * 
     * @ORM\Column(type="string")
     */
    private $billToEmail;

    /**
     * phone number penerima tagihan
     * 
     * @ORM\Column(type="string")
     */
    private $billToPhone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreate;

    /**
     * outstanding = belum dibayar, invoice ini sebagai surat tagihan kepada bendahara
     * paid = sudah dibayar, invoice ini sebagai bukti pembayaran kepada bendahara
     * 
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank()
     */
    private $invoiceType;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Member", mappedBy="company", cascade={"all"})
     */
    private $member;

    /**
     * cc
     * bankwire
     * paypal
     * check
     * 
     * @ORM\Column(type="string", length=8)
     * @Assert\NotBlank()
     */
    private $payment;

    /**
     * @ORM\Column(type="string")
     */
    private $shipToName;

    /**
     * @ORM\Column(type="string")
     */
    private $shipToAdress;

    /**
     * @ORM\Column(type="string")
     */
    private $shipToEmail;

    /**
     * @ORM\Column(type="string")
     */
    private $shipToPhone;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $member  = $this->member->first();
        // var_dump($member);
        $profile = $member->getProfile();
        $name    = $profile->getFirstName() + " " + $profile->getLastName();
        $address = $profile->getCountry() + " " + $profile->getState();
        $email   = $member->getEmail();
        $phone   = $profile->getPhone();

        // bill to data
        if(empty($this->billToName))
            $this->billToName = $name;
        if(empty($this->billToAdress))
            $this->billToAdress = $address;
        if(empty($this->billToEmail))
            $this->billToEmail = $email;
        if(empty($this->billToPhone))
            $this->billToPhone = $phone;

        // ship to data
        if(empty($this->shipToName))
            $this->shipToName = $name;
        if(empty($this->shipToAdress))
            $this->shipToAdress = $address;
        if(empty($this->shipToEmail))
            $this->shipToEmail = $email;
        if(empty($this->shipToPhone))
            $this->shipToPhone = $phone;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->invoiceType = 'paid';
        $this->dateCreate  = new \DateTime();
        $this->member      = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set billToName
     *
     * @param string $billToName
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Company
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
     * Set invoiceType
     *
     * @param string $invoiceType
     * @return Company
     */
    public function setInvoiceType($invoiceType)
    {
        $this->invoiceType = $invoiceType;
    
        return $this;
    }

    /**
     * Get invoiceType
     *
     * @return string 
     */
    public function getInvoiceType()
    {
        return $this->invoiceType;
    }

    /**
     * Set shipToName
     *
     * @param string $shipToName
     * @return Company
     */
    public function setShipToName($shipToName)
    {
        $this->shipToName = $shipToName;
    
        return $this;
    }

    /**
     * Get shipToName
     *
     * @return string 
     */
    public function getShipToName()
    {
        return $this->shipToName;
    }

    /**
     * Set shipToAdress
     *
     * @param string $shipToAdress
     * @return Company
     */
    public function setShipToAdress($shipToAdress)
    {
        $this->shipToAdress = $shipToAdress;
    
        return $this;
    }

    /**
     * Get shipToAdress
     *
     * @return string 
     */
    public function getShipToAdress()
    {
        return $this->shipToAdress;
    }

    /**
     * Set shipToEmail
     *
     * @param string $shipToEmail
     * @return Company
     */
    public function setShipToEmail($shipToEmail)
    {
        $this->shipToEmail = $shipToEmail;
    
        return $this;
    }

    /**
     * Get shipToEmail
     *
     * @return string 
     */
    public function getShipToEmail()
    {
        return $this->shipToEmail;
    }

    /**
     * Set shipToPhone
     *
     * @param string $shipToPhone
     * @return Company
     */
    public function setShipToPhone($shipToPhone)
    {
        $this->shipToPhone = $shipToPhone;
    
        return $this;
    }

    /**
     * Get shipToPhone
     *
     * @return string 
     */
    public function getShipToPhone()
    {
        return $this->shipToPhone;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Company
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
     * Set accountManager
     *
     * @param \Jariff\AdminBundle\Entity\Admin $accountManager
     * @return Company
     */
    public function setAccountManager(\Jariff\AdminBundle\Entity\Admin $accountManager = null)
    {
        $this->accountManager = $accountManager;
    
        return $this;
    }

    /**
     * Get accountManager
     *
     * @return \Jariff\AdminBundle\Entity\Admin 
     */
    public function getAccountManager()
    {
        return $this->accountManager;
    }

    /**
     * Add member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return Company
     */
    public function addMember(\Jariff\MemberBundle\Entity\Member $member)
    {
        $this->member[] = $member;
    
        return $this;
    }

    /**
     * Remove member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     */
    public function removeMember(\Jariff\MemberBundle\Entity\Member $member)
    {
        $this->member->removeElement($member);
    }

    /**
     * Get member
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set payment
     *
     * @param string $payment
     * @return Company
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    
        return $this;
    }

    /**
     * Get payment
     *
     * @return string 
     */
    public function getPayment()
    {
        return $this->payment;
    }
}