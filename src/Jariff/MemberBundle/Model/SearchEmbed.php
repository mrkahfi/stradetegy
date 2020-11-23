<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


class SearchEmbed
{
    /**
     *  digunakan untuk filter
     */
    protected $collect;


    /**
     * q = keyword yang di masukan
     *
     */
    protected $q;

    /**
     * q = keyword yang di masukan
     *
     */
    protected $condition;

    /**
     * q = keyword yang di masukan
     *
     */
    protected $category;

    public function __construct()
    {
        $this->collect = new ArrayCollection();
        $this->q = new ArrayCollection();
        $this->condition = new ArrayCollection();
    }


    public function setCollect($collect)
    {
        $this->collect = $collect;

        return $this;
    }


    public function getCollect()
    {
        return $this->collect;
    }

    public function setQ($q)
    {
        $this->q = $q;

        return $this;
    }

    public function getQ()
    {
        return $this->q;
    }


    public function getCondition()
    {
        return $this->condition;
    }

    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    public function addSearchCollect(SearchFieldCollect $collect)
    {
        $collect->addCollect($this);
        $this->collect->add($collect);
    }

    public function removeSearchCollect(SearchFieldCollect $collect)
    {
        $this->collect->removeElement($collect);
    }

    public function addSearchQ(SearchFieldQ $q)
    {
        $q->addQ($this);
        $this->q->add($q);
    }

    public function removeSearchQ(SearchFieldQ $q)
    {
        $this->q->removeElement($q);
    }

    public function addSearchCondition(SearchFieldCondition $condition)
    {
        $condition->addCondition($this);
        $this->condition->add($condition);
    }

    public function removeSearchCondition(SearchFieldCondition $condition)
    {
        $this->q->removeElement($condition);
    }

    public function setCategory($q)
    {
        $this->category = $q;

        return $this;
    }


    public function getCategory()
    {
        return $this->category;
    }
}