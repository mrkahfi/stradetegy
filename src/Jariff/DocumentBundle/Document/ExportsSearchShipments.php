<?php

namespace Jariff\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="exports_search_shipments")
 */
class ExportsSearchShipments
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Int
     */
    private $user_id;

    /**
     * @MongoDB\String
     */
    private $file_name;

    /**
     * @MongoDB\String
     */
    private $file_type;

    /**
     * @MongoDB\String
     */
    private $keyword;

    /**
     * @MongoDB\String
     */
    private $description;

    /**
     * @MongoDB\String
     */
    private $email;

    /**
     * @MongoDB\Int
     */
    private $send_mail;

    /**
     * @MongoDB\Int
     */
    private $download_from;

    /**
     * @MongoDB\Int
     */
    private $download_to;

    /**
     * @MongoDB\Date
     */
    private $date_create;

    /**
     * @MongoDB\Int
     */
    private $process;


    public function __construct()
    {
        $this->date_create = new \DateTime('now');
        $this->process = 1;
    }


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param int $userId
     * @return self
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * Get userId
     *
     * @return int $userId
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return self
     */
    public function setFileName($fileName)
    {
        $this->file_name = $fileName;
        return $this;
    }

    /**
     * Get fileName
     *
     * @return string $fileName
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Set fileType
     *
     * @param string $fileType
     * @return self
     */
    public function setFileType($fileType)
    {
        $this->file_type = $fileType;
        return $this;
    }

    /**
     * Get fileType
     *
     * @return string $fileType
     */
    public function getFileType()
    {
        return $this->file_type;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     * @return self
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * Get keyword
     *
     * @return string $keyword
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set sendMail
     *
     * @param int $sendMail
     * @return self
     */
    public function setSendMail($sendMail)
    {
        $this->send_mail = $sendMail;
        return $this;
    }

    /**
     * Get sendMail
     *
     * @return int $sendMail
     */
    public function getSendMail()
    {
        return $this->send_mail;
    }

    /**
     * Set downloadFrom
     *
     * @param int $downloadFrom
     * @return self
     */
    public function setDownloadFrom($downloadFrom)
    {
        $this->download_from = $downloadFrom;
        return $this;
    }

    /**
     * Get downloadFrom
     *
     * @return int $downloadFrom
     */
    public function getDownloadFrom()
    {
        return $this->download_from;
    }

    /**
     * Set downloadTo
     *
     * @param int $downloadTo
     * @return self
     */
    public function setDownloadTo($downloadTo)
    {
        $this->download_to = $downloadTo;
        return $this;
    }

    /**
     * Get downloadTo
     *
     * @return int $downloadTo
     */
    public function getDownloadTo()
    {
        return $this->download_to;
    }

    /**
     * Set dateCreate
     *
     * @param date $dateCreate
     * @return self
     */
    public function setDateCreate($dateCreate)
    {
        $this->date_create = $dateCreate;
        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return date $dateCreate
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * Set process
     *
     * @param int $process
     * @return self
     */
    public function setProcess($process)
    {
        $this->process = $process;
        return $this;
    }

    /**
     * Get process
     *
     * @return int $process
     */
    public function getProcess()
    {
        return $this->process;
    }
}
