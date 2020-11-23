<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class ConsigneeName
{

    public $consignee_name;

    public function setConsigneeName($consignee_name)
    {
        $this->consignee_name = $consignee_name;

        return $this;
    }


    public function getConsigneeName()
    {
        return $this->consignee_name;
    }
}