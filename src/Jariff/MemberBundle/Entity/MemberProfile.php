<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Jariff\MemberBundle\Entity\MemberProfile
 *
 * @ORM\Table(name="member_profile")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberProfileRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberProfile
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    // billToName dipindah ke Company
    // billToAdress dipindah ke Company
    // billToEmail dipindah ke Company
    // billToPhone dipindah ke Company

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $country;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRegistration;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank()
     */
    private $firstName;

    // invoiceType dipindah ke Company

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank()
     */
    private $lastName;

    // manager : dipindah ke Company jadi accountManager

    /**
     * @ORM\OneToOne(targetEntity="Member", inversedBy="profile")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $phone;

    /**
     * Mr. Mrs. Ms.
     * 
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $salutation;

    // shipToName dipindah ke Company
    // shipToAdress dipindah ke Company
    // shipToEmail dipindah ke Company
    // shipToPhone dipindah ke Company
    
    /**
     * country state
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    private $state;

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

    public function getName()
    {
        return $this->salutation.' '.$this->firstName.' '.$this->lastName;
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
     * @return MemberProfile
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
     * Set dateRegistration
     *
     * @param \DateTime $dateRegistration
     * @return MemberProfile
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
     * Set firstName
     *
     * @param string $firstName
     * @return MemberProfile
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
     * @return MemberProfile
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
     * Set phone
     *
     * @param string $phone
     * @return MemberProfile
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
     * Set salutation
     *
     * @param string $salutation
     * @return MemberProfile
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
     * Set state
     *
     * @param string $state
     * @return MemberProfile
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberProfile
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