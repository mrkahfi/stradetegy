<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\ProjectBundle\Twig;

use Symfony\Component\Validator\Constraints\DateTime;
use Twig_Extension;
use Twig_Filter_Method;
use Twig_Function_Method;
use Jariff\ProjectBundle\Util\Util;

class StringExtension extends Twig_Extension
{
    public function getFilters()
    {
        return array(
            'readmore' => new Twig_Filter_Method($this, 'readmore'),
            'twolettercode' => new Twig_Filter_Method($this, 'twolettercode'),
            'twolettercodeslug' => new Twig_Filter_Method($this, 'twolettercodeslug'),
            'compare_dates' => new Twig_Filter_Method($this, 'compare_dates'),
            'ribuan' => new Twig_Filter_Method($this, 'ribuan'),
            'jsondecode' => new Twig_Filter_Method($this, 'jsondecode'),

            'firstnlastword' => new Twig_Filter_Method($this, 'firstnlastword'),
            'descfirstnlastword' => new Twig_Filter_Method($this, 'descfirstnlastword'),

            'convert_date' => new Twig_Filter_Method($this, 'convert_date'),

        );
    }

    public function descfirstnlastword($string)
    {
        $splitText = preg_split('/(<[^>]*[^\/]>)/i', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $splitTextFirstLen = strlen($splitText[0]);

        $textFirst = $splitText[0];
        $endText = isset($splitText[4]) ? $splitText[4] : ' ';
        $middleText = isset($splitText[3]) ? $splitText[3] : ' ';


        if ($splitTextFirstLen > 30 and $textFirst != '<strong>') {
            $textFirst = substr($splitText[0], $splitTextFirstLen - 30);
            $fullText = '...' . $textFirst . ' ' . $splitText[1] . $splitText[2] . $middleText . $endText;
        } else {
            $fullText = $textFirst . ' ' . $splitText[1] . $splitText[2] . $middleText . $endText;
        }

        return preg_replace('/\=/', '', $fullText);
    }



    public function firstnlastword($string)
    {
        $splitText = preg_split('/(<[^>]*[^\/]>)/i', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $splitTextFirstLen = strlen($splitText[0]);

        $textFirst = $splitText[0];
        $endText = isset($splitText[4]) ? $splitText[4] : ' ';
        $middleText = isset($splitText[3]) ? $splitText[3] : ' ';


        if ($splitTextFirstLen > 15 and $textFirst != '<strong>') {
            $textFirst = $this->getNthWord($splitText[0], 1, 'first');
            $fullText = $textFirst . ' ' . $splitText[1] . $splitText[2] . ' ' . $this->getNthWord($middleText, 2, 'end') . $this->getNthWord($endText, 2, 'end');
        } else {
            $fullText = $textFirst . ' ' . $splitText[1] . $splitText[2] . ' ' . $this->getNthWord($middleText, 2, 'end') . $this->getNthWord($endText, 2, 'end');
        }

        return 'test';
    }

    function getNthWord($string, $n, $pos)
    {
        $string = preg_split('/ /', $string);

        if ($pos == 'first') {
            $n = count($string) - $n;
            $first1 = isset($string[$n - 1]) ? $string[$n - 1] : '';
            $first2 = isset($string[$n]) ? $string[$n] : ' ' . $string[0];

            $data = $first1 . ' ' . $first2;
        } else {
            $twoWords = isset($string[1]) ? $string[1] : '';
            $threeWords = isset($string[2]) ? $string[2] : '';
            $check = strlen($threeWords);
            if ($check > 7)
                $data = $string[0] . ' ' . $twoWords;
            else
                $data = $string[0] . ' ' . $twoWords . ' ' . $threeWords;
        }


        return preg_replace(array('/\=/', '/\(/', '/\)/'), ' ', $data);
    }

    public function ribuan($money)
    {
        return number_format($money, 0, ',', ',');
    }

    public function jsondecode($string)
    {
        return json_decode($string);
    }

    public function serializetwig($string,$toArray = false)
    {
        if($toArray)
            return serialize($string);
        else
            return unserialize($string);
    }

    public function readmore($readmore)
    {
        $string = strip_tags($readmore);

        if (strlen($string) > 30) {

            // truncate string
            $stringCut = substr($string, 0, 30);

            // make sure it ends in a word so assassinate doesn't become ass...
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
        }
        return $string;
    }


    public function twolettercode($country)
    {

        $data = $this->dataCountry();
        return strtoupper($country);

    }

    public function twolettercodeslug($country)
    {
        $data = $this->dataCountry();

        return 'id';

    }

    function compare_dates($date2)
    {
        $date1 = new \DateTime('now');
        $today = $date1->format("Y-m-d");
        $date2 = substr($date2, 0, 4) . '-' . substr($date2, 4, 2) . '-' . substr($date2, 6, 2);

        $date1 = strtotime($today);


        $date2 = strtotime($date2);


        $blocks = array(
            array('name' => 'year', 'amount' => 60 * 60 * 24 * 365),
            array('name' => 'month', 'amount' => 60 * 60 * 24 * 31),
            array('name' => 'week', 'amount' => 60 * 60 * 24 * 7),
            array('name' => 'day', 'amount' => 60 * 60 * 24),
            array('name' => 'hour', 'amount' => 60 * 60),
            array('name' => 'minute', 'amount' => 60),
            array('name' => 'second', 'amount' => 1)
        );

        $diff = abs($date1 - $date2);

        $levels = 2;
        $current_level = 1;
        $result = array();
        foreach ($blocks as $block) {
            if ($current_level > $levels) {
                break;
            }
            if ($diff / $block['amount'] >= 1) {
                $amount = floor($diff / $block['amount']);
                if ($amount > 1) {
                    $plural = 's';
                } else {
                    $plural = '';
                }
                $result[] = $amount . ' ' . $block['name'] . $plural;
                $diff -= $amount * $block['amount'];
                $current_level++;
            }
        }
        return implode(' ', $result) . ' ago';
    }

    function convert_date($date2)
    {
        $date2 = substr($date2, 0, 4) . '-' . substr($date2, 4, 2) . '-' . substr($date2, 6, 2);

        return $date2;

    }

    public function getName()
    {
        return 'jariff_string_extension';
    }

    public function dataCountry()
    {
        return array("AF" => "AFGHANISTAN",
            "AX" => "ÅLAND ISLANDS",
            "AL" => "ALBANIA",
            "DZ" => "ALGERIA",
            "AS" => "AMERICAN SAMOA",
            "AD" => "ANDORRA",
            "AO" => "ANGOLA",
            "AI" => "ANGUILLA",
            "AQ" => "ANTARCTICA",
            "AG" => "ANTIGUA AND BARBUDA",
            "AR" => "ARGENTINA",
            "AM" => "ARMENIA",
            "AW" => "ARUBA",
            "AU" => "AUSTRALIA",
            "AT" => "AUSTRIA",
            "AZ" => "AZERBAIJAN",
            "AN" => "Netherlands Antilles",
            "BS" => "BAHAMAS",
            "BH" => "BAHRAIN",
            "BD" => "BANGLADESH",
            "BB" => "BARBADOS",
            "BY" => "BELARUS",
            "BE" => "BELGIUM",
            "BZ" => "BELIZE",
            "BJ" => "BENIN",
            "BM" => "BERMUDA",
            "BT" => "BHUTAN",
            "BO" => "BOLIVIA, PLURINATIONAL STATE OF",
            "BQ" => "BONAIRE, SINT EUSTATIUS AND SABA",
            "BA" => "BOSNIA AND HERZEGOVINA",
            "BW" => "BOTSWANA",
            "BV" => "BOUVET ISLAND",
            "BR" => "BRAZIL",
            "IO" => "BRITISH INDIAN OCEAN TERRITORY",
            "BN" => "BRUNEI DARUSSALAM",
            "BG" => "BULGARIA",
            "BF" => "BURKINA FASO",
            "BI" => "BURUNDI",
            "KH" => "CAMBODIA",
            "CM" => "CAMEROON",
            "CA" => "CANADA",
            "CV" => "CAPE VERDE",
            "KY" => "CAYMAN ISLANDS",
            "CF" => "CENTRAL AFRICAN REPUBLIC",
            "TD" => "CHAD",
            "CL" => "CHILE",
            "CN" => "CHINA",
            "CX" => "CHRISTMAS ISLAND",
            "CC" => "COCOS (KEELING) ISLANDS",
            "CO" => "COLOMBIA",
            "KM" => "COMOROS",
            "CG" => "CONGO",
            "CD" => "CONGO, THE DEMOCRATIC REPUBLIC OF THE",
            "CK" => "COOK ISLANDS",
            "CR" => "COSTA RICA",
            "CI" => "CÔTE D'IVOIRE",
            "HR" => "CROATIA",
            "CU" => "CUBA",
            "CW" => "CURAÇAO",
            "CY" => "CYPRUS",
            "CZ" => "CZECH REPUBLIC",
            "DK" => "DENMARK",
            "DJ" => "DJIBOUTI",
            "DM" => "DOMINICA",
            "DO" => "DOMINICAN REPUBLIC",
            "EC" => "ECUADOR",
            "EG" => "EGYPT",
            "SV" => "EL SALVADOR",
            "GQ" => "EQUATORIAL GUINEA",
            "ER" => "ERITREA",
            "EE" => "ESTONIA",
            "ET" => "ETHIOPIA",
            "FK" => "FALKLAND ISLANDS (MALVINAS)",
            "FO" => "FAROE ISLANDS",
            "FJ" => "FIJI",
            "FI" => "FINLAND",
            "FR" => "FRANCE",
            "GF" => "FRENCH GUIANA",
            "PF" => "FRENCH POLYNESIA",
            "TF" => "FRENCH SOUTHERN TERRITORIES",
            "GA" => "GABON",
            "GM" => "GAMBIA",
            "GE" => "GEORGIA",
            "DE" => "GERMANY",
            "GH" => "GHANA",
            "GI" => "GIBRALTAR",
            "GR" => "GREECE",
            "GL" => "GREENLAND",
            "GD" => "GRENADA",
            "GP" => "GUADELOUPE",
            "GU" => "GUAM",
            "GT" => "GUATEMALA",
            "GG" => "GUERNSEY",
            "GN" => "GUINEA",
            "GW" => "GUINEA-BISSAU",
            "GY" => "GUYANA",
            "HT" => "HAITI",
            "HM" => "HEARD ISLAND AND MCDONALD ISLANDS",
            "VA" => "HOLY SEE (VATICAN CITY STATE)",
            "HN" => "HONDURAS",
            "HK" => "HONG KONG",
            "HU" => "HUNGARY",
            "IS" => "ICELAND",
            "IN" => "INDIA",
            "ID" => "INDONESIA",
            "IR" => "IRAN, ISLAMIC REPUBLIC OF",
            "IQ" => "IRAQ",
            "IE" => "IRELAND",
            "IM" => "ISLE OF MAN",
            "IL" => "ISRAEL",
            "IT" => "ITALY",
            "JM" => "JAMAICA",
            "JP" => "JAPAN",
            "JE" => "JERSEY",
            "JO" => "JORDAN",
            "KZ" => "KAZAKHSTAN",
            "KE" => "KENYA",
            "KI" => "KIRIBATI",
            "KP" => "KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF",
            "KR" => "SOUTH KOREA",
            "KW" => "KUWAIT",
            "KG" => "KYRGYZSTAN",
            "LA" => "LAO PEOPLE'S DEMOCRATIC REPUBLIC",
            "LV" => "LATVIA",
            "LB" => "LEBANON",
            "LS" => "LESOTHO",
            "LR" => "LIBERIA",
            "LY" => "LIBYA",
            "LI" => "LIECHTENSTEIN",
            "LT" => "LITHUANIA",
            "LU" => "LUXEMBOURG",
            "MO" => "MACAO",
            "MK" => "MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF",
            "MG" => "MADAGASCAR",
            "MW" => "MALAWI",
            "MY" => "MALAYSIA",
            "MV" => "MALDIVES",
            "ML" => "MALI",
            "MT" => "MALTA",
            "MH" => "MARSHALL ISLANDS",
            "MQ" => "MARTINIQUE",
            "MR" => "MAURITANIA",
            "MU" => "MAURITIUS",
            "YT" => "MAYOTTE",
            "MX" => "MEXICO",
            "FM" => "MICRONESIA, FEDERATED STATES OF",
            "MD" => "MOLDOVA, REPUBLIC OF",
            "MC" => "MONACO",
            "MN" => "MONGOLIA",
            "ME" => "MONTENEGRO",
            "MS" => "MONTSERRAT",
            "MA" => "MOROCCO",
            "MZ" => "MOZAMBIQUE",
            "MM" => "MYANMAR",
            "NA" => "NAMIBIA",
            "NR" => "NAURU",
            "NP" => "NEPAL",
            "NL" => "NETHERLANDS",
            "NC" => "NEW CALEDONIA",
            "NZ" => "NEW ZEALAND",
            "NI" => "NICARAGUA",
            "NE" => "NIGER",
            "NG" => "NIGERIA",
            "NU" => "NIUE",
            "NF" => "NORFOLK ISLAND",
            "MP" => "NORTHERN MARIANA ISLANDS",
            "NO" => "NORWAY",
            "OM" => "OMAN",
            "PK" => "PAKISTAN",
            "PW" => "PALAU",
            "PS" => "PALESTINE, STATE OF",
            "PA" => "PANAMA",
            "PG" => "PAPUA NEW GUINEA",
            "PY" => "PARAGUAY",
            "PE" => "PERU",
            "PH" => "PHILIPPINES",
            "PN" => "PITCAIRN",
            "PL" => "POLAND",
            "PT" => "PORTUGAL",
            "PR" => "PUERTO RICO",
            "QA" => "QATAR",
            "RE" => "RÉUNION",
            "RO" => "ROMANIA",
            "RU" => "RUSSIAN FEDERATION",
            "RW" => "RWANDA",
            "BL" => "SAINT BARTHÉLEMY",
            "SH" => "SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA",
            "KN" => "SAINT KITTS AND NEVIS",
            "LC" => "SAINT LUCIA",
            "MF" => "SAINT MARTIN (FRENCH PART)",
            "PM" => "SAINT PIERRE AND MIQUELON",
            "VC" => "SAINT VINCENT AND THE GRENADINES",
            "WS" => "SAMOA",
            "SM" => "SAN MARINO",
            "ST" => "SAO TOME AND PRINCIPE",
            "SA" => "SAUDI ARABIA",
            "SN" => "SENEGAL",
            "RS" => "SERBIA",
            "SC" => "SEYCHELLES",
            "SL" => "SIERRA LEONE",
            "SG" => "SINGAPORE",
            "SX" => "SINT MAARTEN (DUTCH PART)",
            "SK" => "SLOVAKIA",
            "SI" => "SLOVENIA",
            "SB" => "SOLOMON ISLANDS",
            "SO" => "SOMALIA",
            "ZA" => "SOUTH AFRICA",
            "GS" => "SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS",
            "SS" => "SOUTH SUDAN",
            "ES" => "SPAIN",
            "LK" => "SRI LANKA",
            "SD" => "SUDAN",
            "SR" => "SURINAME",
            "SJ" => "SVALBARD AND JAN MAYEN",
            "SZ" => "SWAZILAND",
            "SE" => "SWEDEN",
            "CH" => "SWITZERLAND",
            "SY" => "SYRIAN ARAB REPUBLIC",
            "TW" => "TAIWAN",
            "TJ" => "TAJIKISTAN",
            "TZ" => "TANZANIA, UNITED REPUBLIC OF",
            "TH" => "THAILAND",
            "TL" => "TIMOR-LESTE",
            "TG" => "TOGO",
            "TK" => "TOKELAU",
            "TO" => "TONGA",
            "TT" => "TRINIDAD AND TOBAGO",
            "TN" => "TUNISIA",
            "TR" => "TURKEY",
            "TM" => "TURKMENISTAN",
            "TC" => "TURKS AND CAICOS ISLANDS",
            "TV" => "TUVALU",
            "UG" => "UGANDA",
            "UA" => "UKRAINE",
            "AE" => "UNITED ARAB EMIRATES",
            "GB" => "UNITED KINGDOM",
            "US" => "UNITED STATES",
            "UM" => "UNITED STATES MINOR OUTLYING ISLANDS",
            "UY" => "URUGUAY",
            "UZ" => "UZBEKISTAN",
            "VU" => "VANUATU",
            "VE" => "VENEZUELA, BOLIVARIAN REPUBLIC OF",
            "VN" => "VIET NAM",
            "VG" => "VIRGIN ISLANDS, BRITISH",
            "VI" => "VIRGIN ISLANDS, U.S.",
            "WF" => "WALLIS AND FUTUNA",
            "EH" => "WESTERN SAHARA",
            "YE" => "YEMEN",
            "ZM" => "ZAMBIA",
            "ZW" => "ZIMBABWE");
    }
}