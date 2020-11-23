<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Model;

class BillTypeCode
{

    public $bill_type_code;

    public function setBillTypeCode($bill_type_code)
    {
        $this->bill_type_code = $bill_type_code;

        return $this;
    }


    public function getBillTypeCode()
    {
        return $this->bill_type_code;
    }
}