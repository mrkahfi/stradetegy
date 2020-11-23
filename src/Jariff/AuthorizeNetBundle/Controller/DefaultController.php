<?php

namespace Jariff\AuthorizeNetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use AuthorizeNet;

class DefaultController extends Controller
{
    /**
     * @Route("/tes-arb", name="tes_arb")
     */
    public function arbAction()
    {
    	$params = $this->container->getParameter('jariff_authorize_net');
		define("AUTHORIZENET_API_LOGIN_ID", $params['api_login_id']);
		define("AUTHORIZENET_TRANSACTION_KEY", $params['api_transaction_key']);

		$subscription = new AuthorizeNet\Type\Subscription;
		$subscription->name = "Short subscription";
		$subscription->intervalLength = "1";
		$subscription->intervalUnit = "months";
		$subscription->startDate = date('Y-m-d');
		$subscription->totalOccurrences = "14";
		$subscription->amount = rand(1,100);
		$subscription->creditCardCardNumber = "6011000000000012";
		$subscription->creditCardExpirationDate = "2018-10";
		$subscription->creditCardCardCode = "123";
		$subscription->billToFirstName = "john";
		$subscription->billToLastName = "doe";

		$request = new AuthorizeNet\API\ARB;
		$response = $request->createSubscription($subscription);

		return new Response(var_dump($response->response));
    }

    /**
     * @Route("/tes-aim", name="tes_aim")
     */
    public function aimAction()
    {
    	$params = $this->container->getParameter('jariff_authorize_net');
		define("AUTHORIZENET_API_LOGIN_ID", $params['api_login_id']);
		define("AUTHORIZENET_TRANSACTION_KEY", $params['api_transaction_key']);

		$sale           = new AuthorizeNet\API\AIM;
		$sale->amount   = "1999.99";
		$sale->card_num = '6011000000000012';
		$sale->exp_date = '04/15';
		$response       = $sale->authorizeAndCapture();
		$out            = new AuthorizeNet\API\AIMResponse($response->response, ",", "|", array());

		if ($out->approved) {
			
		} elseif ($out->declined) {
			
		} elseif ($out->error) {
			
		} elseif ($out->held) {
			
		} 

		return new Response(var_dump($out));
    }
}

/*
address
allow_partial_auth
amount
auth_code
authentication_indicator
bank_aba_code
bank_acct_name
bank_acct_num
bank_acct_type
bank_check_number
bank_name
card_code
card_num
cardholder_authentication_value
city
company
country
cust_id
customer_ip
delim_char
delim_data
description
duplicate_window
duty
echeck_type
email
email_customer
encap_char
exp_date
fax
first_name
footer_email_receipt
freight
header_email_receipt
invoice_num
last_name
line_item
login
method
phone
po_num
recurring_billing
relay_response
ship_to_address
ship_to_city
ship_to_company
ship_to_country
ship_to_first_name
ship_to_last_name
ship_to_state
ship_to_zip
split_tender_id
state
tax
tax_exempt
test_request
tran_key
trans_id
type
version
zip
 */