<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;

class Country
{

    public $country;

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