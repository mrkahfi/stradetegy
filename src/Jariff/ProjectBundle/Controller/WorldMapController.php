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

use Jariff\ProjectBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Form\SearchGlobalFormType;
// use Jariff\ProjectBundle\Util as Util;
use Jariff\ProjectBundle\Util\Util;




// define('JSON_ENCODE_EX_SCALAR', 0);
// define('JSON_ENCODE_EX_ARRAY', 1);
// define('JSON_ENCODE_EX_OBJECT', 2);
// define('JSON_ENCODE_EX_EncoderDataObject', 3);

class WorldMapController extends BaseController
{
    /**
     * @Route(
     *   "/world-map/buyer/{slug}/{page}",
     *   name     = "world_map_buyer", 
     *   defaults = {
     *     "slug"  : "",
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *   options={"expose"=true}
     * )
     */
    public function indexAction($slug, $page)
    {

        $data = $this->get('request')->get('search_form');

        $searchDocument = new ImportDocument();

        $formSearchGlobal = $this->createForm(new SearchGlobalFormType(), $searchDocument,
            array(
                'q' => isset($data['q']) ? $data['q'] : null,
                )
            );

        // search



        return $this->render('JariffProjectBundle:Default:world_map.html.twig',
            array(
                'formSearchGlobal' => $formSearchGlobal->createView(),
                'slug' => $slug,
                'page' => $page - 1
                ));
    }

    /**
     * Lists all Testimonial entities.
     *
     * @Route("/world-map/buyer/json/{slug}/{page}", 
     * name="world_map_buyer_json",
     *   defaults = {
     *     "slug"  : "",
     *     "page"  : "0"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *   options={"expose"=true})
     */
    public function loadJsonAction($slug, $page)
    {
        $dataChild2 = $this->repoMongo("JariffDocumentBundle:TradeMap")->findOneBy(array('parent' => $slug));
        $page++;
        $json = $dataChild2->getTree();
        $obj = json_decode($json);
        $obj2 = new \stdClass();
        $obj2->id = $obj->id;
        $obj2->name = $obj->name;
        $obj2->data = $obj->data;
        $children = array();
        for ($i=($page*10)-10; $i < 10*$page; $i++) { 
            $child = $obj->children[$i];
            $child2 = new \stdClass();
            $child2->id = $child->id;
            $child2->name = $child->name;
            $child2->data = $child->data;
            if ($child2->data != '-pruned-') {
                $children2 = array();
                if (property_exists($child, 'children')) {
                    for ($j=0; $j < count($child->children); $j++) { 
                        $grandChild = $child->children[$j];
                        $grandChild2 = new \stdClass();
                        $grandChild2->id = $grandChild->id;
                        $grandChild2->name = $grandChild->name;
                        $grandChild2->data = $grandChild->data;
                        $children2[] = $grandChild2;
                        if ($j == 5) break;
                    }
                }
                $child2->children = $children2;
                $children[] = $child2;
            }
        }
        $obj2->children = $children;
        $newJson = json_encode($obj2);
        // $array = unserialize($serial);
        // $newArray = $this->utf8json($array, 0, '', $page);
        // $json = json_encode($newArray);
        $response = new Response($newJson);
        $response->headers->set('Content-Type', 'application/json');

        return $response;


    }

    /**
     * @Route(
     *   "/world-map/buyers-of/{key}/{page}",
     *   name     = "world_map_search_buyer",
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

            // $result->setUsedRoute('world_map_search_buyer');
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
     * @Route("/world-map/ajax-buyer", name="ajax_world_map_buyer_search")
     * @Template("JariffProjectBundle:Default:result-buyers-world-map.html.twig")
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
            // $result->setUsedRoute('world_map_search_buyer');
            $message = "founded";


        }

        return array(
            'q' => isset($key) ? str_replace("+"," ",$key) : null,
            'resultBuyer' => $result,
            'message' => $message,
            'totalBuyer' => empty($result) ? 0 : Util::ribuan($result->getTotalItemCount()),
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
        if($depth >= 6) { 
            $isFinal = true;
        } 

        if ($theKey == 'children') {
            if ($depth < 3) {
                $arrayOfArray = array_chunk($inArray, 10);
                if (count($arrayOfArray) > 1) {
                    $inArray = $arrayOfArray[$page];
                }
            } else {
                $arrayOfArray = array_chunk($inArray, 5);
                if (count($arrayOfArray) > 1) {
                    $inArray = $arrayOfArray[0];
                }
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
