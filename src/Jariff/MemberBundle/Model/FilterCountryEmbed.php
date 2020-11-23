<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


class FilterCountryEmbed
{
    /**
     *  digunakan untuk filter
     */
    protected $country;

    public function __construct()
    {
        $this->country = new ArrayCollection();
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

    public function addFilterCountry(FilterCountry $q)
    {
        $q->addCountry($this);
        $this->country->add($q);
    }

    public function removeFilterCountry(FilterCountry $q)
    {
        $this->country->removeElement($q);
    }
}