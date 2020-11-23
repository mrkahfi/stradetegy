<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Jariff\ProjectBundle\Util\Util;

/**
 * Jariff\MemberBundle\Entity\MemberHistory
 *
 * @ORM\Table(name="member_history")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberHistoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberHistory
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Jariff\AdminBundle\Entity\Admin", inversedBy="memberHistory")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL")
     */
    private $admin;

    /**
     * @ORM\Column(name="_column", type="string")
     */
    private $column;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="history")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * @ORM\Column(type="text")
     */
    private $newValue;

    /**
     * @ORM\Column(type="text")
     */
    private $oldValue;

    /**
     * @ORM\Column(name="_table", type="string")
     */
    private $table;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {        
        $this->date = new \DateTime();

        if ( $this->oldValue instanceof \DateTime) {
            $this->oldValue = $this->oldValue->format('Y-m-d H:i:s'); 
        }
        if ( $this->newValue instanceof \DateTime) {
            $this->newValue = $this->newValue->format('Y-m-d H:i:s'); 
        }
        if (empty($this->description)) {
            if (is_null($this->admin)) {
                $this->description = 'This member change data on '.$this->column.' from "'.$this->oldValue.'" become "'.$this->newValue.'"';
            } else {
                $this->description = $this->admin->getName().' update member '.$this->member->getFirstName().' on '.$this->column.' from "'.$this->oldValue.'" become "'.$this->newValue.'"';
            }
        }
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
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
     * Set column
     *
     * @param string $column
     * @return MemberHistory
     */
    public function setColumn($column)
    {
        $this->column = $column;
    
        return $this;
    }

    /**
     * Get column
     *
     * @return string 
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return MemberHistory
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
     * @return MemberHistory
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
     * Set newValue
     *
     * @param string $newValue
     * @return MemberHistory
     */
    public function setNewValue($newValue)
    {
        $this->newValue = $newValue;
    
        return $this;
    }

    /**
     * Get newValue
     *
     * @return string 
     */
    public function getNewValue()
    {
        return $this->newValue;
    }

    /**
     * Set oldValue
     *
     * @param string $oldValue
     * @return MemberHistory
     */
    public function setOldValue($oldValue)
    {
        $this->oldValue = $oldValue;
    
        return $this;
    }

    /**
     * Get oldValue
     *
     * @return string 
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * Set table
     *
     * @param string $table
     * @return MemberHistory
     */
    public function setTable($table)
    {
        $this->table = $table;
    
        return $this;
    }

    /**
     * Get table
     *
     * @return string 
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set admin
     *
     * @param \Jariff\AdminBundle\Entity\Admin $admin
     * @return MemberHistory
     */
    public function setAdmin(\Jariff\AdminBundle\Entity\Admin $admin = null)
    {
        $this->admin = $admin;
    
        return $this;
    }

    /**
     * Get admin
     *
     * @return \Jariff\AdminBundle\Entity\Admin 
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberHistory
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