<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class SearchBigPictureModel
{

    public $country;

    public $category;

    public $q;

    public function setQ($q)
    {
        $this->q = $q;

        return $this;
    }


    public function getQ()
    {
        return $this->q;
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

    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }


    public function getCountry()
    {
        return $this->country;
    }



}