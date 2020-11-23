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
use Jariff\ProjectBundle\Util\Util;

use Jariff\MemberBundle\Form\SearchGlobal\SearchShipmentsEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomDataFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\DateRangeFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomCountryDataFormType;
use Jariff\MemberBundle\Model\SearchEmbed;
use Jariff\MemberBundle\Model\CustomData;
use Jariff\MemberBundle\Model\DateRange;
use Jariff\MemberBundle\Model\CustomCountryData;




class SupplierTradeMapController extends BaseController
{
    /**
     * @Route(
     *   "/member/big-picture/supplier/{slug}",
     *   name     = "trade_map_supplier", 
     *   defaults = {
     *     "slug"  : ""
     *   },
     *   requirements = {
     *   },
     *   options={"expose"=true}
     * )
     */
    public function indexAction($slug)
    {

        $data = $this->get('request')->get('search_form');

        $searchDocument = new ImportDocument();

        $today = new \DateTime('now');

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

        return $this->render('JariffProjectBundle:Default:tbp_export.html.twig',
            array(
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
     * Lists all Testimonial entities.
     *
     * @Route("/big-picture-tree/supplier/json/{slug}/{mode}/{key}/{sort}/{date_range}/{country}/{limit}/{offset}", 
     * name="trade_map_supplier_json_tree",
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
        $data = $this->repoMongo("JariffDocumentBundle:ExporterCompanyTree")->findOneBy(array('slug' => $slug));

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
     *   "/big-picture/suppliers-of/{key}/{page}",
     *   name     = "big_picture_search_supplier",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     */
    public function suppliersAction($key, $page = 1)
    {
        $result = null;

        if (!empty($key)) {
            $finder = $this->container->get('fos_elastica.finder.jariff.supplier');
            $res = $finder->createPaginatorAdapter($key);

            $result = $this->paginate($res, $page, 10);

            // $result->setUsedRoute('big_picture_search_supplier');
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
            'result' => $result,
            'message' => $message,
            'total' => empty($result) ? 0 : Util::ribuan($result->getTotalItemCount()),

            );
    }

     /**
     * @Route("/big-picture/ajax-supplier", name="ajax_big_picture_supplier_search")
     * @Template("JariffProjectBundle:Default:result-suppliers-big-picture.html.twig")
     * @Method("GET")
     */
     public function resultGlobalAction($page = 1)
     {
        $data = $this->getRequest()->get("key");

        $result = null;

        if (!empty($data)) {
            $finder = $this->container->get('fos_elastica.finder.jariff.supplier');
            $res = $finder->createPaginatorAdapter($data);

            $result = $this->paginate($res, $page, 10);

            $result->setParam('key', $data);
            // $result->setUsedRoute('big_picture_search_supplier');
            $message = "founded";


        }

        return array(
            'q' => isset($data) ? str_replace("+"," ",$data) : null,
            'result' => $result,
            'total' => empty($result) ? 0 : Util::ribuan($result->getTotalItemCount()),
            );
    }



    function checkForError() {

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
            echo ' - No errors';
            break;
            case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
            break;
            case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
            break;
            case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
            break;
            case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
            break;
            case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
            case JSON_ERROR_RECURSION:
            echo ' - Error recursion';
            break;
            case JSON_ERROR_INF_OR_NAN:
            echo ' - Error inf or Nan';
            break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
            echo ' - Error unsupported type';
            break;
            default:
            echo ' - Unknown error';
            break;
        }

    }

    public function generateTree()
    {
        $data = $this->repoMongo("JariffDocumentBundle:Suppliers")->findAll();

        foreach ($data as $row) {
            $consignee = $row->getValue()->getConsigneeName();

            foreach ($consignee as $cons) {

            }


        }
    }

    public function getBySlug($slug)
    {
        $data = $this->repoMongo("JariffDocumentBundle:Suppliers")->findOneBy(array('value.slug' => $slug));


        if (!empty($data))
            $dataChildConsignee = $data->getValue()->getConsigneeName();
        else
            return array();

        $dataArrayChild = array();
        foreach ($dataChildConsignee as $cons) {

        }

        return $dataArray = array(
            "id" => $slug,
            'name' => $data->getValue()->getShipperName(),
            'data' => array(),
            'children' => $dataArrayChild

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

    function utf8json($inArray, $depth, $theKey, $page) { 

        // static $depth = 0; 
        $isFinal = false;

        /* our return object */ 
        $newArray = array(); 

        /* safety recursion limit */ 
        $depth ++; 
        if($depth >= 18) { 
            $isFinal = true;
        } 

        // $inArray

        if ($theKey == 'children') {
            if ($depth < 3) {
                $arrayOfArray = array_chunk($inArray, 10);
            } else {
                $arrayOfArray = array_chunk($inArray, 5);
            }
            if (count($arrayOfArray) > 1) {
                $inArray = $arrayOfArray[$page];
            }
        } else {
            if (array_key_exists('children', $inArray)){
                $size = count($inArray['children']);
                $inArray['data']['size'] = $size;
            } 
        }


        /* step through inArray */ 
        foreach($inArray as $key=>$val) { 
            if(is_array($val)) { 
                /* recurse on array elements */ 
                if (!$isFinal) {
                    $newArray[$key] = $this->utf8json($val, $depth, $key, $page); 
                } 
            } else { 
                /* encode string values */ 
                $newArray[$key] = utf8_encode($val); 
            } 
        } 

        /* return utf8 encoded array */ 
        return $newArray; 
    } 



}
