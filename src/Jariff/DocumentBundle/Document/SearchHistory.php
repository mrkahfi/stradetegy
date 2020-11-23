<?php

namespace Jariff\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="search_history")
 *
 */
class SearchHistory
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
    private $query;

    /**
     * @MongoDB\String
     */
    private $date_search;

    /**
     * @MongoDB\Int
     */
    private $is_download;

    /**
     * @MongoDB\String
     */
    private $timezone;

    /**
     * @MongoDB\Int
     */
    private $total_row;

    /**
     * @MongoDB\String
     */
    private $set_pin;

    /**
     * @MongoDB\Int
     */
    private $priority;

    /**
     * @MongoDB\String
     */
    private $search_on;

    function __construct(){
        $this->priority = 0;
        $this->set_pin = 'unpin';
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
     * Set query
     *
     * @param string $query
     * @return self
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Get query
     *
     * @return string $query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set dateSearch
     *
     * @param string $dateSearch
     * @return self
     */
    public function setDateSearch($dateSearch)
    {
        $this->date_search = $dateSearch;
        return $this;
    }

    /**
     * Get dateSearch
     *
     * @return string $dateSearch
     */
    public function getDateSearch()
    {
        return $this->date_search;
    }

    /**
     * Set isDownload
     *
     * @param int $isDownload
     * @return self
     */
    public function setIsDownload($isDownload)
    {
        $this->is_download = $isDownload;
        return $this;
    }

    /**
     * Get isDownload
     *
     * @return int $isDownload
     */
    public function getIsDownload()
    {
        return $this->is_download;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return self
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * Get timezone
     *
     * @return string $timezone
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set totalRow
     *
     * @param int $totalRow
     * @return self
     */
    public function setTotalRow($totalRow)
    {
        $this->total_row = $totalRow;
        return $this;
    }

    /**
     * Get totalRow
     *
     * @return int $totalRow
     */
    public function getTotalRow()
    {
        return $this->total_row;
    }

    /**
     * Set setPin
     *
     * @param string $setPin
     * @return self
     */
    public function setSetPin($setPin)
    {
        $this->set_pin = $setPin;
        return $this;
    }

    /**
     * Get setPin
     *
     * @return string $setPin
     */
    public function getSetPin()
    {
        return $this->set_pin;
    }

    /**
     * Set priority
     *
     * @param int $priority
     * @return self
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Get priority
     *
     * @return int $priority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set searchOn
     *
     * @param string $searchOn
     * @return self
     */
    public function setSearchOn($searchOn)
    {
        $this->search_on = $searchOn;
        return $this;
    }

    /**
     * Get searchOn
     *
     * @return string $searchOn
     */
    public function getSearchOn()
    {
        return $this->search_on;
    }
}
