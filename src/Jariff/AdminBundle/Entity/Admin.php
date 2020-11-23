<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * Jariff\AdminBundle\Entity\Admin
 *
 * @ORM\Table(name="admins", indexes={@ORM\Index(name="index_admins_on_email", columns={"email"}), @ORM\Index(name="index_admins_on_reset_password_token", columns={"reset_password_token"})})
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\AdminRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Admin implements UserInterface, \Serializable
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
     * @ORM\OneToMany(targetEntity="Jariff\MemberBundle\Entity\MemberContract", mappedBy="admin", cascade={"all"})
     */
    private $contract;

    /**
     * @ORM\OneToMany(targetEntity="LeadActivity", mappedBy="assigned", cascade={"all"})
     */
    private $activityAssigned;

    /**
     * @ORM\OneToMany(targetEntity="LeadActivity", mappedBy="owner", cascade={"all"})
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
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="\Jariff\MemberBundle\Entity\Invoice", mappedBy="sales", cascade={"all"})
     */
    private $invoice;

    /**
     * @ORM\Column(type="datetime", name="last_sign_in_at", nullable=true)
     */
    private $lastLoginDate;

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=true)
     */
    private $joinDate;

    /**
     * @ORM\Column(type="string", name="last_sign_in_ip", nullable=true)
     */
    private $lastLoginIp;

    /**
     * @ORM\OneToMany(targetEntity="LeadHistory", mappedBy="admin", cascade={"all"})
     */
    private $leadHistory;

    /**
     * @ORM\OneToMany(targetEntity="Lead", mappedBy="owner", cascade={"all"})
     */
    private $leadOwned;

    /**
     * @ORM\OneToMany(targetEntity="LeadSales", mappedBy="sales", cascade={"all"})
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", name="encrypted_password")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="\Jariff\MemberBundle\Entity\Payment", mappedBy="admin", cascade={"all"})
     */
    private $payment;
    
    /**
     * @ORM\Column(type="string", name="role", nullable=true)
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

    /**
     * @ORM\Column(name="ws_id", type="integer", nullable=true)
     */
    private $wsId;

    /**
     * See revenue detail
     * 
     * @ORM\OneToMany(targetEntity="\Jariff\AdminBundle\Entity\Earning", mappedBy="earnings", cascade={"all"})
     */
    private $earnings;


    /**
     * @ORM\Column(name="reset_password_token", type="string", nullable=true)
     */
    private $resetPasswordToken;
    
    /**
     * @ORM\Column(name="reset_password_sent_at", type="datetime", nullable=true)
     */
    private $resetPasswordSentAt;
    
    /**
     * @ORM\Column(name="remember_created_at", type="datetime", nullable=true)
     */
    private $rememberCreatedAt;
    
    /**
     * @ORM\Column(name="sign_in_count", type="integer", nullable=false, options={"default" = 0})
     */
    private $signInCount;
    
    /**
     * @ORM\Column(name="current_sign_in_at", type="datetime", nullable=true)
     */
    private $currentSignInAt;
    
    /**
     * @ORM\Column(name="current_sign_in_ip", type="string", nullable=true)
     */
    private $currentSignInIp;
    
    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;
    
    /**
     * @ORM\Column(name="avatar_file_name", type="string", nullable=true)
     */
    private $avatarFileName;
    
    /**
     * @ORM\Column(name="avatar_file_size", type="integer", nullable=true)
     */
    private $avatarFileSize;
    
    /**
     * @ORM\Column(name="avatar_content_type", type="string", nullable=true)
     */
    private $avatarContentType;
    
    /**
     * @ORM\Column(name="avatar_updated_at", type="datetime", nullable=true)
     */
    private $avatarUpdatedAt;


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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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


    public function getWsId()
    {
        return $this->wsId;
    }

    /**
     * Add earnings
     *
     * @param \Jariff\AdminBundle\Entity\Earning $earning
     * @return Admin
     */
    public function addEarnings(\Jariff\AdminBundle\Entity\Earning $earning)
    {
        $this->earnings[] = $earning;
    
        return $this;
    }

    /**
     * Remove earnings
     *
     * @param \Jariff\AdminBundle\Entity\Earning $earning
     */
    public function removeEarnings(\Jariff\AdminBundle\Entity\Earning $earning)
    {
        $this->earnings->removeElement($earning);
    }

    /**
     * Get earnings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEarnings()
    {
        return $this->earnings;
    }


    /**
     * Set joinDate
     *
     * @param \DateTime $joinDate
     * @return Admin
     */
    public function setJoinDate($joinDate)
    {
        $this->joinDate = $joinDate;
    
        return $this;
    }

    /**
     * Get joinDate
     *
     * @return \DateTime 
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    /**
     * Set wsId
     *
     * @param integer $wsId
     * @return Admin
     */
    public function setWsId($wsId)
    {
        $this->wsId = $wsId;
    
        return $this;
    }

    /**
     * Add leadHistory
     *
     * @param \Jariff\AdminBundle\Entity\LeadHistory $leadHistory
     * @return Admin
     */
    public function addLeadHistory(\Jariff\AdminBundle\Entity\LeadHistory $leadHistory)
    {
        $this->leadHistory[] = $leadHistory;
    
        return $this;
    }

    /**
     * Remove leadHistory
     *
     * @param \Jariff\AdminBundle\Entity\LeadHistory $leadHistory
     */
    public function removeLeadHistory(\Jariff\AdminBundle\Entity\LeadHistory $leadHistory)
    {
        $this->leadHistory->removeElement($leadHistory);
    }

    /**
     * Get leadHistory
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLeadHistory()
    {
        return $this->leadHistory;
    }

    /**
     * Add earnings
     *
     * @param \Jariff\AdminBundle\Entity\Earning $earnings
     * @return Admin
     */
    public function addEarning(\Jariff\AdminBundle\Entity\Earning $earnings)
    {
        $this->earnings[] = $earnings;
    
        return $this;
    }

    /**
     * Remove earnings
     *
     * @param \Jariff\AdminBundle\Entity\Earning $earnings
     */
    public function removeEarning(\Jariff\AdminBundle\Entity\Earning $earnings)
    {
        $this->earnings->removeElement($earnings);
    }

    /**
     * Set resetPasswordToken
     *
     * @param string $resetPasswordToken
     * @return Admin
     */
    public function setResetPasswordToken($resetPasswordToken)
    {
        $this->resetPasswordToken = $resetPasswordToken;
    
        return $this;
    }

    /**
     * Get resetPasswordToken
     *
     * @return string 
     */
    public function getResetPasswordToken()
    {
        return $this->resetPasswordToken;
    }

    /**
     * Set resetPasswordSentAt
     *
     * @param \DateTime $resetPasswordSentAt
     * @return Admin
     */
    public function setResetPasswordSentAt($resetPasswordSentAt)
    {
        $this->resetPasswordSentAt = $resetPasswordSentAt;
    
        return $this;
    }

    /**
     * Get resetPasswordSentAt
     *
     * @return \DateTime 
     */
    public function getResetPasswordSentAt()
    {
        return $this->resetPasswordSentAt;
    }

    /**
     * Set rememberCreatedAt
     *
     * @param \DateTime $rememberCreatedAt
     * @return Admin
     */
    public function setRememberCreatedAt($rememberCreatedAt)
    {
        $this->rememberCreatedAt = $rememberCreatedAt;
    
        return $this;
    }

    /**
     * Get rememberCreatedAt
     *
     * @return \DateTime 
     */
    public function getRememberCreatedAt()
    {
        return $this->rememberCreatedAt;
    }

    /**
     * Set signInCount
     *
     * @param integer $signInCount
     * @return Admin
     */
    public function setSignInCount($signInCount)
    {
        $this->signInCount = $signInCount;
    
        return $this;
    }

    /**
     * Get signInCount
     *
     * @return integer 
     */
    public function getSignInCount()
    {
        return $this->signInCount;
    }

    /**
     * Set currentSignInAt
     *
     * @param \DateTime $currentSignInAt
     * @return Admin
     */
    public function setCurrentSignInAt($currentSignInAt)
    {
        $this->currentSignInAt = $currentSignInAt;
    
        return $this;
    }

    /**
     * Get currentSignInAt
     *
     * @return \DateTime 
     */
    public function getCurrentSignInAt()
    {
        return $this->currentSignInAt;
    }

    /**
     * Set currentSignInIp
     *
     * @param string $currentSignInIp
     * @return Admin
     */
    public function setCurrentSignInIp($currentSignInIp)
    {
        $this->currentSignInIp = $currentSignInIp;
    
        return $this;
    }

    /**
     * Get currentSignInIp
     *
     * @return string 
     */
    public function getCurrentSignInIp()
    {
        return $this->currentSignInIp;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Admin
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set avatarFileName
     *
     * @param string $avatarFileName
     * @return Admin
     */
    public function setAvatarFileName($avatarFileName)
    {
        $this->avatarFileName = $avatarFileName;
    
        return $this;
    }

    /**
     * Get avatarFileName
     *
     * @return string 
     */
    public function getAvatarFileName()
    {
        return $this->avatarFileName;
    }

    /**
     * Set avatarFileSize
     *
     * @param integer $avatarFileSize
     * @return Admin
     */
    public function setAvatarFileSize($avatarFileSize)
    {
        $this->avatarFileSize = $avatarFileSize;
    
        return $this;
    }

    /**
     * Get avatarFileSize
     *
     * @return integer 
     */
    public function getAvatarFileSize()
    {
        return $this->avatarFileSize;
    }

    /**
     * Set avatarContentType
     *
     * @param string $avatarContentType
     * @return Admin
     */
    public function setAvatarContentType($avatarContentType)
    {
        $this->avatarContentType = $avatarContentType;
    
        return $this;
    }

    /**
     * Get avatarContentType
     *
     * @return string 
     */
    public function getAvatarContentType()
    {
        return $this->avatarContentType;
    }

    /**
     * Set avatarUpdatedAt
     *
     * @param \DateTime $avatarUpdatedAt
     * @return Admin
     */
    public function setAvatarUpdatedAt($avatarUpdatedAt)
    {
        $this->avatarUpdatedAt = $avatarUpdatedAt;
    
        return $this;
    }

    /**
     * Get avatarUpdatedAt
     *
     * @return \DateTime 
     */
    public function getAvatarUpdatedAt()
    {
        return $this->avatarUpdatedAt;
    }

    /**
     * Add contract
     *
     * @param \Jariff\MemberBundle\Entity\MemberContract $contract
     * @return Admin
     */
    public function addContract(\Jariff\MemberBundle\Entity\MemberContract $contract)
    {
        $this->contract[] = $contract;
    
        return $this;
    }

    /**
     * Remove contract
     *
     * @param \Jariff\MemberBundle\Entity\MemberContract $contract
     */
    public function removeContract(\Jariff\MemberBundle\Entity\MemberContract $contract)
    {
        $this->contract->removeElement($contract);
    }

    /**
     * Get contract
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContract()
    {
        return $this->contract;
    }
}