<?php

namespace Jariff\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="keyword_history")
 */
class KeywordHistory
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
    private $keyword;

    /**
     * @MongoDB\String
     */
    private $identity;

    /**
     * @MongoDB\String
     */
    private $search_on;




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
     * Set identity
     *
     * @param string $identity
     * @return self
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * Get identity
     *
     * @return string $identity
     */
    public function getIdentity()
    {
        return $this->identity;
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
