<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class ShipperName
{

    public $shipper_name;

    public function setShipperName($shipper_name)
    {
        $this->shipper_name = $shipper_name;

        return $this;
    }


    public function getShipperName()
    {
        return $this->shipper_name;
    }
}