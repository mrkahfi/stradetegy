<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Jariff\AdminBundle\Entity\CallTime;

/**
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\LeadContactRepository")
 * @ORM\Table(name="lead_contact")
 * @ORM\HasLifecycleCallbacks
 */
class LeadContact
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
     * client timezone,
     * 
     * PST 
     * MST
     * CST 
     * EST 
     * Other (empty text field)
     * 
     * @ORM\ManyToOne(targetEntity="CallTime", inversedBy="leadContact", cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $callTime;
    protected $callTimeOther;

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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $country;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $dateCreate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateUpdate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $dontCall;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $dontEmail;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $decisionMaker;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $expired;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $facebook;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ip;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $jobTitle;

    /**
     * Preferred language
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    protected $language;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\ManyToOne(targetEntity="Lead", inversedBy="contact")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $lead;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $linkedin;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $number;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $payment;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $paypal;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * Mr. Mrs. Ms.
     * 
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    protected $salutation;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $secureCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $skype;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $street;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $twitter;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $website;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersistPreUpdate()
    {        
        if (is_null($this->callTime) && !empty($this->callTimeOther)) {
            $callTime = new CallTime();
            $callTime->setName($this->callTimeOther);
            $this->setCallTime($callTime);
        }
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {        
        $this->dateCreate = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->dateUpdate = new \DateTime();
    }

    public function setCallTimeOther($callTimeOther)
    {
        $this->callTimeOther = $callTimeOther;
    
        return $this;
    }

    public function getCallTimeOther()
    {
        return $this->callTimeOther;
    }

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
     * Set country
     *
     * @param string $country
     * @return LeadContact
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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return LeadContact
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
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * @return LeadContact
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;
    
        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime 
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set dontCall
     *
     * @param boolean $dontCall
     * @return LeadContact
     */
    public function setDontCall($dontCall)
    {
        $this->dontCall = $dontCall;
    
        return $this;
    }

    /**
     * Get dontCall
     *
     * @return boolean 
     */
    public function getDontCall()
    {
        return $this->dontCall;
    }

    /**
     * Set dontEmail
     *
     * @param boolean $dontEmail
     * @return LeadContact
     */
    public function setDontEmail($dontEmail)
    {
        $this->dontEmail = $dontEmail;
    
        return $this;
    }

    /**
     * Get dontEmail
     *
     * @return boolean 
     */
    public function getDontEmail()
    {
        return $this->dontEmail;
    }

    /**
     * Set decisionMaker
     *
     * @param boolean $decisionMaker
     * @return LeadContact
     */
    public function setDecisionMaker($decisionMaker)
    {
        $this->decisionMaker = $decisionMaker;
    
        return $this;
    }

    /**
     * Get decisionMaker
     *
     * @return boolean 
     */
    public function getDecisionMaker()
    {
        return $this->decisionMaker;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return LeadContact
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
     * Set facebook
     *
     * @param string $facebook
     * @return LeadContact
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    
        return $this;
    }

    /**
     * Get facebook
     *
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return LeadContact
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
     * Set ip
     *
     * @param string $ip
     * @return LeadContact
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set jobTitle
     *
     * @param string $jobTitle
     * @return LeadContact
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
    
        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string 
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return LeadContact
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return LeadContact
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
     * Set linkedin
     *
     * @param string $linkedin
     * @return LeadContact
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;
    
        return $this;
    }

    /**
     * Get linkedin
     *
     * @return string 
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * Set paypal
     *
     * @param string $paypal
     * @return LeadContact
     */
    public function setPaypal($paypal)
    {
        $this->paypal = $paypal;
    
        return $this;
    }

    /**
     * Get paypal
     *
     * @return string 
     */
    public function getPaypal()
    {
        return $this->paypal;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return LeadContact
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
     * Set skype
     *
     * @param string $skype
     * @return LeadContact
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    
        return $this;
    }

    /**
     * Get skype
     *
     * @return string 
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return LeadContact
     */
    public function setStreet($street)
    {
        $this->street = $street;
    
        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return LeadContact
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return LeadContact
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    
        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return LeadContact
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set callTime
     *
     * @param \Jariff\AdminBundle\Entity\CallTime $callTime
     * @return LeadContact
     */
    public function setCallTime(\Jariff\AdminBundle\Entity\CallTime $callTime = null)
    {
        $this->callTime = $callTime;
    
        return $this;
    }

    /**
     * Get callTime
     *
     * @return \Jariff\AdminBundle\Entity\CallTime 
     */
    public function getCallTime()
    {
        return $this->callTime;
    }

    /**
     * Set lead
     *
     * @param \Jariff\AdminBundle\Entity\Lead $lead
     * @return LeadContact
     */
    public function setLead(\Jariff\AdminBundle\Entity\Lead $lead = null)
    {
        $this->lead = $lead;
    
        return $this;
    }

    /**
     * Get lead
     *
     * @return \Jariff\AdminBundle\Entity\Lead 
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * Set ccType
     *
     * @param string $ccType
     * @return LeadContact
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
     * Set expired
     *
     * @param \DateTime $expired
     * @return LeadContact
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
     * Set number
     *
     * @param string $number
     * @return LeadContact
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
     * Set password
     *
     * @param string $password
     * @return LeadContact
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
     * Set payment
     *
     * @param string $payment
     * @return LeadContact
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
     * Set salutation
     *
     * @param string $salutation
     * @return LeadContact
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
     * Set secureCode
     *
     * @param string $secureCode
     * @return LeadContact
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
}