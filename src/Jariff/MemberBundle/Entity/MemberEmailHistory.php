<?php

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Jariff\MemberBundle\Entity\Message
 *
 * @ORM\Table(name="member_email_history")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberEmailHistoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberEmailHistory
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="MemberEmail", inversedBy="history")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $memberEmail;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $viewDate;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $viewIpAddress;

    /**
     * operating system
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    private $viewOs;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {        
        $this->viewDate = new \DateTime();
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
     * Set viewDate
     *
     * @param \DateTime $viewDate
     * @return MemberEmailHistory
     */
    public function setViewDate($viewDate)
    {
        $this->viewDate = $viewDate;
    
        return $this;
    }

    /**
     * Get viewDate
     *
     * @return \DateTime 
     */
    public function getViewDate()
    {
        return $this->viewDate;
    }

    /**
     * Set viewIpAddress
     *
     * @param string $viewIpAddress
     * @return MemberEmailHistory
     */
    public function setViewIpAddress($viewIpAddress)
    {
        $this->viewIpAddress = $viewIpAddress;
    
        return $this;
    }

    /**
     * Get viewIpAddress
     *
     * @return string 
     */
    public function getViewIpAddress()
    {
        return $this->viewIpAddress;
    }

    /**
     * Set viewOs
     *
     * @param string $viewOs
     * @return MemberEmailHistory
     */
    public function setViewOs($viewOs)
    {
        $this->viewOs = $viewOs;
    
        return $this;
    }

    /**
     * Get viewOs
     *
     * @return string 
     */
    public function getViewOs()
    {
        return $this->viewOs;
    }

    /**
     * Set memberEmail
     *
     * @param \Jariff\MemberBundle\Entity\MemberEmail $memberEmail
     * @return MemberEmailHistory
     */
    public function setMemberEmail(\Jariff\MemberBundle\Entity\MemberEmail $memberEmail = null)
    {
        $memberEmail->addHistory($this);
        $this->memberEmail = $memberEmail;
    
        return $this;
    }

    /**
     * Get memberEmail
     *
     * @return \Jariff\MemberBundle\Entity\MemberEmail 
     */
    public function getMemberEmail()
    {
        return $this->memberEmail;
    }
}