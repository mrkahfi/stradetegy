<?php

namespace AuthorizeNet\Type;



/**
 * A class that contains all fields for a CIM Bank Account.
 *
 * @package    AuthorizeNet
 * @subpackage AuthorizeNetCIM
 */
class BankAccount
{
    public $accountType;
    public $routingNumber;
    public $accountNumber;
    public $nameOnAccount;
    public $echeckType;
    public $bankName;
}