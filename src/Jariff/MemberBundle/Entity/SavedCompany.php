<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="saved_company")
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\AdminRepository")
 * @ORM\HasLifecycleCallbacks
 */
class SavedCompany
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
    private $name_company;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $slug_company;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $country_origin;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name_index;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $is_compare;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date_save;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $detail;


    public function __construct()
    {
        $this->date_save = new \DateTime('now');

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
     * Set slug_company
     *
     * @param string $slugCompany
     * @return SavedCompany
     */
    public function setSlugCompany($slugCompany)
    {
        $this->slug_company = $slugCompany;
    
        return $this;
    }

    /**
     * Get slug_company
     *
     * @return string 
     */
    public function getSlugCompany()
    {
        return $this->slug_company;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return SavedCompany
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
     * Set name_index
     *
     * @param string $nameIndex
     * @return SavedCompany
     */
    public function setNameIndex($nameIndex)
    {
        $this->name_index = $nameIndex;
    
        return $this;
    }

    /**
     * Get name_index
     *
     * @return string 
     */
    public function getNameIndex()
    {
        return $this->name_index;
    }

    /**
     * Set is_compare
     *
     * @param boolean $isCompare
     * @return SavedCompany
     */
    public function setIsCompare($isCompare)
    {
        $this->is_compare = $isCompare;
    
        return $this;
    }

    /**
     * Get is_compare
     *
     * @return boolean 
     */
    public function getIsCompare()
    {
        return $this->is_compare;
    }

    /**
     * Set date_save
     *
     * @param \DateTime $dateSave
     * @return SavedCompany
     */
    public function setDateSave($dateSave)
    {
        $this->date_save = $dateSave;
    
        return $this;
    }

    /**
     * Get date_save
     *
     * @return \DateTime 
     */
    public function getDateSave()
    {
        return $this->date_save;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return SavedCompany
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
     * Set name_company
     *
     * @param string $nameCompany
     * @return SavedCompany
     */
    public function setNameCompany($nameCompany)
    {
        $this->name_company = $nameCompany;
    
        return $this;
    }

    /**
     * Get name_company
     *
     * @return string 
     */
    public function getNameCompany()
    {
        return $this->name_company;
    }

    /**
     * Set country_origin
     *
     * @param string $countryOrigin
     * @return SavedCompany
     */
    public function setCountryOrigin($countryOrigin)
    {
        $this->country_origin = $countryOrigin;
    
        return $this;
    }

    /**
     * Get country_origin
     *
     * @return string 
     */
    public function getCountryOrigin()
    {
        return $this->country_origin;
    }

    /**
     * Set detail
     *
     * @param string $detail
     * @return SavedCompany
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    
        return $this;
    }

    /**
     * Get detail
     *
     * @return string 
     */
    public function getDetail()
    {
        return $this->detail;
    }
}