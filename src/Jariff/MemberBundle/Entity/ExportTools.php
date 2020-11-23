<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="export_tools")
 * @ORM\Entity(repositoryClass="Jariff\AdminBundle\Repository\AdminRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ExportTools
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
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="export_tools")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $file_name;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $file_type;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $query;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $download_from;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $download_to;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $send_mail;

   /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $process;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $request_at;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $collection;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $max_download;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $procentage;


    public function __construct()
    {
        $this->request_at = new \DateTime('now');
        $this->process = 1;
        $this->send_mail = 1;
        $this->procentage = 0;
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
     * Set file_name
     *
     * @param string $fileName
     * @return ExportTools
     */
    public function setFileName($fileName)
    {
        $this->file_name = $fileName;
    
        return $this;
    }

    /**
     * Get file_name
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Set file_type
     *
     * @param string $fileType
     * @return ExportTools
     */
    public function setFileType($fileType)
    {
        $this->file_type = $fileType;
    
        return $this;
    }

    /**
     * Get file_type
     *
     * @return string 
     */
    public function getFileType()
    {
        return $this->file_type;
    }

    /**
     * Set query
     *
     * @param string $query
     * @return ExportTools
     */
    public function setQuery($query)
    {
        $this->query = $query;
    
        return $this;
    }

    /**
     * Get query
     *
     * @return string 
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set download_from
     *
     * @param integer $downloadFrom
     * @return ExportTools
     */
    public function setDownloadFrom($downloadFrom)
    {
        $this->download_from = $downloadFrom;
    
        return $this;
    }

    /**
     * Get download_from
     *
     * @return integer 
     */
    public function getDownloadFrom()
    {
        return $this->download_from;
    }

    /**
     * Set download_to
     *
     * @param integer $downloadTo
     * @return ExportTools
     */
    public function setDownloadTo($downloadTo)
    {
        $this->download_to = $downloadTo;
    
        return $this;
    }

    /**
     * Get download_to
     *
     * @return integer 
     */
    public function getDownloadTo()
    {
        return $this->download_to;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return ExportTools
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
     * Set send_mail
     *
     * @param boolean $sendMail
     * @return ExportTools
     */
    public function setSendMail($sendMail)
    {
        $this->send_mail = $sendMail;
    
        return $this;
    }

    /**
     * Get send_mail
     *
     * @return boolean 
     */
    public function getSendMail()
    {
        return $this->send_mail;
    }

    /**
     * Set process
     *
     * @param boolean $process
     * @return ExportTools
     */
    public function setProcess($process)
    {
        $this->process = $process;
    
        return $this;
    }

    /**
     * Get process
     *
     * @return boolean 
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ExportTools
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set request_at
     *
     * @param \DateTime $requestAt
     * @return ExportTools
     */
    public function setRequestAt($requestAt)
    {
        $this->request_at = $requestAt;
    
        return $this;
    }

    /**
     * Get request_at
     *
     * @return \DateTime 
     */
    public function getRequestAt()
    {
        return $this->request_at;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return ExportTools
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
     * Set collection
     *
     * @param string $collection
     * @return ExportTools
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
    
        return $this;
    }

    /**
     * Get collection
     *
     * @return string 
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set max_download
     *
     * @param boolean $maxDownload
     * @return ExportTools
     */
    public function setMaxDownload($maxDownload)
    {
        $this->max_download = $maxDownload;
    
        return $this;
    }

    /**
     * Get max_download
     *
     * @return boolean 
     */
    public function getMaxDownload()
    {
        return $this->max_download;
    }

    /**
     * Set procentage
     *
     * @param int $procentage
     *
     * @return ExportTools
     */
    public function setProcentage($procentage)
    {
        $this->procentage = $procentage;

        return $this;
    }

    /**
     * Get procentage
     *
     * @return int
     */
    public function getProcentage()
    {
        return $this->procentage;
    }
}
