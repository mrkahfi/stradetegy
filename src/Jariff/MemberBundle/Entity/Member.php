<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

use Jariff\ProjectBundle\Util\Util;

/**
 * Jariff\MemberBundle\Entity\Member
 *
 * @ORM\Table(name="members", indexes={
 *     @ORM\Index(name="index_members_on_created_at", columns="created_at"),
 * })
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Member implements UserInterface, \Serializable
{
    const NEWLY                = 0;
    const AGREEMENT_SIGNED     = 5;
    const INITIAL_DECLINE      = 10;
    const ACTIVE               = 15;
    const PENDING_CANCELLATION = 20;
    const CANCELLED            = 25;
    const SUSPENDED            = 30;
    const EXPIRED              = 35;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * generated auto recurring billing (month to month payment)
     * 
     * @ORM\OneToMany(targetEntity="MemberARB", mappedBy="member", cascade={"all"})
     */
    private $arb;

    /**
     * @ORM\OneToMany(targetEntity="MemberContract", mappedBy="member", cascade={"all"})
     */
    private $contract;

    /**
     * credi t c ar d
     * 
     * @ORM\OneToOne(targetEntity="MemberCC", mappedBy="member", cascade={"all"})
     */
    private $cc;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", name="country", nullable=true)
     */
    private $country;
    
    /**
     * email untuk login, sebagai master data juga.
     * 
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="string", name="first_name")
     */
    private $firstName;

    /**
     * @ORM\OneToMany(targetEntity="MemberHistory", mappedBy="member", cascade={"all"})
     */
    private $history;

    /**
     * @ORM\OneToMany(targetEntity="\Jariff\AdminBundle\Entity\Inbound", mappedBy="member", cascade={"all"})
     */
    private $inbound;

    /**
     * @ORM\OneToMany(targetEntity="Invoice", mappedBy="member", cascade={"all"})
     */
    private $invoice;

    /**
     * 
     * @ORM\Column(type="datetime", name="last_login_date", nullable=true)
     */
    private $lastLoginDate;

    /**
     * xxxxx
     * @ORM\Column(type="string", name="company", nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" = 0})
     */
    private $training;

    /**
     * @ORM\Column(type="string", name="bill_to_company", nullable=true)
     */
    private $billToCompany;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="bill_to_firstname", nullable=true)
     */
    private $billToFirstname;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="bill_to_lastname", nullable=true)
     */
    private $billToLastname;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="bill_to_street", nullable=true)
     */
    private $billToStreet;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="bill_to_city", nullable=true)
     */
    private $billToCity;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="bill_to_state", nullable=true)
     */
    private $billToState;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="bill_to_zip", nullable=true)
     */
    private $billToZip;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="bill_to_email", nullable=true)
     */
    private $billToEmail;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="bill_to_phone", nullable=true)
     */
    private $billToPhone;

    /**
     * @ORM\Column(type="string", name="ship_to_company", nullable=true)
     */
    private $shipToCompany;

    /**
     * xxxxx
     * @ORM\Column(type="string", name="ship_to_firstname", nullable=true)
     */
    private $shipToFirstname;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="ship_to_lastname", nullable=true)
     */
    private $shipToLastname;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="ship_to_street", nullable=true)
     */
    private $shipToStreet;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="ship_to_city", nullable=true)
     */
    private $shipToCity;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="ship_to_state", nullable=true)
     */
    private $shipToState;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="ship_to_zip", nullable=true)
     */
    private $shipToZip;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="ship_to_email", nullable=true)
     */
    private $shipToEmail;
    /**
     * xxxxx
     * @ORM\Column(type="string", name="ship_to_phone", nullable=true)
     */
    private $shipToPhone;

    /**
     * xxxxx
     * @ORM\Column(type="string", name="state", nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="string", name="last_name", nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", name="last_login_ip", nullable=true)
     */
    private $lastLoginIp;

    /**
     * @ORM\OneToOne(targetEntity="MemberSubscription")
     * @ORM\JoinColumn(name="last_subscription_id", referencedColumnName="id")
     */
    private $lastSubscription;

    /**
     * @ORM\OneToOne(targetEntity="\Jariff\AdminBundle\Entity\Lead", inversedBy="member")
     * @ORM\JoinColumn(name="lead_id", referencedColumnName="id")
     */
    private $lead;

    /**
     * @ORM\ManyToOne(targetEntity="\Jariff\AdminBundle\Entity\Admin", cascade={"all"})
     * @ORM\JoinColumn(name="cs_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $cs;

    /**
     * @ORM\ManyToOne(targetEntity="\Jariff\AdminBundle\Entity\Admin", cascade={"all"})
     * @ORM\JoinColumn(name="sales_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $sales;


    /**
     * @ORM\ManyToOne(targetEntity="\Jariff\AdminBundle\Entity\Admin", cascade={"all"})
     * @ORM\JoinColumn(name="sales2_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $sales2;



    /**
     * @ORM\OneToMany(targetEntity="MemberEmail", mappedBy="member", cascade={"all"})
     */
    private $memberEmail;

    /**
     * @ORM\Column(type="text", name="note", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="string", name="slug", nullable=true)
     */
    private $number;

    // origin diganti jadi pending

    /**
     * UPDATE : mengurangi link, member.roles = [main,child]
     * member pertama roles = main, 
     * yg ditambahkan dia = child
     * 
     * member yg pertama kali daftar dan yg buat company memiliki parent = null
     * member baru yg ditambahkan oleh first employee memiliki parent = first employee
     * 
     * @ORM\ManyToOne(targetEntity="Member")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * password untuk login,
     * lihat algoritma generate nya di Jariff\MemberBundle\Security\PasswordEncoder
     * kalau mau update password atau ngecek kesamaan password gunakan class di atas
     * yg sudah di definisikan sebagai password di Jariff\MemberBundle\Resources\config\service.yml
     * 
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="member", cascade={"all"})
     */
    private $payment;

    /**
     * data pertama kali mendaftar disimpan di tabel pending
     * 
     * @ORM\OneToOne(targetEntity="Pending", mappedBy="member", cascade={"all"})
     * @Assert\NotNull()
     */
    private $pending;

    /**
     * @ORM\Column(type="string", name="phone", nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity="MemberProfile", mappedBy="member", cascade={"all"})
     * @Assert\NotNull()
     */
    private $profile;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $roles;
    
    /**
     * @ORM\Column(type="string")
     */
    private $salt;

    /**
     * @ORM\Column(type="string", name="salutation", nullable=true)
     */
    private $salutation;
    
    /**
     * menyimpan session id dari user yg login,
     * digunakan untuk feature one user one connection
     * jadi tidak bisa digunakan dari 2 browser yg berbeda.
     * 
     * @ORM\Column(type="string", name="session_id", nullable=true)
     */
    private $sessionId;

    /**
     * menyimpan data lokalisasi dari member
     * 
     * @ORM\OneToOne(targetEntity="MemberSetting", mappedBy="member", cascade={"all"})
     * @Assert\NotNull()
     */
    private $setting;

    /**
     * newly: 0,
     * agreement_signed: 5,
     * initial_decline: 10,
     * active: 15,
     * pending_cancellation: 20,
     * cancelled: 25,
     * suspended: 30,
     * expired: 35,
     * 
     * @ORM\Column(type="integer", nullable=true, options={"default" = 0})
     */
    private $status;
    
    /**
     * origin berupa data asli saat pendaftaran pertama kali, 
     * untuk kepentingan dokumentasi data pendaftaran.
     * 
     * @ORM\OneToMany(targetEntity="MemberSubscription", mappedBy="member", cascade={"all"})
     * @Assert\NotNull()
     */
    private $subscription;

    /**
     * @ORM\Column(type="string", nullable=true, unique = true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     */
    private $updatedAt;

    public function canNotSearch(){
      return $this->canSearch() ? false : true; 
    }

    public function canSearch(){
      if (is_null($this->getLastSubscription()) || (!$this->getLastSubscription()->isActive())) {
        return false;
      }
      return true;
    }

    public function getName()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    public function __toString()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    public function getRoles()
    {
        return unserialize($this->roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = serialize($roles);

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        return $this->salt;
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

    public function getStatusString()
    {
        switch ($this->getStatus()) {
            case 0:
                return 'Newly';
                break;
            case 5:
                return 'Agreement Signed';
                break;
            case 10:
                return 'Initial Decline';
                break;
            case 15:
                return 'Active';
                break;
            case 20:
                return 'Pending Cancellation';
                break;
            case 25:
                return 'Cancelled';
                break;
            case 30:
                return 'Suspended';
                break;
            case 35:
                return 'Expired';
                break;
        }
    }
    
    public function __construct(){
        $date = new \DateTime();
        $this->number    = $date->format('mdY') + strval(500 + rand(10,499));
        $this->sessionId = md5(time());
        $this->token     = Util::token('member-token');
        $this->salt      = md5('member'.uniqid().time());
        $this->roles     = 'a:1:{i:0;s:11:"ROLE_MEMBER";}';
        $this->createdAt = $date;
        $this->updatedAt = $date;
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
     * Set email
     *
     * @param string $email
     * @return Member
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set lastLoginDate
     *
     * @param \DateTime $lastLoginDate
     * @return Member
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
     * @return Member
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
     * Set number
     *
     * @param string $number
     * @return Member
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Member
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Member
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Set sessionId
     *
     * @param string $sessionId
     * @return Member
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    
        return $this;
    }

    /**
     * Get sessionId
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Member
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Member
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
     * Add arb
     *
     * @param \Jariff\MemberBundle\Entity\MemberARB $arb
     * @return Member
     */
    public function addArb(\Jariff\MemberBundle\Entity\MemberARB $arb)
    {
        $this->arb[] = $arb;
    
        return $this;
    }

    /**
     * Remove arb
     *
     * @param \Jariff\MemberBundle\Entity\MemberARB $arb
     */
    public function removeArb(\Jariff\MemberBundle\Entity\MemberARB $arb)
    {
        $this->arb->removeElement($arb);
    }

    /**
     * Get arb
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArb()
    {
        return $this->arb;
    }

    /**
     * Set cc
     *
     * @param \Jariff\MemberBundle\Entity\MemberCC $cc
     * @return Member
     */
    public function setCc(\Jariff\MemberBundle\Entity\MemberCC $cc = null)
    {
        $this->cc = $cc;
    
        return $this;
    }

    /**
     * Get cc
     *
     * @return \Jariff\MemberBundle\Entity\MemberCC 
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * Add memberEmail
     *
     * @param \Jariff\MemberBundle\Entity\MemberEmail $memberEmail
     * @return Member
     */
    public function addMemberEmail(\Jariff\MemberBundle\Entity\MemberEmail $memberEmail)
    {
        $this->memberEmail[] = $memberEmail;
    
        return $this;
    }

    /**
     * Remove memberEmail
     *
     * @param \Jariff\MemberBundle\Entity\MemberEmail $memberEmail
     */
    public function removeMemberEmail(\Jariff\MemberBundle\Entity\MemberEmail $memberEmail)
    {
        $this->memberEmail->removeElement($memberEmail);
    }

    /**
     * Get memberEmail
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMemberEmail()
    {
        return $this->memberEmail;
    }

    /**
     * Add history
     *
     * @param \Jariff\MemberBundle\Entity\MemberHistory $history
     * @return Member
     */
    public function addHistory(\Jariff\MemberBundle\Entity\MemberHistory $history)
    {
        $this->history[] = $history;
    
        return $this;
    }

    /**
     * Remove history
     *
     * @param \Jariff\MemberBundle\Entity\MemberHistory $history
     */
    public function removeHistory(\Jariff\MemberBundle\Entity\MemberHistory $history)
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
     * Add inbound
     *
     * @param \Jariff\AdminBundle\Entity\Inbound $inbound
     * @return Member
     */
    public function addInbound(\Jariff\AdminBundle\Entity\Inbound $inbound)
    {
        $this->inbound[] = $inbound;
    
        return $this;
    }

    /**
     * Remove inbound
     *
     * @param \Jariff\AdminBundle\Entity\Inbound $inbound
     */
    public function removeInbound(\Jariff\AdminBundle\Entity\Inbound $inbound)
    {
        $this->inbound->removeElement($inbound);
    }

    /**
     * Get inbound
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInbound()
    {
        return $this->inbound;
    }

    /**
     * Add invoice
     *
     * @param \Jariff\MemberBundle\Entity\Invoice $invoice
     * @return Member
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
     * Set lead
     *
     * @param \Jariff\AdminBundle\Entity\Lead $lead
     * @return Member
     */
    public function setLead(\Jariff\AdminBundle\Entity\Lead $lead = null)
    {
        $this->lead = $lead;
    
        return $this;
    }

    /**
     * Get lead
     *
     * @return \Jariff\AdminBundle\Entity\Lead 
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * Set parent
     *
     * @param \Jariff\MemberBundle\Entity\Member $parent
     * @return Member
     */
    public function setParent(\Jariff\MemberBundle\Entity\Member $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Jariff\MemberBundle\Entity\Member 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add payment
     *
     * @param \Jariff\MemberBundle\Entity\Payment $payment
     * @return Member
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
     * Set pending
     *
     * @param \Jariff\MemberBundle\Entity\Pending $pending
     * @return Member
     */
    public function setPending(\Jariff\MemberBundle\Entity\Pending $pending = null)
    {
        $this->pending = $pending;
    
        return $this;
    }

    /**
     * Get pending
     *
     * @return \Jariff\MemberBundle\Entity\Pending 
     */
    public function getPending()
    {
        return $this->pending;
    }

    /**
     * Set profile
     *
     * @param \Jariff\MemberBundle\Entity\MemberProfile $profile
     * @return Member
     */
    public function setProfile(\Jariff\MemberBundle\Entity\MemberProfile $profile = null)
    {
        $this->profile = $profile;
    
        return $this;
    }

    /**
     * Get profile
     *
     * @return \Jariff\MemberBundle\Entity\MemberProfile 
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set setting
     *
     * @param \Jariff\MemberBundle\Entity\MemberSetting $setting
     * @return Member
     */
    public function setSetting(\Jariff\MemberBundle\Entity\MemberSetting $setting = null)
    {
        $this->setting = $setting;
    
        return $this;
    }

    /**
     * Get setting
     *
     * @return \Jariff\MemberBundle\Entity\MemberSetting 
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * Add subscription
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscription $subscription
     * @return Member
     */
    public function addSubscription(\Jariff\MemberBundle\Entity\MemberSubscription $subscription)
    {
        $this->subscription[] = $subscription;
        $subscription->setMember($this);
    
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Member
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Member
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Member
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Member
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Member
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
     * Set phone
     *
     * @param string $phone
     * @return Member
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set salutation
     *
     * @param string $salutation
     * @return Member
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
    
        return $this;
    }

    /**
     * Get salutation
     *
     * @return string 
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Member
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
     * Set slug
     *
     * @param string $slug
     * @return Member
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set lastSubscription
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscription $lastSubscription
     * @return Member
     */
    public function setLastSubscription(\Jariff\MemberBundle\Entity\MemberSubscription $lastSubscription = null)
    {
        $this->lastSubscription = $lastSubscription;
    
        return $this;
    }

    /**
     * Get lastSubscription
     *
     * @return \Jariff\MemberBundle\Entity\MemberSubscription 
     */
    public function getLastSubscription()
    {
        return $this->lastSubscription;
    }

    /**
     * Set billToFirstname
     *
     * @param string $billToFirstname
     * @return Member
     */
    public function setBillToFirstname($billToFirstname)
    {
        $this->billToFirstname = $billToFirstname;
    
        return $this;
    }

    /**
     * Get billToFirstname
     *
     * @return string 
     */
    public function getBillToFirstname()
    {
        return $this->billToFirstname;
    }

    /**
     * Set billToLastname
     *
     * @param string $billToLastname
     * @return Member
     */
    public function setBillToLastname($billToLastname)
    {
        $this->billToLastname = $billToLastname;
    
        return $this;
    }

    /**
     * Get billToLastname
     *
     * @return string 
     */
    public function getBillToLastname()
    {
        return $this->billToLastname;
    }

    /**
     * Set billToStreet
     *
     * @param string $billToStreet
     * @return Member
     */
    public function setBillToStreet($billToStreet)
    {
        $this->billToStreet = $billToStreet;
    
        return $this;
    }

    /**
     * Get billToStreet
     *
     * @return string 
     */
    public function getBillToStreet()
    {
        return $this->billToStreet;
    }

    /**
     * Set billToCity
     *
     * @param string $billToCity
     * @return Member
     */
    public function setBillToCity($billToCity)
    {
        $this->billToCity = $billToCity;
    
        return $this;
    }

    /**
     * Get billToCity
     *
     * @return string 
     */
    public function getBillToCity()
    {
        return $this->billToCity;
    }

    /**
     * Set billToState
     *
     * @param string $billToState
     * @return Member
     */
    public function setBillToState($billToState)
    {
        $this->billToState = $billToState;
    
        return $this;
    }

    /**
     * Get billToState
     *
     * @return string 
     */
    public function getBillToState()
    {
        return $this->billToState;
    }

    /**
     * Set billToZip
     *
     * @param string $billToZip
     * @return Member
     */
    public function setBillToZip($billToZip)
    {
        $this->billToZip = $billToZip;
    
        return $this;
    }

    /**
     * Get billToZip
     *
     * @return string 
     */
    public function getBillToZip()
    {
        return $this->billToZip;
    }

    /**
     * Set billToEmail
     *
     * @param string $billToEmail
     * @return Member
     */
    public function setBillToEmail($billToEmail)
    {
        $this->billToEmail = $billToEmail;
    
        return $this;
    }

    /**
     * Get billToEmail
     *
     * @return string 
     */
    public function getBillToEmail()
    {
        return $this->billToEmail;
    }

    /**
     * Set billToPhone
     *
     * @param string $billToPhone
     * @return Member
     */
    public function setBillToPhone($billToPhone)
    {
        $this->billToPhone = $billToPhone;
    
        return $this;
    }

    /**
     * Get billToPhone
     *
     * @return string 
     */
    public function getBillToPhone()
    {
        return $this->billToPhone;
    }

    /**
     * Set shipToFirstname
     *
     * @param string $shipToFirstname
     * @return Member
     */
    public function setShipToFirstname($shipToFirstname)
    {
        $this->shipToFirstname = $shipToFirstname;
    
        return $this;
    }

    /**
     * Get shipToFirstname
     *
     * @return string 
     */
    public function getShipToFirstname()
    {
        return $this->shipToFirstname;
    }

    /**
     * Set shipToLastname
     *
     * @param string $shipToLastname
     * @return Member
     */
    public function setShipToLastname($shipToLastname)
    {
        $this->shipToLastname = $shipToLastname;
    
        return $this;
    }

    /**
     * Get shipToLastname
     *
     * @return string 
     */
    public function getShipToLastname()
    {
        return $this->shipToLastname;
    }

    /**
     * Set shipToStreet
     *
     * @param string $shipToStreet
     * @return Member
     */
    public function setShipToStreet($shipToStreet)
    {
        $this->shipToStreet = $shipToStreet;
    
        return $this;
    }

    /**
     * Get shipToStreet
     *
     * @return string 
     */
    public function getShipToStreet()
    {
        return $this->shipToStreet;
    }

    /**
     * Set shipToCity
     *
     * @param string $shipToCity
     * @return Member
     */
    public function setShipToCity($shipToCity)
    {
        $this->shipToCity = $shipToCity;
    
        return $this;
    }

    /**
     * Get shipToCity
     *
     * @return string 
     */
    public function getShipToCity()
    {
        return $this->shipToCity;
    }

    /**
     * Set shipToState
     *
     * @param string $shipToState
     * @return Member
     */
    public function setShipToState($shipToState)
    {
        $this->shipToState = $shipToState;
    
        return $this;
    }

    /**
     * Get shipToState
     *
     * @return string 
     */
    public function getShipToState()
    {
        return $this->shipToState;
    }

    /**
     * Set shipToZip
     *
     * @param string $shipToZip
     * @return Member
     */
    public function setShipToZip($shipToZip)
    {
        $this->shipToZip = $shipToZip;
    
        return $this;
    }

    /**
     * Get shipToZip
     *
     * @return string 
     */
    public function getShipToZip()
    {
        return $this->shipToZip;
    }

    /**
     * Set shipToEmail
     *
     * @param string $shipToEmail
     * @return Member
     */
    public function setShipToEmail($shipToEmail)
    {
        $this->shipToEmail = $shipToEmail;
    
        return $this;
    }

    /**
     * Get shipToEmail
     *
     * @return string 
     */
    public function getShipToEmail()
    {
        return $this->shipToEmail;
    }

    /**
     * Set shipToPhone
     *
     * @param string $shipToPhone
     * @return Member
     */
    public function setShipToPhone($shipToPhone)
    {
        $this->shipToPhone = $shipToPhone;
    
        return $this;
    }

    /**
     * Get shipToPhone
     *
     * @return string 
     */
    public function getShipToPhone()
    {
        return $this->shipToPhone;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return Member
     */
    public function setCompany($company)
    {
        $this->company = $company;
    
        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set cs
     *
     * @param \Jariff\AdminBundle\Entity\Admin $cs
     * @return Member
     */
    public function setCs(\Jariff\AdminBundle\Entity\Admin $cs = null)
    {
        $this->cs = $cs;
    
        return $this;
    }

    /**
     * Get cs
     *
     * @return \Jariff\AdminBundle\Entity\Admin 
     */
    public function getCs()
    {
        return $this->cs;
    }

    /**
     * Set sales
     *
     * @param \Jariff\AdminBundle\Entity\Admin $sales
     * @return Member
     */
    public function setSales(\Jariff\AdminBundle\Entity\Admin $sales = null)
    {
        $this->sales = $sales;
    
        return $this;
    }

    /**
     * Get sales
     *
     * @return \Jariff\AdminBundle\Entity\Admin 
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * Set sales2
     *
     * @param \Jariff\AdminBundle\Entity\Admin $sales2
     * @return Member
     */
    public function setSales2(\Jariff\AdminBundle\Entity\Admin $sales2 = null)
    {
        $this->sales2 = $sales2;
    
        return $this;
    }

    /**
     * Get sales2
     *
     * @return \Jariff\AdminBundle\Entity\Admin 
     */
    public function getSales2()
    {
        return $this->sales2;
    }

    /**
     * Set billToCompany
     *
     * @param string $billToCompany
     * @return Member
     */
    public function setBillToCompany($billToCompany)
    {
        $this->billToCompany = $billToCompany;
    
        return $this;
    }

    /**
     * Get billToCompany
     *
     * @return string 
     */
    public function getBillToCompany()
    {
        return $this->billToCompany;
    }

    /**
     * Set shipToCompany
     *
     * @param string $shipToCompany
     * @return Member
     */
    public function setShipToCompany($shipToCompany)
    {
        $this->shipToCompany = $shipToCompany;
    
        return $this;
    }

    /**
     * Get shipToCompany
     *
     * @return string 
     */
    public function getShipToCompany()
    {
        return $this->shipToCompany;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Member
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set training
     *
     * @param integer $training
     * @return Member
     */
    public function setTraining($training)
    {
        $this->training = $training;
    
        return $this;
    }

    /**
     * Get training
     *
     * @return integer 
     */
    public function getTraining()
    {
        return $this->training;
    }

    /**
     * Add contract
     *
     * @param \Jariff\MemberBundle\Entity\MemberContract $contract
     * @return Member
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