<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="member_subscription_activity")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberSubscriptionActivityRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberSubscriptionActivity
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreate;

    /**
     * @ORM\Column(type="text")
     */
    private $note;

    /**
     * activated    = 
     * canceled     = 
     * expired      = ARB but CC invalid,
     * suspended    = abusement
     * 
     * @ORM\Column(type="string", length=20)
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity="MemberSubscription", inversedBy="activity", cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", name="member_subscription_id", onDelete="CASCADE")
     */
    private $subscription;

    public function __toString()
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
     * @return MemberSubscriptionActivity
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
     * Set note
     *
     * @param string $note
     * @return MemberSubscriptionActivity
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
     * Set action
     *
     * @param string $action
     * @return MemberSubscriptionActivity
     */
    public function setAction($action)
    {
        $this->action = $action;
    
        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set subscription
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscription $subscription
     * @return MemberSubscriptionActivity
     */
    public function setSubscription(\Jariff\MemberBundle\Entity\MemberSubscription $subscription = null)
    {
        $this->subscription = $subscription;
    
        return $this;
    }

    /**
     * Get subscription
     *
     * @return \Jariff\MemberBundle\Entity\MemberSubscription 
     */
    public function getSubscription()
    {
        return $this->subscription;
    }
}