<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class SearchFieldCategory
{

    public $category;

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