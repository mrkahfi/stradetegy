<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class DateRange
{

    public $date_from;

    public $date_to;

    public $not_valid_date;

    public $marked_master;

    public function setDateFrom($date_from)
    {
        $this->date_from = $date_from;

        return $this;
    }


    public function getDateFrom()
    {
        return $this->date_from;
    }

    public function setDateTo($date_to)
    {
        $this->date_to = $date_to;

        return $this;
    }


    public function getDateTo()
    {
        return $this->date_to;
    }

    public function setNotValidDate($not_valid_date)
    {
        $this->not_valid_date = $not_valid_date;

        return $this;
    }


    public function getNotValidDate()
    {
        return $this->not_valid_date;
    }

    public function setMarkedMaster($marked_master)
    {
        $this->marked_master = $marked_master;

        return $this;
    }


    public function getMarkedMaster()
    {
        return $this->marked_master;
    }
}