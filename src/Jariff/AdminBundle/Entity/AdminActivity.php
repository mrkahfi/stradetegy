<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\AdminActivityRepository")
 * @ORM\Table(name="admin_activity")
 * @ORM\HasLifecycleCallbacks
 */
class AdminActivity
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
     * @ORM\ManyToOne(targetEntity="Admin", inversedBy="activity")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $admin;

    /**
     * tanggal ditandai sebagai selesai
     * 
     * @ORM\Column(type="datetime")
     */
    protected $dateCreate;

    /**
     * @ORM\Column(type="string")
     */
    protected $message;

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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return AdminActivity
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
     * Set message
     *
     * @param string $message
     * @return AdminActivity
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set admin
     *
     * @param \Jariff\AdminBundle\Entity\Admin $admin
     * @return AdminActivity
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
}