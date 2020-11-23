<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="contact_company")
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\AdminRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ContactCompany
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
     * @ORM\Column(type="string", nullable=false)
     */
    private $company_name;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $company_as;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $country_type;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $s_cache;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $process;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $reply;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $request_at;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $slug;


    public function __construct()
    {
        $this->request_at = new \DateTime('now');
        $this->process = 0;
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
     * Set company_name
     *
     * @param string $companyName
     * @return ContactCompany
     */
    public function setCompanyName($companyName)
    {
        $this->company_name = $companyName;
    
        return $this;
    }

    /**
     * Get company_name
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->company_name;
    }

    /**
     * Set company_as
     *
     * @param string $companyAs
     * @return ContactCompany
     */
    public function setCompanyAs($companyAs)
    {
        $this->company_as = $companyAs;
    
        return $this;
    }

    /**
     * Get company_as
     *
     * @return string 
     */
    public function getCompanyAs()
    {
        return $this->company_as;
    }

    /**
     * Set country_type
     *
     * @param string $countryType
     * @return ContactCompany
     */
    public function setCountryType($countryType)
    {
        $this->country_type = $countryType;
    
        return $this;
    }

    /**
     * Get country_type
     *
     * @return string 
     */
    public function getCountryType()
    {
        return $this->country_type;
    }

    /**
     * Set s_cache
     *
     * @param string $sCache
     * @return ContactCompany
     */
    public function setSCache($sCache)
    {
        $this->s_cache = $sCache;
    
        return $this;
    }

    /**
     * Get s_cache
     *
     * @return string 
     */
    public function getSCache()
    {
        return $this->s_cache;
    }

    /**
     * Set process
     *
     * @param boolean $process
     * @return ContactCompany
     */
    public function setProcess($process)
    {
        $this->process = $process;
    
        return $this;
    }

    /**
     * Get process
     *
     * @return boolean 
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * Set reply
     *
     * @param string $reply
     * @return ContactCompany
     */
    public function setReply($reply)
    {
        $this->reply = $reply;
    
        return $this;
    }

    /**
     * Get reply
     *
     * @return string 
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * Set request_at
     *
     * @param \DateTime $requestAt
     * @return ContactCompany
     */
    public function setRequestAt($requestAt)
    {
        $this->request_at = $requestAt;
    
        return $this;
    }

    /**
     * Get request_at
     *
     * @return \DateTime 
     */
    public function getRequestAt()
    {
        return $this->request_at;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return ContactCompany
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
     * Set slug
     *
     * @param string $slug
     * @return ContactCompany
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
}