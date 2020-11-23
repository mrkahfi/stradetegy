<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class FieldUsCustom
{

    public $field_us_custom;

    public function setFieldUsCustom($field_custom)
    {
        $this->field_us_custom = $field_custom;

        return $this;
    }


    public function getFieldUsCustom()
    {
        return $this->field_us_custom;
    }
}