<?php

namespace AuthorizeNet\Type;



/**
 * A class that contains all fields for a CIM Payment Type.
 *
 * @package    AuthorizeNet
 * @subpackage AuthorizeNetCIM
 */
class Payment
{
    public $creditCard;
    public $bankAccount;
    
    public function __construct()
    {
        $this->creditCard = new CreditCard;
        $this->bankAccount = new BankAccount;
    }
}