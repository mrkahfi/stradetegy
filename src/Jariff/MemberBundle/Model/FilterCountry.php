<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class FilterCountry
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


    public function addCountry(FilterCountryEmbed $task)
    {
        if (!$this->country->contains($task)) {
            $this->country->add($task);
        }
    }
}