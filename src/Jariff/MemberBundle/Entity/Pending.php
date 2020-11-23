<?php
/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Jariff\MemberBundle\Validator as MemberAssert;
use Jariff\ProjectBundle\Validator as ProjectAssert;
use Jariff\ProjectBundle\Util\Util;

/**
 * Jariff\MemberBundle\Entity\Pending
 * @MemberAssert\Pending(groups={"registration"})
 * @ORM\Table(name="pending")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\PendingRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Pending
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
     * @ORM\Column(type="boolean")
     */
    private $bigPicture;

    /**
     * mastercards
     * visa
     * american express
     * discover
     * 
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ccType;
    
    /**
     * @ORM\Column(type="string", length=120)
     * @Assert\NotBlank()
     */
    private $companyName;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     */
    private $zipToBill;

    /**
     * true / false
     * kalau akun nya ikut custom plan, maka setiap action harus di cek quotanya,
     * di pending entity ini perlu dibuat property everythingPlan dan customPlan
     * untuk memastikan user memilih salah satu plan saat signup
     * 
     * @ORM\Column(type="boolean")
     */
    private $customPlan;

    /**
     * @ORM\Column(type="integer")
     */
    private $discount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRegistration;

    /**
     * 0  => 'disabled',
     * 30 => '      1 000 = $30',
     * 40 => '      5 000 = $40',
     * 60 => '     25 000 = $60',
     * 70 => '    100 000 = $70',
     * 80 => 'unlimited = $80',
     * 
     * @ORM\Column(type="integer")
     */
    private $download;

    /**
     * @ORM\Column(type="string", length=120)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * true / false
     * kalau akun nya ikut everything plan, maka setiap action tidak usah di cek quotanya,
     * tetapi tetap di log buat report
     * kalau false, maka getPlan()
     * 
     * @ORM\Column(type="boolean")
     */
    private $everythingPlan;

    /**
     * credit card date expired
     * 
     * @ORM\Column(type="date", nullable=true)
     */
    private $expired;

    /**
     * @ORM\Column(type="string", length=12)
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @ORM\Column(type="integer")
     */
    private $history;

    /**
     * @ORM\Column(type="string", length=120)
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @ORM\OneToOne(targetEntity="Member", inversedBy="pending")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * 12 = 20%
     * 6 = 15%
     * 3 = 10%
     * 
     * @ORM\Column(type="integer")
     */
    private $month;

    /**
     * credit card number
     * 
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=120)
     * @Assert\NotBlank()
     */
    private $password;

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
     * pif = Paid in full (Pay Up Front)
     * mtm = Month to Month (Automated Recurring Billing)
     * @ORM\Column(type="string", length=3)
     */
    private $paymentTerm;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank()
     */
    private $phone;

    /**
     * Mr. Mrs. Ms.
     * 
     * @ORM\Column(type="string", length=4)
     * @Assert\NotBlank()
     */
    private $salutation;

    /**
     * @ORM\Column(type="integer")
     */
    private $search;

    /**
     * credit card secure code
     * 
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $secureCode;

    /**
     * @ORM\Column(type="string", unique=true, length=64)
     * @Assert\NotBlank()
     */
    private $token;

    public function getTotalPrice(){

        $price = 0;
        if ($this->getEverythingPlan()) {
            $price = 200;
        } else if ($this->getCustomPlan()) {
            $price = $this->getHistory() + $this->getSearch() + $this->getDownload() + $this->getBigPicture();
        }

        // calculate discount based on pif / mtm
        $total_discount = 0;
        if ($this->getPaymentTerm() == 'mtm' ) {
            $total_discount = 0;
        } else if($this->getPaymentTerm() == 'pif') {
            $total_discount = round($price * ($this->getMonth() / 100) * $this->getMonthValue());
        }

        // update total price
        $total_payment = $price * $this->getMonthValue() - $total_discount - $this->getDiscount();

        return $total_payment;
    }   

    public function getBigPictureValue()
    {
        switch ($this->bigPicture) {
            case 0  : return false;
            case 30 : return true;
            default : throw new \Exception('Undefined big picture value');     
        }
    }

    /**
     * @return int
     */
    public function getDownloadValue()
    {
        switch ($this->download) {
            case 0  : return 0;
            case 30 : return 1000;
            case 40 : return 5000;
            case 60 : return 25000;
            case 70 : return 100000;
            case 80 : return 1000000; //unlimited  
            default : throw new \Exception('Undefined download value');     
        }
    }
    /**
     * @return int
     */
    public function getSearchValue()
    {
        switch ($this->search) {
            case 0  : return 5;
            case 10 : return 25;
            case 20 : return 50;
            case 35 : return 1000000; //unlimited
            default : throw new \Exception('Undefined search value');
        }
    }

    /**
     * @return int
     */
    public function getHistoryValue()
    {
        switch ($this->history) {
            case 59 : return 18;
            case 99 : return 36;
            case 15 : return 60;
            default : throw new \Exception('Undefined history value');
        }
    }

    /**
     * berapa bulan user yg membayar dengan pif memilih paket nya ?
     * hal ini terjadi karena ketidakmampuan sf2 mendefinisikan custom attribute untuk option element
     * @return int
     */
    public function getPaymentTermString()
    {
        switch ($this->paymentTerm) {
            case 'pif' : return 'Paid in Full';
            case 'mtm' : return 'Month to month';
            default    : throw new \Exception('Undefined payment term value');
        }
    }
    
    public function __construct(){
        $this->dateRegistration = new \DateTime();
        $this->activated        = false;
        $this->discount         = 0;
        $this->token            = Util::token('pending');
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
     * Set email
     *
     * @param string $email
     * @return Pending
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set salutation
     *
     * @param string $salutation
     * @return Pending
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
    
        return $this;
    }

    /**
     * Get salutation
     *
     * @return string 
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Pending
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
     * @return Pending
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
     * Set password
     *
     * @param string $password
     * @return Pending
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     * @return Pending
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    
        return $this;
    }

    /**
     * Get companyName
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Pending
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

        /**
     * Set zipToBill
     *
     * @param string $zipToBill
     * @return Pending
     */
    public function setZipToBill($zipToBill)
    {
        $this->zipToBill = $zipToBill;
    
        return $this;
    }

    /**
     * Get zipToBill
     *
     * @return string 
     */
    public function getZipToBill()
    {
        return $this->zipToBill;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Pending
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set dateRegistration
     *
     * @param \DateTime $dateRegistration
     * @return Pending
     */
    public function setDateRegistration($dateRegistration)
    {
        $this->dateRegistration = $dateRegistration;
    
        return $this;
    }

    /**
     * Get dateRegistration
     *
     * @return \DateTime 
     */
    public function getDateRegistration()
    {
        return $this->dateRegistration;
    }

    /**
     * Set payment
     *
     * @param string $payment
     * @return Pending
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

    /**
     * Set number
     *
     * @param string $number
     * @return Pending
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
     * Set expired
     *
     * @param \DateTime $expired
     * @return Pending
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
     * Set secureCode
     *
     * @param string $secureCode
     * @return Pending
     */
    public function setSecureCode($secureCode)
    {
        $this->secureCode = $secureCode;
    
        return $this;
    }

    /**
     * Get secureCode
     *
     * @return string 
     */
    public function getSecureCode()
    {
        return $this->secureCode;
    }

    /**
     * Set everythingPlan
     *
     * @param boolean $everythingPlan
     * @return Pending
     */
    public function setEverythingPlan($everythingPlan)
    {
        if ($everythingPlan == 'true') {
            $this->everythingPlan = true;
        } else {
            $this->everythingPlan = false;
        }
        return $this;
    }

    /**
     * Get everythingPlan
     *
     * @return boolean 
     */
    public function getEverythingPlan()
    {
        if ($this->everythingPlan === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set history
     *
     * @param integer $history
     * @return Pending
     */
    public function setHistory($history)
    {
        $this->history = $history;
    
        return $this;
    }

    /**
     * Get history
     *
     * @return integer 
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Set search
     *
     * @param integer $search
     * @return Pending
     */
    public function setSearch($search)
    {
        $this->search = $search;
    
        return $this;
    }

    /**
     * Get search
     *
     * @return integer 
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set download
     *
     * @param integer $download
     * @return Pending
     */
    public function setDownload($download)
    {
        $this->download = $download;
    
        return $this;
    }

    /**
     * Get download
     *
     * @return integer 
     */
    public function getDownload()
    {
        return $this->download;
    }

    /**
     * Set bigPicture
     *
     * @param boolean $bigPicture
     * @return Pending
     */
    public function setBigPicture($bigPicture)
    {
        $this->bigPicture = $bigPicture;
    
        return $this;
    }

    /**
     * Get bigPicture
     *
     * @return boolean 
     */
    public function getBigPicture()
    {
        return $this->bigPicture;
    }

    /* *
     * Set quarterlyFeedback
     *
     * @param boolean $quarterlyFeedback
     * @return Pending
    
    public function setQuarterlyFeedback($quarterlyFeedback)
    {
        $this->quarterlyFeedback = $quarterlyFeedback;
    
        return $this;
    }
    */
    /* *
     * Get quarterlyFeedback
     *
     * @return boolean 
    
    public function getQuarterlyFeedback()
    {
        return $this->quarterlyFeedback;
    } */

    /**
     * Set month
     *
     * @param integer $month
     * @return Pending
     */
    public function setMonth($month)
    {
        $this->month = $month;
    
        return $this;
    }

    /**
     * Get month
     *
     * @return integer 
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set customPlan
     *
     * @param boolean $customPlan
     * @return Pending
     */
    public function setCustomPlan($customPlan)
    {
        if ($customPlan == 'true') {
            $this->customPlan = true;
        } else {
            $this->customPlan = false;
        }
        return $this;
    }

    /**
     * Get customPlan
     *
     * @return boolean 
     */
    public function getCustomPlan()
    {
        if ($this->customPlan === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set paymentTerm
     *
     * @param string $paymentTerm
     * @return Pending
     */
    public function setPaymentTerm($paymentTerm)
    {
        $this->paymentTerm = $paymentTerm;
    
        return $this;
    }

    /**
     * Get paymentTerm
     *
     * @return string 
     */
    public function getPaymentTerm()
    {
        return $this->paymentTerm;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Pending
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set ccType
     *
     * @param string $ccType
     * @return Pending
     */
    public function setCcType($ccType)
    {
        $this->ccType = $ccType;
    
        return $this;
    }

    /**
     * Get ccType
     *
     * @return string 
     */
    public function getCcType()
    {
        return $this->ccType;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     * @return Pending
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    
        return $this;
    }

    /**
     * Get discount
     *
     * @return integer 
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return Pending
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