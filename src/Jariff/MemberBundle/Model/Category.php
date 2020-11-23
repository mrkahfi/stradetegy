<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class Category
{

    public $category;

    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }


    public function getCategory()
    {
        return $this->category;
    }
}