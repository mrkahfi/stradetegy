<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\SuperAdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * Jariff\SuperAdminBundle\Entity\SuperAdmin
 *
 * @ORM\Table(name="super_admin")
 * @ORM\Entity(repositoryClass="Jariff\SuperAdminBundle\Repository\SuperAdminRepository")
 * @ORM\HasLifecycleCallbacks
 */
class SuperAdmin implements UserInterface, \Serializable
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
     * @ORM\OneToMany(targetEntity="Jariff\AdminBundle\Entity\LeadActivity", mappedBy="assigned", cascade={"all"})
     */
    private $activityAssigned;

    /**
     * @ORM\OneToMany(targetEntity="Jariff\AdminBundle\Entity\LeadActivity", mappedBy="owner", cascade={"all"})
     */
    private $activityOwned;

    /**
     * true / false
     * di blokir atau tidak, misal admin yg keluar dari perusahaan, maka akunnya di banned
     * kalau dihapus nanti bisa merusak tabel dengan foreign key ke admin tersebut
     * 
     * @ORM\Column(type="boolean")
     */
    private $banned;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="\Jariff\MemberBundle\Entity\Invoice", mappedBy="sales", cascade={"all"})
     */
    private $invoice;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLoginDate;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $lastLoginIp;

    /**
     * @ORM\OneToMany(targetEntity="Jariff\AdminBundle\Entity\Lead", mappedBy="owner", cascade={"all"})
     */
    private $leadOwned;

    /**
     * @ORM\OneToMany(targetEntity="Jariff\AdminBundle\Entity\LeadSales", mappedBy="sales", cascade={"all"})
     */
    private $leads;

    /**
     * Account Manager
     * 
     * @ORM\OneToMany(targetEntity="\Jariff\MemberBundle\Entity\MemberHistory", mappedBy="admin", cascade={"all"})
     */
    private $memberHistory;

    /**
     * Account Manager
     * 
     * @ORM\OneToMany(targetEntity="\Jariff\MemberBundle\Entity\Company", mappedBy="accountManager", cascade={"all"})
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="\Jariff\MemberBundle\Entity\Payment", mappedBy="admin", cascade={"all"})
     */
    private $payment;
    
    /**
     * @ORM\Column(type="string")
     */
    private $roles;

    /**
     * which subscription which sold because this admin
     * 
     * @ORM\OneToMany(targetEntity="\Jariff\MemberBundle\Entity\MemberSubscription", mappedBy="owner", cascade={"all"})
     */
    private $subscription;

    /**
     * See who helped the owner of subscription to reactivate if different than owner
     * 
     * @ORM\OneToMany(targetEntity="\Jariff\MemberBundle\Entity\MemberSubscription", mappedBy="sales2", cascade={"all"})
     */
    private $sales2;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $salt;

    public function getUsername()
    {
        return $this->email;
    }

    public function __toString(){
        return $this->name;
    }
    
    public function __construct(){
        $this->salt      = md5('member'.uniqid().time());
        $this->banned    = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRoles(array $roles)
    {
        $this->roles = serialize($roles);

        return $this;
    }

    public function getRoles()
    {
        return unserialize($this->roles);
    }

        /**
     * Set password
     *
     * @param string $password
     * @return SuperAdmin
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return SuperAdmin
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    public function eraseCredentials()
    {
    }
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }

        /**
     * Set email
     *
     * @param string $email
     * @return SuperAdmin
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }
    

    /**
     * Set lastLoginDate
     *
     * @param \DateTime $lastLoginDate
     * @return Profile
     */
    public function setLastLoginDate($lastLoginDate)
    {
        $this->lastLoginDate = $lastLoginDate;

        return $this;
    }

    /**
     * Get lastLoginDate
     *
     * @return \DateTime
     */
    public function getLastLoginDate()
    {
        return $this->lastLoginDate;
    }

    /**
     * Set lastLoginIp
     *
     * @param string $lastLoginIp
     * @return Profile
     */
    public function setLastLoginIp($lastLoginIp)
    {
        $this->lastLoginIp = $lastLoginIp;

        return $this;
    }

    /**
     * Get lastLoginIp
     *
     * @return string
     */
    public function getLastLoginIp()
    {
        return $this->lastLoginIp;
    }

    /**
     * Set banned
     *
     * @param boolean $banned
     * @return SuperAdmin
     */
    public function setBanned($banned)
    {
        $this->banned = $banned;

        return $this;
    }

    /**
     * Get banned
     *
     * @return boolean 
     */
    public function isBanned()
    {
        return $this->banned;
    }
    /**
     * Get banned
     *
     * @return boolean 
     */
    public function getBanned()
    {
        return $this->banned;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return SuperAdmin
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add payment
     *
     * @param \Jariff\MemberBundle\Entity\Payment $payment
     * @return SuperAdmin
     */
    public function addPayment(\Jariff\MemberBundle\Entity\Payment $payment)
    {
        $this->payment[] = $payment;
    
        return $this;
    }

    /**
     * Remove payment
     *
     * @param \Jariff\MemberBundle\Entity\Payment $payment
     */
    public function removePayment(\Jariff\MemberBundle\Entity\Payment $payment)
    {
        $this->payment->removeElement($payment);
    }

    /**
     * Get payment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Add activityAssigned
     *
     * @param \Jariff\AdminBundle\Entity\LeadActivity $activityAssigned
     * @return SuperAdmin
     */
    public function addActivityAssigned(\Jariff\AdminBundle\Entity\LeadActivity $activityAssigned)
    {
        $this->activityAssigned[] = $activityAssigned;
    
        return $this;
    }

    /**
     * Remove activityAssigned
     *
     * @param \Jariff\AdminBundle\Entity\LeadActivity $activityAssigned
     */
    public function removeActivityAssigned(\Jariff\AdminBundle\Entity\LeadActivity $activityAssigned)
    {
        $this->activityAssigned->removeElement($activityAssigned);
    }

    /**
     * Get activityAssigned
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivityAssigned()
    {
        return $this->activityAssigned;
    }

    /**
     * Add activityOwned
     *
     * @param \Jariff\AdminBundle\Entity\LeadActivity $activityOwned
     * @return SuperAdmin
     */
    public function addActivityOwned(\Jariff\AdminBundle\Entity\LeadActivity $activityOwned)
    {
        $this->activityOwned[] = $activityOwned;
    
        return $this;
    }

    /**
     * Remove activityOwned
     *
     * @param \Jariff\AdminBundle\Entity\LeadActivity $activityOwned
     */
    public function removeActivityOwned(\Jariff\AdminBundle\Entity\LeadActivity $activityOwned)
    {
        $this->activityOwned->removeElement($activityOwned);
    }

    /**
     * Get activityOwned
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivityOwned()
    {
        return $this->activityOwned;
    }

    /**
     * Add leads
     *
     * @param \Jariff\AdminBundle\Entity\Lead $leads
     * @return SuperAdmin
     */
    public function addLead(\Jariff\AdminBundle\Entity\Lead $leads)
    {
        $this->leads[] = $leads;
    
        return $this;
    }

    /**
     * Remove leads
     *
     * @param \Jariff\AdminBundle\Entity\Lead $leads
     */
    public function removeLead(\Jariff\AdminBundle\Entity\Lead $leads)
    {
        $this->leads->removeElement($leads);
    }

    /**
     * Get leads
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLeads()
    {
        return $this->leads;
    }

    /**
     * Add leadOwned
     *
     * @param \Jariff\AdminBundle\Entity\Lead $leadOwned
     * @return SuperAdmin
     */
    public function addLeadOwned(\Jariff\AdminBundle\Entity\Lead $leadOwned)
    {
        $this->leadOwned[] = $leadOwned;
    
        return $this;
    }

    /**
     * Remove leadOwned
     *
     * @param \Jariff\AdminBundle\Entity\Lead $leadOwned
     */
    public function removeLeadOwned(\Jariff\AdminBundle\Entity\Lead $leadOwned)
    {
        $this->leadOwned->removeElement($leadOwned);
    }

    /**
     * Get leadOwned
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLeadOwned()
    {
        return $this->leadOwned;
    }

    /**
     * Add subscription
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscription $subscription
     * @return SuperAdmin
     */
    public function addSubscription(\Jariff\MemberBundle\Entity\MemberSubscription $subscription)
    {
        $this->subscription[] = $subscription;
    
        return $this;
    }

    /**
     * Remove subscription
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscription $subscription
     */
    public function removeSubscription(\Jariff\MemberBundle\Entity\MemberSubscription $subscription)
    {
        $this->subscription->removeElement($subscription);
    }

    /**
     * Get subscription
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Add sales2
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscription $sales2
     * @return SuperAdmin
     */
    public function addSales2(\Jariff\MemberBundle\Entity\MemberSubscription $sales2)
    {
        $this->sales2[] = $sales2;
    
        return $this;
    }

    /**
     * Remove sales2
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscription $sales2
     */
    public function removeSales2(\Jariff\MemberBundle\Entity\MemberSubscription $sales2)
    {
        $this->sales2->removeElement($sales2);
    }

    /**
     * Get sales2
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSales2()
    {
        return $this->sales2;
    }

    /**
     * Get memberProfile
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMemberProfile()
    {
        return $this->memberProfile;
    }

    /**
     * Add invoice
     *
     * @param \Jariff\MemberBundle\Entity\Invoice $invoice
     * @return SuperAdmin
     */
    public function addInvoice(\Jariff\MemberBundle\Entity\Invoice $invoice)
    {
        $this->invoice[] = $invoice;
    
        return $this;
    }

    /**
     * Remove invoice
     *
     * @param \Jariff\MemberBundle\Entity\Invoice $invoice
     */
    public function removeInvoice(\Jariff\MemberBundle\Entity\Invoice $invoice)
    {
        $this->invoice->removeElement($invoice);
    }

    /**
     * Get invoice
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Add memberHistory
     *
     * @param \Jariff\MemberBundle\Entity\MemberHistory $memberHistory
     * @return SuperAdmin
     */
    public function addMemberHistory(\Jariff\MemberBundle\Entity\MemberHistory $memberHistory)
    {
        $this->memberHistory[] = $memberHistory;
    
        return $this;
    }

    /**
     * Remove memberHistory
     *
     * @param \Jariff\MemberBundle\Entity\MemberHistory $memberHistory
     */
    public function removeMemberHistory(\Jariff\MemberBundle\Entity\MemberHistory $memberHistory)
    {
        $this->memberHistory->removeElement($memberHistory);
    }

    /**
     * Get memberHistory
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMemberHistory()
    {
        return $this->memberHistory;
    }

    /**
     * Add company
     *
     * @param \Jariff\MemberBundle\Entity\Company $company
     * @return SuperAdmin
     */
    public function addCompany(\Jariff\MemberBundle\Entity\Company $company)
    {
        $this->company[] = $company;
    
        return $this;
    }

    /**
     * Remove company
     *
     * @param \Jariff\MemberBundle\Entity\Company $company
     */
    public function removeCompany(\Jariff\MemberBundle\Entity\Company $company)
    {
        $this->company->removeElement($company);
    }

    /**
     * Get company
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompany()
    {
        return $this->company;
    }
}