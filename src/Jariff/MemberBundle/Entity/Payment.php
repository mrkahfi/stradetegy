<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Jariff\ProjectBundle\Util\Util;

/**
 * Jariff\MemberBundle\Entity\Payment
 *
 * @ORM\Table(name="member_payment")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\PaymentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Payment
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="\Jariff\AdminBundle\Entity\Admin", inversedBy="payment")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL")
     */
    private $admin;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="payment")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * @ORM\OneToOne(targetEntity="Invoice", mappedBy="payment")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $invoice;

    /**
     * @ORM\Column(type="string", unique=true, length=64)
     * @Assert\NotBlank()
     */
    private $token;

    /**
     * cc
     * bankwire
     * paypal
     * check
     * 
     * @ORM\Column(type="string", length=8)
     */
    private $type;

    public function setInvoice(\Jariff\MemberBundle\Entity\Invoice $invoice = null)
    {
        if (is_null($invoice->getPayment())) {
            $invoice->setPayment($this);
        }
        $this->invoice = $invoice;
    
        return $this;
    }

    public function __toString(){
        return $this->amount.'$, '.$this->type.', '.$this->date->format('Y-m-d H:i').', '.$this->note;
    }

    public function __construct(){
        $this->date = new \DateTime();
        $this->token = Util::token('payment');
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
     * Set amount
     *
     * @param integer $amount
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Payment
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Payment
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
     * Set note
     *
     * @param string $note
     * @return Payment
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
     * Set admin
     *
     * @param \Jariff\AdminBundle\Entity\Admin $admin
     * @return Payment
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
     * @return Payment
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
     * Set token
     *
     * @param string $token
     * @return Payment
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
     * Get invoice
     *
     * @return \Jariff\MemberBundle\Entity\Invoice 
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
}