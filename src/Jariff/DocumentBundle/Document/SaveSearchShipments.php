<?php

namespace Jariff\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="save_search_shipments")
 */
class SaveSearchShipments
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
    private $name;

    /**
     * @MongoDB\String
     */
    private $keyword;

    /**
     * @MongoDB\String
     */
    private $description;

    /**
     * @MongoDB\Date
     */
    private $date_create;

    /**
     * @MongoDB\Int
     */
    private $is_alerts;


    public function __construct()
    {
        $this->date_create = new \DateTime('now');
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
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
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
     * Set isAlerts
     *
     * @param int $isAlerts
     * @return self
     */
    public function setIsAlerts($isAlerts)
    {
        $this->is_alerts = $isAlerts;
        return $this;
    }

    /**
     * Get isAlerts
     *
     * @return int $isAlerts
     */
    public function getIsAlerts()
    {
        return $this->is_alerts;
    }
}
