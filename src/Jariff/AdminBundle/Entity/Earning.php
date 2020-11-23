<?php

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Earning
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\EarningRepository")
 */
class Earning
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="achievement", type="integer")
     */
    private $achievement;

    /**
     * @var integer
     *
     * @ORM\Column(name="monthly", type="integer")
     */
    private $monthly;

    /**
     * @var integer
     *
     * @ORM\Column(name="quarterly", type="integer")
     */
    private $quarterly;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endof", type="datetime")
     */
    private $endof;

    /**
     * @var integer
     *
     * @ORM\Column(name="paid", type="integer")
     */
    private $paid;

    /**
     * @var integer
     *
     * @ORM\Column(name="corp_earning", type="integer")
     */
    private $corpEarning;

    /**
     * @var integer
     *
     * @ORM\Column(name="admin", type="integer")
     */
    private $admin;


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
     * Set achievement
     *
     * @param integer $achievement
     * @return Earning
     */
    public function setAchievement($achievement)
    {
        $this->achievement = $achievement;
    
        return $this;
    }

    /**
     * Get achievement
     *
     * @return integer 
     */
    public function getAchievement()
    {
        return $this->achievement;
    }

    /**
     * Set monthly
     *
     * @param integer $monthly
     * @return Earning
     */
    public function setMonthly($monthly)
    {
        $this->monthly = $monthly;
    
        return $this;
    }

    /**
     * Get monthly
     *
     * @return integer 
     */
    public function getMonthly()
    {
        return $this->monthly;
    }

    /**
     * Set quarterly
     *
     * @param integer $quarterly
     * @return Earning
     */
    public function setQuarterly($quarterly)
    {
        $this->quarterly = $quarterly;
    
        return $this;
    }

    /**
     * Get quarterly
     *
     * @return integer 
     */
    public function getQuarterly()
    {
        return $this->quarterly;
    }

    /**
     * Set endof
     *
     * @param \DateTime $endof
     * @return Earning
     */
    public function setEndof($endof)
    {
        $this->endof = $endof;
    
        return $this;
    }

    /**
     * Get endof
     *
     * @return \DateTime 
     */
    public function getEndof()
    {
        return $this->endof;
    }

    /**
     * Set paid
     *
     * @param integer $paid
     * @return Earning
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
    
        return $this;
    }

    /**
     * Get paid
     *
     * @return integer 
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Set corpEarning
     *
     * @param integer $corpEarning
     * @return Earning
     */
    public function setCorpEarning($corpEarning)
    {
        $this->corpEarning = $corpEarning;
    
        return $this;
    }

    /**
     * Get corpEarning
     *
     * @return integer 
     */
    public function getCorpEarning()
    {
        return $this->corpEarning;
    }

    /**
     * Set admin
     *
     * @param integer $admin
     * @return Earning
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    
        return $this;
    }

    /**
     * Get admin
     *
     * @return integer 
     */
    public function getAdmin()
    {
        return $this->admin;
    }
}