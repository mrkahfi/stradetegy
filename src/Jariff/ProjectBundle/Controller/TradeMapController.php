<?php

namespace Jariff\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\DocumentBundle\Document\ImportDocument;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\MemberBundle\Model\SearchFieldCollect;
use Jariff\MemberBundle\Model\SearchFieldQ;
use Jariff\ProjectBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Form\SearchGlobalFormType;
// use Jariff\ProjectBundle\Util as Util;
use Jariff\ProjectBundle\Util\Util;
use Jariff\MemberBundle\Form\SearchGlobal\SearchShipmentsEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomDataFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\DateRangeFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomCountryDataFormType;
use Jariff\MemberBundle\Model\SearchEmbed;
use Jariff\MemberBundle\Model\CustomData;
use Jariff\MemberBundle\Model\DateRange;
use Jariff\MemberBundle\Model\CustomCountryData;

class TradeMapController extends BaseController
{
    /**
     * @Route(
     *   "/member/big-picture/buyer/{slug}",
     *   name     = "trade_map_buyer", 
     *   defaults = {
     *     "slug"  : ""
     *   },
     *   requirements = {
     *   },
     *   options={"expose"=true}
     * )
     */
    public function bigPictureShowAction($slug)
    {

        $data = $this->get('request')->get('search_form');

        $searchDocument = new ImportDocument();

        $today = new \DateTime('now');

        // $member_search_history = $this->repoMongo('JariffDocumentBundle:SearchHistory')->findBy(array(
        //     'user_id' => $this->getUser()->getId(),
        //     'date_search' => $today->format('Y-m-d')
        // ));
        
        $member_search = $this->repo('JariffMemberBundle:MemberSubscription')->findOneBy(array(
            'member' => $this->getUser()->getId(),
            'active' => true
        ));

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || ($active_subscription->getActive() != 1)) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }
        
      
        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = $slug;
        $entity->getQ()->add($searchQ);

        $customCountryData->setType('us');
        $customData->setType('imports');



        $form = $this->createForm(SearchShipmentsEmbedFormType::withPreferedChoice('all'), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || ($active_subscription->getActive() != 1)) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        $dateRange->setDateFrom($todayMin);
        $today = new \DateTime('now');
        $dateRange->setDateTo($today->format("Y-m-d"));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));



        return $this->render('JariffProjectBundle:Default:tbp_import.html.twig',
            array(
                'mode' => 'buyer_show',
                'slug' => $slug,
                'count_search_value' => $active_subscription->getSearchValue(),
                'downloadValue' => $member_search->getDownloadValue(),
                'downloadLimit' => $member_search->getDownloadLimit(),
                'form' => $form->createView(),
                'formDateRange' => $formDateRange->createView(),
                'formCustom' => $formCustom->createView(),
                'formCustomCountry' => $formCustomCountry->createView(),
            ));
    }


    /**
     * @Route("/member/search-of-big-picture", name="big_picture_index")
     * @Method("GET")
     * @Template("JariffProjectBundle:Default:tbp_search.html.twig")
     */
    public function indexAction()
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $customCountryData->setType('us');
        $customData->setType('imports');


        $form = $this->createForm(new SearchShipmentsEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
        ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
        ));

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || (!$active_subscription->isActive())) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        $dateRange->setDateFrom($todayMin);
        $today = new \DateTime('now');
        $dateRange->setDateTo($today->format("Y-m-d"));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
        ));


        return array(
            'mode' => 'buyer_index',
            'form' => $form->createView(),
            'formDateRange' => $formDateRange->createView(),
            'formCustom' => $formCustom->createView(),
            'formCustomCountry' => $formCustomCountry->createView(),
        );
    }


    /**
     * Lists all Testimonial entities.
     *
     * @Route("/big-picture-tree/buyer/json/{slug}/{mode}/{key}/{sort}/{date_range}/{country}/{limit}/{offset}", 
     * name="trade_map_buyer_json_tree",
     *   defaults = {
     *     "slug"  : "",
     *     "mode"  : "0",
     *     "key"   : ""
     *   },
     *   requirements = {
     *     "mode"  : "\d+"
     *   },
     *   options={"expose"=true})
     */
    public function loadTreeNewJsonAction($slug, $mode, $key, $sort, $date_range, $country, $limit, $offset)
    {   
        $data = $this->repoMongo("JariffDocumentBundle:ImporterCompanyTree")->findOneBy(array('slug' => $slug));

        if ($data) {
            $children = $data->getChild();

            $obj = new \stdClass();
            $obj->slug = $data->getSlug();
            $obj->name = ucwords(strtolower($data->getName()));
            $obj->company_as = $data->getCompanyAs();
            if ($data->getName() == 'N/A') {
                $obj->name = '';
            }

            $countries = array();
            $childrenArray = array();
            $totalShipment = 0;
            foreach ($children as $child) {

                $obj2 = new \stdClass();
                $obj2->slug = $child["slug"];
                $obj2->name = ucwords(strtolower($child["name"]));
                $obj2->shipment_count = $child["shipment_count"];
                $obj2->parent_name = $obj->name;
                $obj2->parent_slug = $obj->slug;
                $obj2->slug_country = $child["slug_country"];
                $obj2->country = $child["country"];
                $obj2->actual_arrival_date = $child["actual_arrival_date"];
                $obj2->product_desc = $child["product_desc"];
                $obj2->company_as = $data->getCompanyAs() == "importer" ? "exporter" : "importer";
                if ($child["name"] == 'N/A') {
                    $obj2->name = '';
                }
                $totalShipment = $totalShipment + $child["shipment_count"];

                $childrenArray[] = $obj2;
            }

            if ($sort == "0") {
                usort($childrenArray, array($this, "cmp"));
            } else {
                usort($childrenArray, array($this, "word"));
            }

            $childrenArray = array_slice($childrenArray, 0, intval($limit));

            foreach ($childrenArray as $child) {
               $countries[$child->slug_country] = trim($child->country);
            }
            
            $countries = array_unique($countries);

            if ($mode == 1) {
                $childrenArray = array_slice($childrenArray, 0, 5);
            }
            $obj->children = $childrenArray;
            $obj->countries = $countries;
            $obj->shipment_count = $totalShipment;

            // var_dump($obj);
            // exit();

            $json = json_encode($obj);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            $json = json_encode(new \stdClass());
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    public function cmp($a, $b)
    {
        return $b->shipment_count - $a->shipment_count;
    }

    public function word($a, $b)
    {
        return $b->name < $a->name;
    }

    /**
     * @Route(
     *   "/big-picture/buyers-of/{key}/{page}",
     *   name     = "big_picture_search_buyer",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     */
    public function buyersAction($key, $page = 1)
    {
        $result = null;

        if (!empty($key)) {
            $finder = $this->container->get('fos_elastica.finder.jariff.buyer');
            $res = $finder->createPaginatorAdapter($key);

            $result = $this->paginate($res, $page, 10);

            // $result->setUsedRoute('big_picture_search_buyer');
            $message = "founded";

        }

        $searchDocument = new ImportDocument();

        $formSearchGlobal = $this->createForm(new SearchGlobalFormType(), $searchDocument,
            array(
                'q' => isset($key) ? str_replace("+"," ",$key) : null,

                )
            );


        return array(
            'q' => isset($key) ? str_replace("+"," ",$key) : null,
            'resultBuyer' => $result,
            'message' => $message,
            'totalBuyer' => empty($result) ? 0 : Util::ribuan($result->getTotalItemCount()),
            );
    }


    /**
     * @Route("/big-picture/ajax-buyer", name="ajax_big_picture_buyer_search")
     * @Template("JariffProjectBundle:Default:result-buyers-big-picture.html.twig")
     * @Method("GET")
     */
    public function resultGlobalBuyerAction($page = 1)
    {
        $key = $this->getRequest()->get("key");

        $result = null;

        if (!empty($key)) {
            $finder = $this->container->get('fos_elastica.finder.jariff.buyer');
            $res = $finder->createPaginatorAdapter($key);

            $result = $this->paginate($res, $page, 10);

            $result->setParam('key', $key);
            // $result->setUsedRoute('big_picture_search_buyer');
            $message = "founded";


        }

        return array(
            'q' => isset($key) ? str_replace("+"," ",$key) : null,
            'resultBuyer' => $result,
            'message' => $message,
            'totalBuyer' => empty($result) ? 0 : Util::ribuan($result->getTotalItemCount()),
            );
    }


    function json_encode_ex_as_array(array $v)
    {
        for ($i = 0; $i < count($v); $i++) {
            if (!isset($v[$i])) {
                return false;
            }
        }
        return true;
    }

    function json_encode_ex($v)
    {
        if (is_object($v)) {
            $type = is_a($v, 'EncoderData') ? JSON_ENCODE_EX_EncoderDataObject : JSON_ENCODE_EX_OBJECT;
        } else if (is_array($v)) {
            $type = $this->json_encode_ex_as_array($v) ? JSON_ENCODE_EX_ARRAY : JSON_ENCODE_EX_OBJECT;
        } else {
            $type = JSON_ENCODE_EX_SCALAR;
        }

        switch ($type) {
            case JSON_ENCODE_EX_ARRAY: // array [...]
            foreach ($v as $value) {
                $rv[] = $this->json_encode_ex($value);
            }
            $rv = '[' . join(',', $rv) . ']';
            break;
            case JSON_ENCODE_EX_OBJECT: // object { .... }
            $rv = array();
            foreach ($v as $key => $value) {
                $rv[] = json_encode((string)$key) . ':' . $this->json_encode_ex($value);
            }
            $rv = '{' . join(',', $rv) . '}';
            break;
            case JSON_ENCODE_EX_EncoderDataObject:
            $rv = $this->json_encode_ex($v->getData());
            break;
            default:
            $rv = json_encode($v);
        }
        return $rv;
    }

    function country_code_to_country($code){
        $code = strtoupper($code);
        $country = '';
        if( $code == 'AF' ) $country = 'Afghanistan';
        if( $code == 'AX' ) $country = 'Aland Islands';
        if( $code == 'AL' ) $country = 'Albania';
        if( $code == 'DZ' ) $country = 'Algeria';
        if( $code == 'AS' ) $country = 'American Samoa';
        if( $code == 'AD' ) $country = 'Andorra';
        if( $code == 'AO' ) $country = 'Angola';
        if( $code == 'AI' ) $country = 'Anguilla';
        if( $code == 'AQ' ) $country = 'Antarctica';
        if( $code == 'AG' ) $country = 'Antigua and Barbuda';
        if( $code == 'AR' ) $country = 'Argentina';
        if( $code == 'AM' ) $country = 'Armenia';
        if( $code == 'AW' ) $country = 'Aruba';
        if( $code == 'AU' ) $country = 'Australia';
        if( $code == 'AT' ) $country = 'Austria';
        if( $code == 'AZ' ) $country = 'Azerbaijan';
        if( $code == 'BS' ) $country = 'Bahamas the';
        if( $code == 'BH' ) $country = 'Bahrain';
        if( $code == 'BD' ) $country = 'Bangladesh';
        if( $code == 'BB' ) $country = 'Barbados';
        if( $code == 'BY' ) $country = 'Belarus';
        if( $code == 'BE' ) $country = 'Belgium';
        if( $code == 'BZ' ) $country = 'Belize';
        if( $code == 'BJ' ) $country = 'Benin';
        if( $code == 'BM' ) $country = 'Bermuda';
        if( $code == 'BT' ) $country = 'Bhutan';
        if( $code == 'BO' ) $country = 'Bolivia';
        if( $code == 'BA' ) $country = 'Bosnia and Herzegovina';
        if( $code == 'BW' ) $country = 'Botswana';
        if( $code == 'BV' ) $country = 'Bouvet Island (Bouvetoya)';
        if( $code == 'BR' ) $country = 'Brazil';
        if( $code == 'IO' ) $country = 'British Indian Ocean Territory (Chagos Archipelago)';
        if( $code == 'VG' ) $country = 'British Virgin Islands';
        if( $code == 'BN' ) $country = 'Brunei Darussalam';
        if( $code == 'BG' ) $country = 'Bulgaria';
        if( $code == 'BF' ) $country = 'Burkina Faso';
        if( $code == 'BI' ) $country = 'Burundi';
        if( $code == 'KH' ) $country = 'Cambodia';
        if( $code == 'CM' ) $country = 'Cameroon';
        if( $code == 'CA' ) $country = 'Canada';
        if( $code == 'CV' ) $country = 'Cape Verde';
        if( $code == 'KY' ) $country = 'Cayman Islands';
        if( $code == 'CF' ) $country = 'Central African Republic';
        if( $code == 'TD' ) $country = 'Chad';
        if( $code == 'CL' ) $country = 'Chile';
        if( $code == 'CN' ) $country = 'China';
        if( $code == 'CX' ) $country = 'Christmas Island';
        if( $code == 'CC' ) $country = 'Cocos (Keeling) Islands';
        if( $code == 'CO' ) $country = 'Colombia';
        if( $code == 'KM' ) $country = 'Comoros the';
        if( $code == 'CD' ) $country = 'Congo';
        if( $code == 'CG' ) $country = 'Congo the';
        if( $code == 'CK' ) $country = 'Cook Islands';
        if( $code == 'CR' ) $country = 'Costa Rica';
        if( $code == 'CI' ) $country = 'Cote d\'Ivoire';
        if( $code == 'HR' ) $country = 'Croatia';
        if( $code == 'CU' ) $country = 'Cuba';
        if( $code == 'CY' ) $country = 'Cyprus';
        if( $code == 'CZ' ) $country = 'Czech Republic';
        if( $code == 'DK' ) $country = 'Denmark';
        if( $code == 'DJ' ) $country = 'Djibouti';
        if( $code == 'DM' ) $country = 'Dominica';
        if( $code == 'DO' ) $country = 'Dominican Republic';
        if( $code == 'EC' ) $country = 'Ecuador';
        if( $code == 'EG' ) $country = 'Egypt';
        if( $code == 'SV' ) $country = 'El Salvador';
        if( $code == 'GQ' ) $country = 'Equatorial Guinea';
        if( $code == 'ER' ) $country = 'Eritrea';
        if( $code == 'EE' ) $country = 'Estonia';
        if( $code == 'ET' ) $country = 'Ethiopia';
        if( $code == 'FO' ) $country = 'Faroe Islands';
        if( $code == 'FK' ) $country = 'Falkland Islands (Malvinas)';
        if( $code == 'FJ' ) $country = 'Fiji the Fiji Islands';
        if( $code == 'FI' ) $country = 'Finland';
        if( $code == 'FR' ) $country = 'France, French Republic';
        if( $code == 'GF' ) $country = 'French Guiana';
        if( $code == 'PF' ) $country = 'French Polynesia';
        if( $code == 'TF' ) $country = 'French Southern Territories';
        if( $code == 'GA' ) $country = 'Gabon';
        if( $code == 'GM' ) $country = 'Gambia the';
        if( $code == 'GE' ) $country = 'Georgia';
        if( $code == 'DE' ) $country = 'Germany';
        if( $code == 'GH' ) $country = 'Ghana';
        if( $code == 'GI' ) $country = 'Gibraltar';
        if( $code == 'GR' ) $country = 'Greece';
        if( $code == 'GL' ) $country = 'Greenland';
        if( $code == 'GD' ) $country = 'Grenada';
        if( $code == 'GP' ) $country = 'Guadeloupe';
        if( $code == 'GU' ) $country = 'Guam';
        if( $code == 'GT' ) $country = 'Guatemala';
        if( $code == 'GG' ) $country = 'Guernsey';
        if( $code == 'GN' ) $country = 'Guinea';
        if( $code == 'GW' ) $country = 'Guinea-Bissau';
        if( $code == 'GY' ) $country = 'Guyana';
        if( $code == 'HT' ) $country = 'Haiti';
        if( $code == 'HM' ) $country = 'Heard Island and McDonald Islands';
        if( $code == 'VA' ) $country = 'Holy See (Vatican City State)';
        if( $code == 'HN' ) $country = 'Honduras';
        if( $code == 'HK' ) $country = 'Hong Kong';
        if( $code == 'HU' ) $country = 'Hungary';
        if( $code == 'IS' ) $country = 'Iceland';
        if( $code == 'IN' ) $country = 'India';
        if( $code == 'ID' ) $country = 'Indonesia';
        if( $code == 'IR' ) $country = 'Iran';
        if( $code == 'IQ' ) $country = 'Iraq';
        if( $code == 'IE' ) $country = 'Ireland';
        if( $code == 'IM' ) $country = 'Isle of Man';
        if( $code == 'IL' ) $country = 'Israel';
        if( $code == 'IT' ) $country = 'Italy';
        if( $code == 'JM' ) $country = 'Jamaica';
        if( $code == 'JP' ) $country = 'Japan';
        if( $code == 'JE' ) $country = 'Jersey';
        if( $code == 'JO' ) $country = 'Jordan';
        if( $code == 'KZ' ) $country = 'Kazakhstan';
        if( $code == 'KE' ) $country = 'Kenya';
        if( $code == 'KI' ) $country = 'Kiribati';
        if( $code == 'KP' ) $country = 'Korea';
        if( $code == 'KR' ) $country = 'Korea';
        if( $code == 'KW' ) $country = 'Kuwait';
        if( $code == 'KG' ) $country = 'Kyrgyz Republic';
        if( $code == 'LA' ) $country = 'Lao';
        if( $code == 'LV' ) $country = 'Latvia';
        if( $code == 'LB' ) $country = 'Lebanon';
        if( $code == 'LS' ) $country = 'Lesotho';
        if( $code == 'LR' ) $country = 'Liberia';
        if( $code == 'LY' ) $country = 'Libyan Arab Jamahiriya';
        if( $code == 'LI' ) $country = 'Liechtenstein';
        if( $code == 'LT' ) $country = 'Lithuania';
        if( $code == 'LU' ) $country = 'Luxembourg';
        if( $code == 'MO' ) $country = 'Macao';
        if( $code == 'MK' ) $country = 'Macedonia';
        if( $code == 'MG' ) $country = 'Madagascar';
        if( $code == 'MW' ) $country = 'Malawi';
        if( $code == 'MY' ) $country = 'Malaysia';
        if( $code == 'MV' ) $country = 'Maldives';
        if( $code == 'ML' ) $country = 'Mali';
        if( $code == 'MT' ) $country = 'Malta';
        if( $code == 'MH' ) $country = 'Marshall Islands';
        if( $code == 'MQ' ) $country = 'Martinique';
        if( $code == 'MR' ) $country = 'Mauritania';
        if( $code == 'MU' ) $country = 'Mauritius';
        if( $code == 'YT' ) $country = 'Mayotte';
        if( $code == 'MX' ) $country = 'Mexico';
        if( $code == 'FM' ) $country = 'Micronesia';
        if( $code == 'MD' ) $country = 'Moldova';
        if( $code == 'MC' ) $country = 'Monaco';
        if( $code == 'MN' ) $country = 'Mongolia';
        if( $code == 'ME' ) $country = 'Montenegro';
        if( $code == 'MS' ) $country = 'Montserrat';
        if( $code == 'MA' ) $country = 'Morocco';
        if( $code == 'MZ' ) $country = 'Mozambique';
        if( $code == 'MM' ) $country = 'Myanmar';
        if( $code == 'NA' ) $country = 'Namibia';
        if( $code == 'NR' ) $country = 'Nauru';
        if( $code == 'NP' ) $country = 'Nepal';
        if( $code == 'AN' ) $country = 'Netherlands Antilles';
        if( $code == 'NL' ) $country = 'Netherlands the';
        if( $code == 'NC' ) $country = 'New Caledonia';
        if( $code == 'NZ' ) $country = 'New Zealand';
        if( $code == 'NI' ) $country = 'Nicaragua';
        if( $code == 'NE' ) $country = 'Niger';
        if( $code == 'NG' ) $country = 'Nigeria';
        if( $code == 'NU' ) $country = 'Niue';
        if( $code == 'NF' ) $country = 'Norfolk Island';
        if( $code == 'MP' ) $country = 'Northern Mariana Islands';
        if( $code == 'NO' ) $country = 'Norway';
        if( $code == 'OM' ) $country = 'Oman';
        if( $code == 'PK' ) $country = 'Pakistan';
        if( $code == 'PW' ) $country = 'Palau';
        if( $code == 'PS' ) $country = 'Palestinian Territory';
        if( $code == 'PA' ) $country = 'Panama';
        if( $code == 'PG' ) $country = 'Papua New Guinea';
        if( $code == 'PY' ) $country = 'Paraguay';
        if( $code == 'PE' ) $country = 'Peru';
        if( $code == 'PH' ) $country = 'Philippines';
        if( $code == 'PN' ) $country = 'Pitcairn Islands';
        if( $code == 'PL' ) $country = 'Poland';
        if( $code == 'PT' ) $country = 'Portugal, Portuguese Republic';
        if( $code == 'PR' ) $country = 'Puerto Rico';
        if( $code == 'QA' ) $country = 'Qatar';
        if( $code == 'RE' ) $country = 'Reunion';
        if( $code == 'RO' ) $country = 'Romania';
        if( $code == 'RU' ) $country = 'Russian Federation';
        if( $code == 'RW' ) $country = 'Rwanda';
        if( $code == 'BL' ) $country = 'Saint Barthelemy';
        if( $code == 'SH' ) $country = 'Saint Helena';
        if( $code == 'KN' ) $country = 'Saint Kitts and Nevis';
        if( $code == 'LC' ) $country = 'Saint Lucia';
        if( $code == 'MF' ) $country = 'Saint Martin';
        if( $code == 'PM' ) $country = 'Saint Pierre and Miquelon';
        if( $code == 'VC' ) $country = 'Saint Vincent and the Grenadines';
        if( $code == 'WS' ) $country = 'Samoa';
        if( $code == 'SM' ) $country = 'San Marino';
        if( $code == 'ST' ) $country = 'Sao Tome and Principe';
        if( $code == 'SA' ) $country = 'Saudi Arabia';
        if( $code == 'SN' ) $country = 'Senegal';
        if( $code == 'RS' ) $country = 'Serbia';
        if( $code == 'SC' ) $country = 'Seychelles';
        if( $code == 'SL' ) $country = 'Sierra Leone';
        if( $code == 'SG' ) $country = 'Singapore';
        if( $code == 'SK' ) $country = 'Slovakia (Slovak Republic)';
        if( $code == 'SI' ) $country = 'Slovenia';
        if( $code == 'SB' ) $country = 'Solomon Islands';
        if( $code == 'SO' ) $country = 'Somalia, Somali Republic';
        if( $code == 'ZA' ) $country = 'South Africa';
        if( $code == 'GS' ) $country = 'South Georgia and the South Sandwich Islands';
        if( $code == 'ES' ) $country = 'Spain';
        if( $code == 'LK' ) $country = 'Sri Lanka';
        if( $code == 'SD' ) $country = 'Sudan';
        if( $code == 'SR' ) $country = 'Suriname';
        if( $code == 'SJ' ) $country = 'Svalbard & Jan Mayen Islands';
        if( $code == 'SZ' ) $country = 'Swaziland';
        if( $code == 'SE' ) $country = 'Sweden';
        if( $code == 'CH' ) $country = 'Switzerland, Swiss Confederation';
        if( $code == 'SY' ) $country = 'Syrian Arab Republic';
        if( $code == 'TW' ) $country = 'Taiwan';
        if( $code == 'TJ' ) $country = 'Tajikistan';
        if( $code == 'TZ' ) $country = 'Tanzania';
        if( $code == 'TH' ) $country = 'Thailand';
        if( $code == 'TL' ) $country = 'Timor-Leste';
        if( $code == 'TG' ) $country = 'Togo';
        if( $code == 'TK' ) $country = 'Tokelau';
        if( $code == 'TO' ) $country = 'Tonga';
        if( $code == 'TT' ) $country = 'Trinidad and Tobago';
        if( $code == 'TN' ) $country = 'Tunisia';
        if( $code == 'TR' ) $country = 'Turkey';
        if( $code == 'TM' ) $country = 'Turkmenistan';
        if( $code == 'TC' ) $country = 'Turks and Caicos Islands';
        if( $code == 'TV' ) $country = 'Tuvalu';
        if( $code == 'UG' ) $country = 'Uganda';
        if( $code == 'UA' ) $country = 'Ukraine';
        if( $code == 'AE' ) $country = 'United Arab Emirates';
        if( $code == 'GB' ) $country = 'United Kingdom';
        if( $code == 'US' ) $country = 'United States of America';
        if( $code == 'UM' ) $country = 'United States Minor Outlying Islands';
        if( $code == 'VI' ) $country = 'United States Virgin Islands';
        if( $code == 'UY' ) $country = 'Uruguay, Eastern Republic of';
        if( $code == 'UZ' ) $country = 'Uzbekistan';
        if( $code == 'VU' ) $country = 'Vanuatu';
        if( $code == 'VE' ) $country = 'Venezuela';
        if( $code == 'VN' ) $country = 'Vietnam';
        if( $code == 'WF' ) $country = 'Wallis and Futuna';
        if( $code == 'EH' ) $country = 'Western Sahara';
        if( $code == 'YE' ) $country = 'Yemen';
        if( $code == 'ZM' ) $country = 'Zambia';
        if( $code == 'ZW' ) $country = 'Zimbabwe';
        if( $country == '') $country = $code;
        return $country;
    }    

}
