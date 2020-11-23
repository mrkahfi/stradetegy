<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Jariff\ProjectBundle\Util\Util;

/**
 * Jariff\MemberBundle\Entity\Attachment
 *
 * @ORM\Table(name="member_attachment")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberAttachmentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberAttachment
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
     * Subscription
     * Payment
     * 
     * @ORM\Column(type="string")
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreate;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="attachment")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * Subscription History : 
     *     Successfully Sent,
     *     Successfully Signed
     * Payment History : 
     *     Successfully Uploaded
     *     Successfully Charged
     *     Failed
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    private $result;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * Subscription History : 
     *     Report, 
     *     Contract
     * Payment History : 
     *     Wire Payment Advice
     *     Paypal Payment Advice
     *     Credit Card Charge
     *     Invoice
     * 
     * @ORM\Column(type="string")
     */
    private $type;

    //start upload config
    /**
     * @Assert\File(maxSize="5M")
     */
    private $attachmentFile;

    /**
     * @var string $attachment
     *
     * @ORM\Column(name="attachment", type="string", length=255)
     */
    private $attachment;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        //handle upload file
        if (null !== $this->attachmentFile) {
            $filename    = rand(100000,999999);
            $slug        = Util::slugify($this->attachmentFile->getClientOriginalName());
            $this->attachment = 'attachment/'.date('Y/m/d').'/'.$slug.'-'.$filename.'.'.$this->attachmentFile->guessExtension();
        }        
            // var_dump($this->attachmentFile);die();
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        //handle upload file
        if (null === $this->attachmentFile) {
            return;
        } else {
            $this->attachmentFile->move($this->getAttachmentUploadRootDir().'/attachment/'.date('/Y/m/d'), $this->attachment);
            unset($this->attachmentFile);
        }
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        //handle upload file gambar
        if ($file = $this->getAttachmentAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Attachment upload
     */
    public function getAttachmentAbsolutePath()
    {
        return null === $this->attachment ? null : $this->getAttachmentUploadRootDir().'/'.$this->attachment;
    }

    /**
     * Attachment upload
     */
    public function getAttachmentWebPath()
    {
        return null === $this->attachment ? null : $this->getAttachmentUploadDir().'/'.$this->attachment;
    }

    /**
     * Attachment upload
     */
    protected function getAttachmentUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getAttachmentUploadDir();
    }

    /**
     * Attachment upload
     */
    protected function getAttachmentUploadDir()
    {
        return 'uploads';
    }
    
    public function setAttachmentFile($attachmentFile)
    {
        $this->attachmentFile = $attachmentFile;
        return $this;
    }
    
    public function getAttachmentFile()
    {
        return $this->attachmentFile;
    }
    //end upload config

    /**
     * @ORM\PrePersist()
     */
    public function PrePersist()
    {
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
     * Set category
     *
     * @param string $category
     * @return MemberAttachment
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return MemberAttachment
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
     * Set result
     *
     * @param string $result
     * @return MemberAttachment
     */
    public function setResult($result)
    {
        $this->result = $result;
    
        return $this;
    }

    /**
     * Get result
     *
     * @return string 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return MemberAttachment
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return MemberAttachment
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
     * Set attachment
     *
     * @param string $attachment
     * @return MemberAttachment
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
    
        return $this;
    }

    /**
     * Get attachment
     *
     * @return string 
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberAttachment
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