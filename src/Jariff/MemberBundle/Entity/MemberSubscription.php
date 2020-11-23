<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Jariff\ProjectBundle\Util\Util;

/**
 * Jariff\MemberBundle\Entity\MemberSubscription
 *
 * @ORM\Table(name="member_subscriptions", indexes={
 *     @ORM\Index(name="index_member_subscriptions_on_slug", columns="slug"),
 * })
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberSubscriptionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberSubscription
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
     * hanya satu active subscription
     * @ORM\Column(type="integer", name="status", options={"default" = 0})
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="MemberSubscriptionActivity", mappedBy="subscription", cascade={"all"})
     */
    private $activity;

    /**
     * false = $0
     * true = $30
     *
     * ini bagian dollar nya : 0, 30
     *
     * @ORM\Column(type="integer", name="big_picture_price")
     */
    private $bigPicture;

    /**
     * ini bagian true / false nya
     *
     * @ORM\Column(type="boolean", name="big_picture_value", options={"default" = 0})
     */
    private $bigPictureValue;

    /**
     * @ORM\OneToOne(targetEntity="MemberContract", mappedBy="subscription")
     * @ORM\JoinColumn(name="member_contract_id", referencedColumnName="id")
     */
    protected $contract;

    /**
     * @ORM\OneToOne(targetEntity="MemberTransaction", mappedBy="subscription")
     * @ORM\JoinColumn(name="member_transaction_id", referencedColumnName="id")
     */
    protected $transaction;

    /**
     * discount yg masukin admin,
     * bukan dari bonus PIF
     *
     * @ORM\Column(type="integer", name="custom_discount", options={"default" = 0})
     */
    private $customDiscount;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $dateCreate;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    private $dateUpdate;

    /**
     * demo => expired tomorrow
     * yg lainnya sesuai paket month yg diambil
     *
     * @ORM\Column(type="datetime", nullable=true, name="active_at")
     */
    private $dateActivated;

    /**
     * demo => expired tomorrow
     * yg lainnya sesuai paket month yg diambil
     *
     * @ORM\Column(type="datetime", nullable=true, name="expire_at")
     */
    private $dateExpired;

    /**
     * demo => expired tomorrow
     * yg lainnya sesuai paket month yg diambil
     *
     * @ORM\Column(type="datetime", nullable=true, name="cancel_at")
     */
    private $dateCancelled;

    /**
     * demo => expired tomorrow
     * yg lainnya sesuai paket month yg diambil
     *
     * @ORM\Column(type="datetime", nullable=true, name="suspend_at")
     */
    private $dateSuspended;

    /**
     * discount jika bayar PIF (3,6,12 bulan)
     *
     * @ORM\Column(type="integer", name="discount", options={"default" = 0})
     */
    private $discount;

    /**
     * @ORM\Column(type="string", nullable=true, name="document_key", options={"default" = ""})
     */
    private $documentKey;

    /**
     * @ORM\Column(type="string", nullable=true, name="document_status", options={"default" = ""})
     */
    private $documentStatus;

    /**
     * @ORM\Column(type="string", nullable=true, name="document_url", options={"default" = ""})
     */
    private $documentUrl;

    /**
     * 0 max download per month        = $0
     * 1000 max download per month     = $30
     * 5000 max download per month     = $40
     * 25000 max download per month    = $60
     * 100000 max download per month   = $70
     * 10000000 max download per month = $80
     *
     * disini bagian dollar nya : 0, 30, 40, 60, 70, 80
     *
     * @ORM\Column(type="integer", name="download_price")
     */
    private $download;

    /**
     * disini bagian jumlah download nya
     *
     * @ORM\Column(type="integer", name="download_value")
     */
    private $downloadValue;

    /**
     * true / false
     * kalau akun nya ikut everything plan, maka setiap action tidak usah di cek quotanya,
     * tetapi tetap di log buat report
     * kalau false, maka getPlan()
     *
     * @ORM\Column(type="boolean", name="everything_plan", options={"default" = 0})
     */
    private $everythingPlan;

    /**
     * last 18 months = $59
     * last 36 months = $99
     * last 60 months = $150
     *
     * disini berupa dollarnya : 59, 99, 150
     *
     * @ORM\Column(type="integer", name="history_price")
     */
    private $history;

    /**
     * disini berupa jumlah bulannya : 18, 36, 60
     *
     * @ORM\Column(type="integer", name="history_value")
     */
    private $historyValue;

    /**
     * @ORM\OneToMany(targetEntity="Invoice", mappedBy="subscription", cascade={"all"})
     */
    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="subscription", cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * bayar 12 bulan dimuka dapat potongan = 20%
     * bayar 6 bulan dimuka dapat potongan = 15%
     * bayar 3 bulan dimuka dapat potongan = 10%
     *
     * disini adalah nilai diskonnya : 20, 15, 10
     *
     * @ORM\Column(type="integer", name="month_discount", options={"default" = 0})
     */
    private $month;

    /**
     * disini adalah nilai berapa bulannya : 12, 6, 3
     *
     * @ORM\Column(type="integer", name="month_value", options={"default" = 0})
     */
    private $monthValue;

    /**
     * @ORM\Column(type="string", name="slug")
     */
    private $number;

    /**
     * pif = Paid in full (Pay Up Front)
     * mtm = Month to Month (Automated Recurring Billing)
     * @ORM\Column(type="string", name="payment_term")
     */
    private $paymentTerm;

    /**
     * 5 max search per day = $0
     * 25 max search per day = $10
     * 50 max search per day = $20
     * 10000 max search per day = $35 (unlimited)
     *
     * disini adalah nilai dollarnya : 0, 10, 20, 35
     *
     * @ORM\Column(type="integer", name="search_price")
     */
    private $search;

    /**
     * disini adalah nilai max search per day nya : 5, 25, 50, 10000
     * @ORM\Column(type="integer", name="search_value")
     */
    private $searchValue;

    /**
     * untuk subscription ini, member nya sudah ditraining belum,
     * dibuat persubscription, karena mungkin subscrption #1 belum full feature
     * trainingnya hanya standard search saja.
     * baru setelah n+1 subscription baru yg full feature dan perlu penjelasan
     *
     * @ ORM\Column(type="boolean", name="")
     */
    private $training;

    /**
     * @ORM\Column(type="string", unique=true, name="token")
     * @Assert\NotBlank()
     */
    protected $token;

    /**
     * @ORM\Column(type="integer", name="total")
     */
    private $total;


    /**
     * 1 = 1 Countries, 2 = 3 Countries, 3 = All Countries
     * @ORM\Column(type="text", name="addons")
     *, options={"default"="a:1:{s:21:'country_subscriptions';a:1:{i:0;s:10:'us-imports'}}"}
     * http://stackoverflow.com/questions/3466872/why-cant-a-text-column-have-a-default-value-in-mysql
     * coded manually in controller
     */
    private $addons;


    /**
     * disini bagian jumlah download nya
     *
     * @ORM\Column(type="integer", name="download_limit", options={"default"=0})
     */
    private $downloadLimit;

    /**
     * disini bagian jumlah download nya
     *
     * @ORM\Column(type="integer", name="total_searches", options={"default"=0})
     */
    private $totalSearches;

    /**
     * @ORM\ManyToMany(targetEntity="Country", inversedBy="subscriptions")
     * @ORM\JoinTable(name="country_subscription")
     */
    private $countries;

    public function isActive()
    {
      // status == active or status == pending_cancellation
      if(intval($this->getActive()) === 3 OR intval($this->getActive()) === 6){
        return true;
      }
      return false;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersistPreUpdate()
    {

        $price = 0;
        if ($this->getEverythingPlan()) {
            $price = 200;
        } else {
            $price = $this->getHistory() + $this->getSearch() + $this->getDownload() + $this->getBigPicture();
        }

        // calculate discount based on pif / mtm
        $total_discount = 0;
        if ($this->getPaymentTerm() == 'mtm') {
            $total_discount = 0;
        } else if ($this->getPaymentTerm() == 'pif') {
            $total_discount = round($price * ($this->getMonth() / 100) * $this->getMonthValue());
        }

        // update total price
        $this->total = $price * $this->getMonthValue() - $total_discount - $this->getDiscount();
    }

    public function getMonthValue()
    {
        switch ($this->month) {
            case 0 :
                return 1;
            case 10 :
                return 3;
            case 15 :
                return 6;
            case 20 :
                return 12;
            default :
                throw new \Exception('Undefined month value');
        }
    }

    /**
     * $subscriptions = $this->repo('JariffAdminBundle:Subscription')->findAll();
     * foreach ($subscriptions as $subscription) {
     *     $data[$subscription->getCountry()][$subscription->getPrice()] = $subscription->getValue();
     * }
     */
    public function updateValue($data)
    {
        if (array_key_exists('history', $data) AND
            array_key_exists('big_bicture', $data) AND
            array_key_exists('hoho', $data) AND
            array_key_exists('search', $data) AND
            array_key_exists('month', $data) AND
            array_key_exists('download', $data)
        ) {
            throw new \Exception('Invalid subscription data');
        } 
        // var_dump($this->getDownloadLimit());
        // die();
        $this->setHistoryValue($data['history'][$this->getHistory()]);
        $this->setBigPictureValue($data['big_picture'][$this->getBigPicture()]);
        $this->setSearchValue($data['search'][$this->getSearch()]);
        $this->setMonthValue($data['month'][$this->getMonth()]);
        $this->setDownloadValue($data['download'][$this->getDownload()]);
        $this->setDownloadLimit($data['download'][$this->getDownload()] + $data['download'][$this->getDownloadLimit()]);
        
    }

    public function __toString()
    {
        return $this->number . ' ( $ ' . $this->total . ' )';
    }

    public function __construct()
    {
        $this->canceled = false;
        $this->everythingPlan = false;
        $this->active = false;
        $this->training = false;
        $this->dateCreate = new \DateTime();
        $this->number = 'subs#' + rand(1000, 9999);
        $this->customDiscount = 0;
        $this->addons = "a:1:{s:21:'country_subscriptions';a:1:{i:0;s:10:'us-imports'}}";
        $this->token = Util::token('subscription');
        $this->totalSearches = 0;
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
     * Set history
     *
     * @param integer $history
     * @return MemberSubscription
     */
    public function setHistory($history)
    {
        $this->history = $history;

        return $this;
    }

    /**
     * Get history
     *
     * @return integer
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Set historyValue
     *
     * @param integer $historyValue
     * @return MemberSubscription
     */
    public function setHistoryValue($historyValue)
    {
        $this->historyValue = $historyValue;

        return $this;
    }

    /**
     * Get historyValue
     *
     * @return integer
     */
    public function getHistoryValue()
    {
        return $this->historyValue;
    }

    /**
     * Set bigPicture
     *
     * @param boolean $bigPicture
     * @return MemberSubscription
     */
    public function setBigPicture($bigPicture)
    {
        $this->bigPicture = $bigPicture;

        return $this;
    }

    /**
     * Get bigPicture
     *
     * @return boolean
     */
    public function getBigPicture()
    {
        return $this->bigPicture;
    }

    /**
     * Set bigPictureValue
     *
     * @param integer $bigPictureValue
     * @return MemberSubscription
     */
    public function setBigPictureValue($bigPictureValue)
    {
        $this->bigPictureValue = $bigPictureValue;

        return $this;
    }

    /**
     * Get bigPictureValue
     *
     * @return integer
     */
    public function getBigPictureValue()
    {
        return $this->bigPictureValue;
    }

    /**
     * Set search
     *
     * @param integer $search
     * @return MemberSubscription
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get search
     *
     * @return integer
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set searchValue
     *
     * @param integer $searchValue
     * @return MemberSubscription
     */
    public function setSearchValue($searchValue)
    {
        $this->searchValue = $searchValue;

        return $this;
    }

    /**
     * Get searchValue
     *
     * @return integer
     */
    public function getSearchValue()
    {
        return $this->searchValue;
    }

    /**
     * Set download
     *
     * @param integer $download
     * @return MemberSubscription
     */
    public function setDownload($download)
    {
        $this->download = $download;

        return $this;
    }

    /**
     * Get download
     *
     * @return integer
     */
    public function getDownload()
    {
        return $this->download;
    }

    /**
     * Set downloadValue
     *
     * @param integer $downloadValue
     * @return MemberSubscription
     */
    public function setDownloadValue($downloadValue)
    {
        $this->downloadValue = $downloadValue;

        return $this;
    }

    /**
     * Get downloadValue
     *
     * @return integer
     */
    public function getDownloadValue()
    {
        return $this->downloadValue;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return MemberSubscription
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
     * Set training
     *
     * @param string $training
     * @return MemberSubscription
     */
    public function setTraining($training)
    {
        $this->training = $training;

        return $this;
    }

    /**
     * Get training
     *
     * @return string
     */
    public function getTraining()
    {
        return $this->training;
    }

    /**
     * Set owner
     *
     * @param \Jariff\AdminBundle\Entity\Admin $owner
     * @return MemberSubscription
     */
    public function setOwner(\Jariff\AdminBundle\Entity\Admin $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Jariff\AdminBundle\Entity\Admin
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set sales2
     *
     * @param \Jariff\AdminBundle\Entity\Admin $sales2
     * @return MemberSubscription
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
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberSubscription
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
     * Add invoice
     *
     * @param \Jariff\MemberBundle\Entity\Invoice $invoice
     * @return MemberSubscription
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
     * Set discount
     *
     * @param integer $discount
     * @return MemberSubscription
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set everythingPlan
     *
     * @param boolean $everythingPlan
     * @return MemberSubscription
     */
    public function setEverythingPlan($everythingPlan)
    {
        $this->everythingPlan = $everythingPlan;

        return $this;
    }

    /**
     * Get everythingPlan
     *
     * @return boolean
     */
    public function getEverythingPlan()
    {
        return $this->everythingPlan;
    }

    /**
     * Set total
     *
     * @param integer $total
     * @return MemberSubscription
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set month
     *
     * @param integer $month
     * @return MemberSubscription
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set paymentTerm
     *
     * @param string $paymentTerm
     * @return MemberSubscription
     */
    public function setPaymentTerm($paymentTerm)
    {
        $this->paymentTerm = $paymentTerm;

        return $this;
    }

    /**
     * Get paymentTerm
     *
     * @return string
     */
    public function getPaymentTerm()
    {
        return $this->paymentTerm;
    }

    /**
     * Set customDiscount
     *
     * @param integer $customDiscount
     * @return MemberSubscription
     */
    public function setCustomDiscount($customDiscount)
    {
        $this->customDiscount = $customDiscount;

        return $this;
    }

    /**
     * Get customDiscount
     *
     * @return integer
     */
    public function getCustomDiscount()
    {
        return $this->customDiscount;
    }

    /**
     * Set canceled
     *
     * @param boolean $canceled
     * @return MemberSubscription
     */
    public function setCanceled($canceled)
    {
        $this->canceled = $canceled;

        return $this;
    }

    /**
     * Get canceled
     *
     * @return boolean
     */
    public function getCanceled()
    {
        return $this->canceled;
    }

    /**
     * Set monthValue
     *
     * @param integer $monthValue
     * @return MemberSubscription
     */
    public function setMonthValue($monthValue)
    {
        $this->monthValue = $monthValue;

        return $this;
    }

    /**
     * Set documentKey
     *
     * @param string $documentKey
     * @return MemberSubscription
     */
    public function setDocumentKey($documentKey)
    {
        $this->documentKey = $documentKey;

        return $this;
    }

    /**
     * Get documentKey
     *
     * @return string
     */
    public function getDocumentKey()
    {
        return $this->documentKey;
    }

    /**
     * Set documentStatus
     *
     * @param string $documentStatus
     * @return MemberSubscription
     */
    public function setDocumentStatus($documentStatus)
    {
        $this->documentStatus = $documentStatus;

        return $this;
    }

    /**
     * Get documentStatus
     *
     * @return string
     */
    public function getDocumentStatus()
    {
        return $this->documentStatus;
    }

    /**
     * Set documentUrl
     *
     * @param string $documentUrl
     * @return MemberSubscription
     */
    public function setDocumentUrl($documentUrl)
    {
        $this->documentUrl = $documentUrl;

        return $this;
    }

    /**
     * Get documentUrl
     *
     * @return string
     */
    public function getDocumentUrl()
    {
        return $this->documentUrl;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return MemberSubscription
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Get addons
     *
     * @return integer
     */
    public function getAddons()
    {
        return $this->addons;
    }

    /**
     * Set dateExpired
     *
     * @param \DateTime $dateExpired
     * @return MemberSubscription
     */
    public function setDateExpired($dateExpired)
    {
        $this->dateExpired = $dateExpired;

        return $this;
    }

    /**
     * Get dateExpired
     *
     * @return \DateTime
     */
    public function getDateExpired()
    {
        return $this->dateExpired;
    }

    /**
     * Add activity
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscriptionActivity $activity
     * @return MemberSubscription
     */
    public function addActivity(\Jariff\MemberBundle\Entity\MemberSubscriptionActivity $activity)
    {
        $this->activity[] = $activity;

        return $this;
    }

    /**
     * Remove activity
     *
     * @param \Jariff\MemberBundle\Entity\MemberSubscriptionActivity $activity
     */
    public function removeActivity(\Jariff\MemberBundle\Entity\MemberSubscriptionActivity $activity)
    {
        $this->activity->removeElement($activity);
    }

    /**
     * Get activity
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return MemberSubscription
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
     * Set token
     *
     * @param string $token
     * @return MemberSubscription
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
     * Set dateActivated
     *
     * @param \DateTime $dateActivated
     * @return MemberSubscription
     */
    public function setDateActivated($dateActivated)
    {
        $this->dateActivated = $dateActivated;

        return $this;
    }

    /**
     * Get dateActivated
     *
     * @return \DateTime
     */
    public function getDateActivated()
    {
        return $this->dateActivated;
    }

    /**
     *
     * Set downloadLimit
     *
     * @param integer $downloadLimit
     * @return MemberSubscription
     */
    public function setDownloadLimit($downloadLimit)
    {
        $this->downloadLimit = $downloadLimit;
    }

    /** Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * @return MemberSubscription
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;


        return $this;
    }

    /**
     * Get downloadLimit
     *
     * @return integer
     */
    public function getDownloadLimit()
    {
        return $this->downloadLimit;
    }

    /** Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set countries
     *
     * @param \Doctrine\Common\Collections\Collection $countries
     * @return MemberSubscription
     */
    public function setCountries($countries)
    {
        $this->countries = $countries;

        return $this;
    }

    /**
     * Get countries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCountries()
    {
        return $this->countries;
    }


    /**
     * Set addons
     *
     * @param string $addons
     * @return MemberSubscription
     */
    public function setAddons($addons)
    {
        $this->addons = $addons;
    
        return $this;
    }

    /**
     * Add countries
     *
     * @param \Jariff\MemberBundle\Entity\Country $countries
     * @return MemberSubscription
     */
    public function addCountrie(\Jariff\MemberBundle\Entity\Country $countries)
    {
        $this->countries[] = $countries;
    
        return $this;
    }

    /**
     * Remove countries
     *
     * @param \Jariff\MemberBundle\Entity\Country $countries
     */
    public function removeCountrie(\Jariff\MemberBundle\Entity\Country $countries)
    {
        $this->countries->removeElement($countries);
    }

    /**
     * Set dateCancelled
     *
     * @param \DateTime $dateCancelled
     * @return MemberSubscription
     */
    public function setDateCancelled($dateCancelled)
    {
        $this->dateCancelled = $dateCancelled;
    
        return $this;
    }

    /**
     * Get dateCancelled
     *
     * @return \DateTime 
     */
    public function getDateCancelled()
    {
        return $this->dateCancelled;
    }

    /**
     * Set dateSuspended
     *
     * @param \DateTime $dateSuspended
     * @return MemberSubscription
     */
    public function setDateSuspended($dateSuspended)
    {
        $this->dateSuspended = $dateSuspended;
    
        return $this;
    }

    /**
     * Get dateSuspended
     *
     * @return \DateTime 
     */
    public function getDateSuspended()
    {
        return $this->dateSuspended;
    }

    /**
     * Set totalSearches
     *
     * @param integer $totalSearches
     * @return MemberSubscription
     */
    public function setTotalSearches($totalSearches)
    {
        $this->totalSearches = $totalSearches;
    
        return $this;
    }

    /**
     * Get totalSearches
     *
     * @return integer 
     */
    public function getTotalSearches()
    {
        return $this->totalSearches;
    }

    /**
     * Set contract
     *
     * @param \Jariff\MemberBundle\Entity\MemberContract $contract
     * @return MemberSubscription
     */
    public function setContract(\Jariff\MemberBundle\Entity\MemberContract $contract = null)
    {
        $this->contract = $contract;
    
        return $this;
    }

    /**
     * Get contract
     *
     * @return \Jariff\MemberBundle\Entity\MemberContract 
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Set transaction
     *
     * @param \Jariff\MemberBundle\Entity\MemberTransaction $transaction
     * @return MemberSubscription
     */
    public function setTransaction(\Jariff\MemberBundle\Entity\MemberTransaction $transaction = null)
    {
        $this->transaction = $transaction;
    
        return $this;
    }

    /**
     * Get transaction
     *
     * @return \Jariff\MemberBundle\Entity\MemberTransaction 
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}