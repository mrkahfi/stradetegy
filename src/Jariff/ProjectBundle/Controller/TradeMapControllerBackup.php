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
use Jariff\ProjectBundle\Util as Util;


class TradeMapController extends BaseController
{
    /**
     * @Route(
     *   "/trade-map/{slug}",
     *   name     = "trade_map"
     * )
     *
     *
     */
    public function indexAction($slug)
    {

        $data = $this->get('request')->get('search_form');

        $searchDocument = new ImportDocument();

        $formSearchGlobal = $this->createForm(new SearchGlobalFormType(), $searchDocument,
            array(
                'q' => isset($data['q']) ? $data['q'] : null,

                )
            );


        return $this->render('JariffProjectBundle:Default:trade_map.html.twig',
            array(
                'formSearchGlobal' => $formSearchGlobal->createView(),
                'slug' => $slug
                ));
    }


    /**
     * Lists all Testimonial entities.
     *
     * @Route("/trade-map/json/{slug}", name="trade_map_json")
     *
     *
     */
    public function loadJsonAction($slug)
    {
        $dataChildConsignee = array();
        $dataChildConsignee2 = array();
        $dataChildConsignee3 = array();


//        $dataChild = $this->repoMongo("JariffDocumentBundle:Suppliers")->findOneBy(array('value.slug' => $slug));
        $dataChild2 = $this->repoMongo("JariffDocumentBundle:TradeMap")->findOneBy(array('parent' => $slug));




//        if (!empty($dataChild))
//            $dataChildConsignee = $dataChild->getValue()->getConsigneeName();
//        else
//            return new Response(json_encode(array()));
//
////        foreach ($customers as $customer) {
////            echo Util\Util::slugify($customer) . "<br/>";
////        }
////        die();
//
//        $dataArrayChild = array();
//
//
//        foreach ($dataChildConsignee as $dt) {
//            $slugChild = Util\Util::slugify($dt);
//
//            $dataChild2 = $this->repoMongo("JariffDocumentBundle:Suppliers")->findOneBy(array('value.slug' => $slugChild));
////
//            $dataBand = "";
//            if (!empty($dataChild2)) {
//                $dataBand = $dataChild2->getValue()->getShipperName();
//                $dataChildConsignee2 = $dataChild2->getValue()->getConsigneeName();
//            }
////
//
//
//            $dataArrayChild2 = array();
//            foreach ($dataChildConsignee2 as $dt2) {
//
//                $slugChild2 = Util\Util::slugify($dt2);
//                $dataChild3 = $this->repoMongo("JariffDocumentBundle:Suppliers")->findOneBy(array('value.slug' => $slugChild2));
//
//                if (!empty($dataChild3))
//                    $dataChildConsignee3 = $dataChild3->getValue()->getConsigneeName();
//
//                $dataArrayChild3 = array();
//                foreach ($dataChildConsignee3 as $dt3) {
//
//                    $slugChild3 = Util\Util::slugify($dt3);
//
//                    $dataArrayChild3[] = array(
//                        'id' => $slugChild3,
//                        'name' => $dt3,
//                        'data' => array(
//                            'band' => $dt2,
//                            'relation' => 'Buyer of band'
//                        ),
//                        'children' => array()
//                    );
//                }
//
//                $dataArrayChild2[] = array(
//                    'id' => $slugChild2,
//                    'name' => $dt2,
//                    'data' => array(
//                        'band' =>$dataBand,
//                        'relation' => 'Buyer of band'
//                    ),
//                    'children' => $dataArrayChild3
//                );
//            }
//
//
//            $dataArrayChild[] = array(
//                'id' => $slugChild,
//                'name' => $dt,
//                'data' => array(
//                    'band' => $dataChild->getValue()->getShipperName(),
//                    'relation' => 'Buyer of band'
//                ),
//                'children' => $dataArrayChild2
//            );
//        }
//
//        $dataArray = array(
//            "id" => $slug,
//            'name' => $dataChild->getValue()->getShipperName(),
//            'data' => array(),
//            'children' => $dataArrayChild
//
//        );

        $serial = $dataChild2->getTree();
        $array = unserialize($serial);
        $json = self::json_encode_ex($array);

        return new Response($json);


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


    function utf8json($inArray) { 

        static $depth = 0; 

        /* our return object */ 
        $newArray = array(); 

        /* safety recursion limit */ 
        $depth ++; 
        if($depth >= '30') { 
            return false; 
        } 

        /* step through inArray */ 
        foreach($inArray as $key=>$val) { 
            if(is_array($val)) { 
                /* recurse on array elements */ 
                $newArray[$key] = utf8json($val); 
            } else { 
                /* encode string values */ 
                $newArray[$key] = utf8_encode($val); 
            } 
        } 

        /* return utf8 encoded array */ 
        return $newArray; 
    } 

}
