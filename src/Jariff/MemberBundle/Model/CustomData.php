<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class CustomData
{

    public $type;

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }


    public function getType()
    {
        return $this->type;
    }
}