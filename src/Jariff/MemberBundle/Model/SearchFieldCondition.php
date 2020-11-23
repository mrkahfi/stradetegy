<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class SearchFieldCondition
{

    public $condition;

    public function setCondition($collect)
    {
        $this->condition = $collect;

        return $this;
    }


    public function getCondition()
    {
        return $this->condition;
    }

    public function addCondition(SearchEmbed $task)
    {
        if (!$this->condition->contains($task)) {
            $this->condition->add($task);
        }
    }
}