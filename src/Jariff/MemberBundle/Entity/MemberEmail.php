<?php

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Jariff\ProjectBundle\Util\Util;

/**
 * Jariff\MemberBundle\Entity\Message
 *
 * @ORM\Table(name="member_email")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberEmailRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberEmail
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="MemberEmailAlias", mappedBy="memberEmail", cascade={"all"})
     */
    private $alias;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $altbody;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $body;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateSend;

    /**
     * @ORM\OneToMany(targetEntity="MemberEmailHistory", mappedBy="memberEmail", cascade={"all"})
     */
    private $history;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="memberEmail")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $subject;

    /**
     * @ORM\Column(type="string", unique = true, length=64)
     */
    private $token;

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
        $this->token      = Util::token('member-email');
        $this->dateCreate = new \DateTime();
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
     * Set subject
     *
     * @param string $subject
     * @return MemberEmail
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return MemberEmail
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set altbody
     *
     * @param string $altbody
     * @return MemberEmail
     */
    public function setAltbody($altbody)
    {
        $this->altbody = $altbody;
    
        return $this;
    }

    /**
     * Get altbody
     *
     * @return string 
     */
    public function getAltbody()
    {
        return $this->altbody;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return MemberEmail
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
     * Set dateSend
     *
     * @param \DateTime $dateSend
     * @return MemberEmail
     */
    public function setDateSend($dateSend)
    {
        $this->dateSend = $dateSend;
    
        return $this;
    }

    /**
     * Get dateSend
     *
     * @return \DateTime 
     */
    public function getDateSend()
    {
        return $this->dateSend;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return MemberEmail
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
     * Set viewDate
     *
     * @param \DateTime $viewDate
     * @return MemberEmail
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
     * @return MemberEmail
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
     * @return MemberEmail
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
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberEmail
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
     * Set address
     *
     * @param string $address
     * @return MemberEmail
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add history
     *
     * @param \Jariff\MemberBundle\Entity\MemberEmailHistory $history
     * @return MemberEmail
     */
    public function addHistory(\Jariff\MemberBundle\Entity\MemberEmailHistory $history)
    {
        $this->history[] = $history;
    
        return $this;
    }

    /**
     * Remove history
     *
     * @param \Jariff\MemberBundle\Entity\MemberEmailHistory $history
     */
    public function removeHistory(\Jariff\MemberBundle\Entity\MemberEmailHistory $history)
    {
        $this->history->removeElement($history);
    }

    /**
     * Get history
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Add alias
     *
     * @param \Jariff\MemberBundle\Entity\MemberEmailAlias $alias
     * @return MemberEmail
     */
    public function addAlia(\Jariff\MemberBundle\Entity\MemberEmailAlias $alias)
    {
        $this->alias[] = $alias;
    
        return $this;
    }

    /**
     * Remove alias
     *
     * @param \Jariff\MemberBundle\Entity\MemberEmailAlias $alias
     */
    public function removeAlia(\Jariff\MemberBundle\Entity\MemberEmailAlias $alias)
    {
        $this->alias->removeElement($alias);
    }

    /**
     * Get alias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlias()
    {
        return $this->alias;
    }
}