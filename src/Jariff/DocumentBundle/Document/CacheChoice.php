<?php

namespace Jariff\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="cache_choice")
 */
class CacheChoice
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
    private $header;

    /**
     * @MongoDB\String
     */
    private $body;

    /**
     * @MongoDB\String
     */
    private $session;

    /**
     * @MongoDB\String
     */
    private $category;

    /**
     * @MongoDB\String
     */
    private $post;

    /**
     * @MongoDB\String
     */
    private $slug;

    /**
     * @MongoDB\Int
     */
    private $is_compare;



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
     * Set session
     *
     * @param string $session
     * @return self
     */
    public function setSession($session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * Get session
     *
     * @return string $session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return self
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return string $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set post
     *
     * @param string $post
     * @return self
     */
    public function setPost($post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * Get post
     *
     * @return string $post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get slug
     *
     * @return string $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }



    /**
     * Set isCompare
     *
     * @param int $isCompare
     * @return self
     */
    public function setIsCompare($isCompare)
    {
        $this->is_compare = $isCompare;
        return $this;
    }

    /**
     * Get isCompare
     *
     * @return int $isCompare
     */
    public function getIsCompare()
    {
        return $this->is_compare;
    }

    /**
     * Set header
     *
     * @param string $header
     * @return self
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * Get header
     *
     * @return string $header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return self
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Get body
     *
     * @return string $body
     */
    public function getBody()
    {
        return $this->body;
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
}
