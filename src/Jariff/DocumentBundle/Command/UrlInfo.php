<?php
namespace Jariff\DocumentBundle\Command;

use Jariff\AdminBundle\Entity\DailyRank;
use Jariff\AdminBundle\Entity\Competitor;

/**
 * Makes a request to AWIS for site info.
 */
class UrlInfo {

    protected static $ActionName        = 'UrlInfo';
    protected static $ResponseGroupName = 'Rank,ContactInfo,LinksInCount';
    protected static $ServiceHost      = 'awis.amazonaws.com';
    protected static $NumReturn         = 10;
    protected static $StartNum          = 1;
    protected static $SigVersion        = '2';
    protected static $HashAlgorithm     = 'HmacSHA256';

    private $accessKeyId;
    private $secretAccessKey;
    private $site;
    private $competitorId;

    public function __construct($accessKeyId, $secretAccessKey, $site, $competitorId) {
        echo "\nconstruct\n";
        $this->accessKeyId = $accessKeyId;
        $this->secretAccessKey = $secretAccessKey;
        $this->site = $site;
        $this->competitorId = $competitorId;
    }

    /**
     * Get site info from AWIS.
     */ 
    public function getUrlInfo() {
        echo "\nini ".$this->accessKeyId."\n";
        $queryParams = $this->buildQueryParams();
        $sig = $this->generateSignature($queryParams);
        $url = 'http://' . self::$ServiceHost . '/?' . $queryParams . 
        '&Signature=' . $sig;
        $ret = self::makeRequest($url);
        echo "\nResults for " . $this->site .":\n\n";
        return self::parseResponse($ret, $this->competitorId);
    }

    /**
     * Builds current ISO8601 timestamp.
     */
    protected static function getTimestamp() {
        return gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time()); 
    }

    /**
     * Builds query parameters for the request to AWIS.
     * Parameter names will be in alphabetical order and
     * parameter values will be urlencoded per RFC 3986.
     * @return String query parameters for the request
     */
    protected function buildQueryParams() {
        $params = array(
            'Action'            => self::$ActionName,
            'ResponseGroup'     => self::$ResponseGroupName,
            'AWSAccessKeyId'    => $this->accessKeyId,
            'Timestamp'         => self::getTimestamp(),
            'Count'             => self::$NumReturn,
            'Start'             => self::$StartNum,
            'SignatureVersion'  => self::$SigVersion,
            'SignatureMethod'   => self::$HashAlgorithm,
            'Url'               => $this->site
            );
        ksort($params);
        $keyvalue = array();
        foreach($params as $k => $v) {
            $keyvalue[] = $k . '=' . rawurlencode($v);
        }
        return implode('&',$keyvalue);
    }

    /**
     * Makes request to AWIS
     * @param String $url   URL to make request to
     * @return String       Result of request
     */
    protected static function makeRequest($url) {
        echo "\nMaking request to:\n$url\n";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Parses XML response from AWIS and displays selected data
     * @param String $response    xml response from AWIS
     */
    public static function parseResponse($response, $competitorId) {
        $dailyRank = new DailyRank();
        try {
            $xml = new \SimpleXMLElement($response,null,false,
                'http://awis.amazonaws.com/doc/2005-07-11');
            if($xml->count() && $xml->Response->UrlInfoResult->Alexa->count()) {
                echo "\ndapet\n";
                $info = $xml->Response->UrlInfoResult->Alexa;
                $nice_array = array(
                    'Phone Number'   => $info->ContactInfo->PhoneNumbers->PhoneNumber,
                    'Owner Name'     => $info->ContactInfo->OwnerName,
                    'Email'          => $info->ContactInfo->Email,
                    'Street'         => $info->ContactInfo->PhysicalAddress->Streets->Street,
                    'City'           => $info->ContactInfo->PhysicalAddress->City,
                    'State'          => $info->ContactInfo->PhysicalAddress->State,
                    'Postal Code'    => $info->ContactInfo->PhysicalAddress->PostalCode,
                    'Country'        => $info->ContactInfo->PhysicalAddress->Country,
                    'Links In Count' => $info->ContentData->LinksInCount,
                    'Rank'           => $info->TrafficData->Rank
                    );
            }
            $dailyRank->setDate(new \DateTime());
            $dailyRank->setAlexaRank($nice_array['Rank']);
            $dailyRank->setCompetitor($competitorId);
            // }
        } catch (Exception $e) { 
            echo $e; 
        }
        return $dailyRank;
    }

    /**
     * Generates an HMAC signature per RFC 2104.
     *
     * @param String $url       URL to use in createing signature
     */
    protected function generateSignature($url) {
        $sign = "GET\n" . strtolower(self::$ServiceHost) . "\n/\n". $url;
        echo "String to sign: \n" . $sign . "\n";
        $sig = base64_encode(hash_hmac('sha256', $sign, $this->secretAccessKey, true));
        echo "\nSignature: " . $sig ."\n";
        return rawurlencode($sig);
    }

}

?>
