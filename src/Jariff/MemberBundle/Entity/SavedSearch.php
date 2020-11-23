<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="saved_search")
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\AdminRepository")
 * @ORM\HasLifecycleCallbacks
 */
class SavedSearch
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
    private $name_of_search;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $query;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $is_alert;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date_search;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $slug_country_subscription;


    public function __construct()
    {
        $this->date_search = new \DateTime('now');

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
     * Set name_of_search
     *
     * @param string $nameOfSearch
     * @return SavedSearch
     */
    public function setNameOfSearch($nameOfSearch)
    {
        $this->name_of_search = $nameOfSearch;
    
        return $this;
    }

    /**
     * Get name_of_search
     *
     * @return string 
     */
    public function getNameOfSearch()
    {
        return $this->name_of_search;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return SavedSearch
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
     * Set query
     *
     * @param string $query
     * @return SavedSearch
     */
    public function setQuery($query)
    {
        $this->query = $query;
    
        return $this;
    }

    /**
     * Get query
     *
     * @return string 
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set is_alert
     *
     * @param boolean $isAlert
     * @return SavedSearch
     */
    public function setIsAlert($isAlert)
    {
        $this->is_alert = $isAlert;
    
        return $this;
    }

    /**
     * Get is_alert
     *
     * @return boolean 
     */
    public function getIsAlert()
    {
        return $this->is_alert;
    }

    /**
     * Set date_search
     *
     * @param \DateTime $dateSearch
     * @return SavedSearch
     */
    public function setDateSearch($dateSearch)
    {
        $this->date_search = $dateSearch;
    
        return $this;
    }

    /**
     * Get date_search
     *
     * @return \DateTime 
     */
    public function getDateSearch()
    {
        return $this->date_search;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return SavedSearch
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
     * Set slug_country_subscription
     *
     * @param string $slugCountrySubscription
     * @return SavedSearch
     */
    public function setSlugCountrySubscription($slugCountrySubscription)
    {
        $this->slug_country_subscription = $slugCountrySubscription;
    
        return $this;
    }

    /**
     * Get slug_country_subscription
     *
     * @return string 
     */
    public function getSlugCountrySubscription()
    {
        return $this->slug_country_subscription;
    }
}