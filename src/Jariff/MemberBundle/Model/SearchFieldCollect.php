<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class SearchFieldCollect
{

    public $collect;

    public function setCollect($collect)
    {
        $this->collect = $collect;

        return $this;
    }


    public function getCollect()
    {
        return $this->collect;
    }

    public function addCollect(SearchEmbed $task)
    {
        if (!$this->collect->contains($task)) {
            $this->collect->add($task);
        }
    }
}