<?php

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DailyRank
 *
 * @ORM\Table("daily_rank")
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\DailyRankRepository")
 */
class DailyRank
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="alexaRank", type="integer")
     */
    private $alexaRank;

    /**
     * @var integer
     *
     * @ORM\Column(nullable=true, name="holiday", type="integer")
     */
    private $holiday;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, name="note", type="string", length=255)
     */
    private $note;

    /**
     * @var integer
     *
     * @ORM\Column(name="competitor", type="integer")
     */
    private $competitor;


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
     * Set date
     *
     * @param \DateTime $date
     * @return DailyRank
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
     * Set alexaRank
     *
     * @param integer $alexaRank
     * @return DailyRank
     */
    public function setAlexaRank($alexaRank)
    {
        $this->alexaRank = $alexaRank;
    
        return $this;
    }

    /**
     * Get alexaRank
     *
     * @return integer 
     */
    public function getAlexaRank()
    {
        return $this->alexaRank;
    }

    /**
     * Set holiday
     *
     * @param integer $holiday
     * @return DailyRank
     */
    public function setHoliday($holiday)
    {
        $this->holiday = $holiday;
    
        return $this;
    }

    /**
     * Get holiday
     *
     * @return integer 
     */
    public function getHoliday()
    {
        return $this->holiday;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return DailyRank
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set competitor
     *
     * @param integer $competitor
     * @return DailyRank
     */
    public function setCompetitor($competitor)
    {
        $this->competitor = $competitor;
    
        return $this;
    }

    /**
     * Get competitor
     *
     * @return integer 
     */
    public function getCompetitor()
    {
        return $this->competitor;
    }
}