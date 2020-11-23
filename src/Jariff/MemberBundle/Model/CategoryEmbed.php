<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


class CategoryEmbed
{
    /**
     *  digunakan untuk filter
     */
    protected $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }


    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }


    public function getCategory()
    {
        return $this->category;
    }

    public function addCategory(Category $q)
    {
        $q->addCategory($this);
        $this->category->add($q);
    }

    public function removeCategory(Category $q)
    {
        $this->category->removeElement($q);
    }
}