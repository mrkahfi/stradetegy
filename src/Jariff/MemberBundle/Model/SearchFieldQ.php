<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class SearchFieldQ
{

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


    public function addQ(SearchEmbed $task)
    {
        if (!$this->q->contains($task)) {
            $this->q->add($task);
        }
    }
}