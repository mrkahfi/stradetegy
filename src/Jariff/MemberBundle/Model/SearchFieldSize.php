<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class SearchFieldSize
{

    public $size;

    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }


    public function getSize()
    {
        return $this->size;
    }
}