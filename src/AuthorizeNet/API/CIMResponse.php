<?php

namespace AuthorizeNet\API;

use AuthorizeNet;

/**
 * A class to parse a response from the CIM XML API.
 *
 * @package    AuthorizeNet
 * @subpackage AuthorizeNetCIM
 */
class CIMResponse extends AuthorizeNet\XMLResponse
{
    /**
     * @return AuthorizeNet\API\AIMResponse
     */
    public function getTransactionResponse()
    {
        return new AuthorizeNet\API\AIMResponse($this->_getElementContents("directResponse"), ",", "", array());
    }
    
    /**
     * @return array Array of AuthorizeNet\API\AIMResponse objects for each payment profile.
     */
    public function getValidationResponses()
    {
        $responses = (array)$this->xml->validationDirectResponseList;
        $return = array();
        foreach ((array)$responses["string"] as $response) {
            $return[] = new AuthorizeNet\API\AIMResponse($response, ",", "", array());
        }
        return $return;
    }
    
    /**
     * @return AuthorizeNet\API\AIMResponse
     */
    public function getValidationResponse()
    {
        return new AuthorizeNet\API\AIMResponse($this->_getElementContents("validationDirectResponse"), ",", "", array());
    }
    
    /**
     * @return array
     */
    public function getCustomerProfileIds()
    {
        $ids = (array)$this->xml->ids;
        return $ids["numericString"];
    }
    
    /**
     * @return array
     */
    public function getCustomerPaymentProfileIds()
    {
        $ids = (array)$this->xml->customerPaymentProfileIdList;
        return $ids["numericString"];
    }
    
    /**
     * @return array
     */
    public function getCustomerShippingAddressIds()
    {
        $ids = (array)$this->xml->customerShippingAddressIdList;
        return $ids["numericString"];
    }
    
    /**
     * @return string
     */
    public function getCustomerAddressId()
    {
        return $this->_getElementContents("customerAddressId");
    }
    
    /**
     * @return string
     */
    public function getCustomerProfileId()
    {
        return $this->_getElementContents("customerProfileId");
    }
    
    /**
     * @return string
     */
    public function getPaymentProfileId()
    {
        return $this->_getElementContents("customerPaymentProfileId");
    }

}
