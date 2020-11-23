<?php
namespace Jariff\ProjectBundle\Twig;

    /*
     * Jariff Multi Corpindo (c)
     */

namespace Jariff\ProjectBundle\Twig;

use Symfony\Component\Validator\Constraints\DateTime;
use Twig_Extension;
use Twig_Filter_Method;
use Twig_Function_Method;
use Jariff\ProjectBundle\Util\Util;

class FormExtension extends Twig_Extension
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
            'preg_country' => new Twig_Filter_Method($this, 'preg_country'),

        );
    }

    public function preg_country($string)
    {
        return preg_split('/\|/', $string);
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

        return preg_replace("/<br\W*?\/>/", '', $fullText);
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

    public function readmore($readmore)
    {
        $string = strip_tags($readmore);

        if (strlen($string) > 100) {

            // truncate string
            $stringCut = substr($string, 0, 100);

            // make sure it ends in a word so assassinate doesn't become ass...
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
        }
        return $string;
    }


    public function twolettercode($country)
    {
        $data = $this->dataCountry();

        if ($country == 'China Taiwan')
            $country = 'taiwan';

        return isset($data[strtoupper($country)]) ? $data[strtoupper($country)] : "Unknown";
    }

    public function twolettercodeslug($country)
    {
        $data = $this->dataCountry();

        if ($country == 'chinataiwan')
            $country = 'taiwan';

        $result = array(
            'country' => $country,
            'code' => 'unknown'
        );

        foreach ($data as $key => $row) {
            $keySlug = trim(preg_replace('~[^\\pL\d]+~u', "", $key));

            if (strtolower($keySlug) == $country) {
                $result = array(
                    'country' => strtoupper(trim(preg_replace("/([A-Z])/", " $1", strtolower($key)))),
                    'code' => $row
                );
                break;
            }

        }

//        isset($data[strtoupper($country)]) ? $data[strtoupper($country)] : ""

        return $result;
    }

    function compare_dates($date2)
    {
        $date1 = new \DateTime('now');
        $today = $date1->format("Y-m-d");
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

    public function getName()
    {
        return 'jariff_string_extension';
    }

    public function dataCountry()
    {
        return array("AFGHANISTAN" => "AF",
            "ÅLAND ISLANDS" => "AX",
            "ALBANIA" => "AL",
            "ALGERIA" => "DZ",
            "AMERICAN SAMOA" => "AS",
            "ANDORRA" => "AD",
            "ANGOLA" => "AO",
            "ANGUILLA" => "AI",
            "ANTARCTICA" => "AQ",
            "ANTIGUA AND BARBUDA" => "AG",
            "ARGENTINA" => "AR",
            "ARMENIA" => "AM",
            "ARUBA" => "AW",
            "AUSTRALIA" => "AU",
            "AUSTRIA" => "AT",
            "AZERBAIJAN" => "AZ",
            "BAHAMAS" => "BS",
            "BAHRAIN" => "BH",
            "BANGLADESH" => "BD",
            "BARBADOS" => "BB",
            "BELARUS" => "BY",
            "BELGIUM" => "BE",
            "BELIZE" => "BZ",
            "BENIN" => "BJ",
            "BERMUDA" => "BM",
            "BHUTAN" => "BT",
            "BOLIVIA, PLURINATIONAL STATE OF" => "BO",
            "BONAIRE, SINT EUSTATIUS AND SABA" => "BQ",
            "BOSNIA AND HERZEGOVINA" => "BA",
            "BOTSWANA" => "BW",
            "BOUVET ISLAND" => "BV",
            "BRAZIL" => "BR",
            "BRITISH INDIAN OCEAN TERRITORY" => "IO",
            "BRUNEI DARUSSALAM" => "BN",
            "BULGARIA" => "BG",
            "BURKINA FASO" => "BF",
            "BURUNDI" => "BI",
            "CAMBODIA" => "KH",
            "CAMEROON" => "CM",
            "CANADA" => "CA",
            "CAPE VERDE" => "CV",
            "CAYMAN ISLANDS" => "KY",
            "CENTRAL AFRICAN REPUBLIC" => "CF",
            "CHAD" => "TD",
            "CHILE" => "CL",
            "CHINA" => "CN",
            "CHRISTMAS ISLAND" => "CX",
            "COCOS (KEELING) ISLANDS" => "CC",
            "COLOMBIA" => "CO",
            "COMOROS" => "KM",
            "CONGO" => "CG",
            "CONGO, THE DEMOCRATIC REPUBLIC OF THE" => "CD",
            "COOK ISLANDS" => "CK",
            "COSTA RICA" => "CR",
            "CÔTE D'IVOIRE" => "CI",
            "CROATIA" => "HR",
            "CUBA" => "CU",
            "CURAÇAO" => "CW",
            "CYPRUS" => "CY",
            "CZECH REPUBLIC" => "CZ",
            "DENMARK" => "DK",
            "DJIBOUTI" => "DJ",
            "DOMINICA" => "DM",
            "DOMINICAN REPUBLIC" => "DO",
            "ECUADOR" => "EC",
            "EGYPT" => "EG",
            "EL SALVADOR" => "SV",
            "EQUATORIAL GUINEA" => "GQ",
            "ERITREA" => "ER",
            "ESTONIA" => "EE",
            "ETHIOPIA" => "ET",
            "FALKLAND ISLANDS (MALVINAS)" => "FK",
            "FAROE ISLANDS" => "FO",
            "FIJI" => "FJ",
            "FINLAND" => "FI",
            "FRANCE" => "FR",
            "FRENCH GUIANA" => "GF",
            "FRENCH POLYNESIA" => "PF",
            "FRENCH SOUTHERN TERRITORIES" => "TF",
            "GABON" => "GA",
            "GAMBIA" => "GM",
            "GEORGIA" => "GE",
            "GERMANY" => "DE",
            "GHANA" => "GH",
            "GIBRALTAR" => "GI",
            "GREECE" => "GR",
            "GREENLAND" => "GL",
            "GRENADA" => "GD",
            "GUADELOUPE" => "GP",
            "GUAM" => "GU",
            "GUATEMALA" => "GT",
            "GUERNSEY" => "GG",
            "GUINEA" => "GN",
            "GUINEA-BISSAU" => "GW",
            "GUYANA" => "GY",
            "HAITI" => "HT",
            "HEARD ISLAND AND MCDONALD ISLANDS" => "HM",
            "HOLY SEE (VATICAN CITY STATE)" => "VA",
            "HONDURAS" => "HN",
            "HONG KONG" => "HK",
            "HUNGARY" => "HU",
            "ICELAND" => "IS",
            "INDIA" => "IN",
            "INDONESIA" => "ID",
            "IRAN, ISLAMIC REPUBLIC OF" => "IR",
            "IRAQ" => "IQ",
            "IRELAND" => "IE",
            "ISLE OF MAN" => "IM",
            "ISRAEL" => "IL",
            "ITALY" => "IT",
            "JAMAICA" => "JM",
            "JAPAN" => "JP",
            "JERSEY" => "JE",
            "JORDAN" => "JO",
            "KAZAKHSTAN" => "KZ",
            "KENYA" => "KE",
            "KIRIBATI" => "KI",
            "KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF" => "KP",
            "SOUTH KOREA" => "KR",
            "KUWAIT" => "KW",
            "KYRGYZSTAN" => "KG",
            "LAO PEOPLE'S DEMOCRATIC REPUBLIC" => "LA",
            "LATVIA" => "LV",
            "LEBANON" => "LB",
            "LESOTHO" => "LS",
            "LIBERIA" => "LR",
            "LIBYA" => "LY",
            "LIECHTENSTEIN" => "LI",
            "LITHUANIA" => "LT",
            "LUXEMBOURG" => "LU",
            "MACAO" => "MO",
            "MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF" => "MK",
            "MADAGASCAR" => "MG",
            "MALAWI" => "MW",
            "MALAYSIA" => "MY",
            "MALDIVES" => "MV",
            "MALI" => "ML",
            "MALTA" => "MT",
            "MARSHALL ISLANDS" => "MH",
            "MARTINIQUE" => "MQ",
            "MAURITANIA" => "MR",
            "MAURITIUS" => "MU",
            "MAYOTTE" => "YT",
            "MEXICO" => "MX",
            "MICRONESIA, FEDERATED STATES OF" => "FM",
            "MOLDOVA, REPUBLIC OF" => "MD",
            "MONACO" => "MC",
            "MONGOLIA" => "MN",
            "MONTENEGRO" => "ME",
            "MONTSERRAT" => "MS",
            "MOROCCO" => "MA",
            "MOZAMBIQUE" => "MZ",
            "MYANMAR" => "MM",
            "NAMIBIA" => "NA",
            "NAURU" => "NR",
            "NEPAL" => "NP",
            "NETHERLANDS" => "NL",
            "NEW CALEDONIA" => "NC",
            "NEW ZEALAND" => "NZ",
            "NICARAGUA" => "NI",
            "NIGER" => "NE",
            "NIGERIA" => "NG",
            "NIUE" => "NU",
            "NORFOLK ISLAND" => "NF",
            "NORTHERN MARIANA ISLANDS" => "MP",
            "NORWAY" => "NO",
            "OMAN" => "OM",
            "PAKISTAN" => "PK",
            "PALAU" => "PW",
            "PALESTINE, STATE OF" => "PS",
            "PANAMA" => "PA",
            "PAPUA NEW GUINEA" => "PG",
            "PARAGUAY" => "PY",
            "PERU" => "PE",
            "PHILIPPINES" => "PH",
            "PITCAIRN" => "PN",
            "POLAND" => "PL",
            "PORTUGAL" => "PT",
            "PUERTO RICO" => "PR",
            "QATAR" => "QA",
            "RÉUNION" => "RE",
            "ROMANIA" => "RO",
            "RUSSIAN FEDERATION" => "RU",
            "RWANDA" => "RW",
            "SAINT BARTHÉLEMY" => "BL",
            "SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA" => "SH",
            "SAINT KITTS AND NEVIS" => "KN",
            "SAINT LUCIA" => "LC",
            "SAINT MARTIN (FRENCH PART)" => "MF",
            "SAINT PIERRE AND MIQUELON" => "PM",
            "SAINT VINCENT AND THE GRENADINES" => "VC",
            "SAMOA" => "WS",
            "SAN MARINO" => "SM",
            "SAO TOME AND PRINCIPE" => "ST",
            "SAUDI ARABIA" => "SA",
            "SENEGAL" => "SN",
            "SERBIA" => "RS",
            "SEYCHELLES" => "SC",
            "SIERRA LEONE" => "SL",
            "SINGAPORE" => "SG",
            "SINT MAARTEN (DUTCH PART)" => "SX",
            "SLOVAKIA" => "SK",
            "SLOVENIA" => "SI",
            "SOLOMON ISLANDS" => "SB",
            "SOMALIA" => "SO",
            "SOUTH AFRICA" => "ZA",
            "SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS" => "GS",
            "SOUTH SUDAN" => "SS",
            "SPAIN" => "ES",
            "SRI LANKA" => "LK",
            "SUDAN" => "SD",
            "SURINAME" => "SR",
            "SVALBARD AND JAN MAYEN" => "SJ",
            "SWAZILAND" => "SZ",
            "SWEDEN" => "SE",
            "SWITZERLAND" => "CH",
            "SYRIAN ARAB REPUBLIC" => "SY",
            "TAIWAN" => "TW",
            "TAJIKISTAN" => "TJ",
            "TANZANIA, UNITED REPUBLIC OF" => "TZ",
            "THAILAND" => "TH",
            "TIMOR-LESTE" => "TL",
            "TOGO" => "TG",
            "TOKELAU" => "TK",
            "TONGA" => "TO",
            "TRINIDAD AND TOBAGO" => "TT",
            "TUNISIA" => "TN",
            "TURKEY" => "TR",
            "TURKMENISTAN" => "TM",
            "TURKS AND CAICOS ISLANDS" => "TC",
            "TUVALU" => "TV",
            "UGANDA" => "UG",
            "UKRAINE" => "UA",
            "UNITED ARAB EMIRATES" => "AE",
            "UNITED KINGDOM" => "GB",
            "UNITED STATES" => "US",
            "UNITED STATES MINOR OUTLYING ISLANDS" => "UM",
            "URUGUAY" => "UY",
            "UZBEKISTAN" => "UZ",
            "VANUATU" => "VU",
            "VENEZUELA, BOLIVARIAN REPUBLIC OF" => "VE",
            "VIET NAM" => "VN",
            "VIRGIN ISLANDS, BRITISH" => "VG",
            "VIRGIN ISLANDS, U.S." => "VI",
            "WALLIS AND FUTUNA" => "WF",
            "WESTERN SAHARA" => "EH",
            "YEMEN" => "YE",
            "ZAMBIA" => "ZM",
            "ZIMBABWE" => "ZW");
    }
}