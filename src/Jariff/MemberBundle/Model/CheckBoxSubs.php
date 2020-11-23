<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class CheckBoxSubs
{

    public $ch;

    public function setCh($ch)
    {
        $this->ch = $ch;

        return $this;
    }


    public function getch()
    {
        return $this->ch;
    }


    
}