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
use Jariff\ProjectBundle\Util\Util;

class GlobalSearchController extends BaseController
{
    /**
     * @Route(
     *   "/searchp/{page}",
     *   name     = "global_search",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   }
     * )
     *
     *
     */
    public function indexAction($page = 1)
    {

        $data = $this->get('request')->get('search_form');

        $searchDocument = new ImportDocument();

        $formSearchGlobal = $this->createForm(new SearchGlobalFormType(), $searchDocument,
            array(
                'q' => isset($data['q']) ? $data['q'] : null,

            )
        );


        return $this->render('JariffProjectBundle:Default:global_search.html.twig',
            array(
                'formSearchGlobal' => $formSearchGlobal->createView(),
            ));
    }

    /**
     * @Route(
     *   "/suppliers-of/{key}/{page}",
     *   name     = "global_search_supplier",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     *
     *
     */
    public function suppliersAction($key, $page = 1)
    {
        $result = null;

        if (!empty($key)) {
            $finder = $this->container->get('fos_elastica.finder.jariff.supplier');
            $res = $finder->createPaginatorAdapter($key);

            $result = $this->paginate($res, $page, 10);

            $result->setUsedRoute('global_search_supplier');
            $message = "founded";


        }

        $searchDocument = new ImportDocument();

        $formSearchGlobal = $this->createForm(new SearchGlobalFormType(), $searchDocument,
            array(
                'q' => isset($key) ? str_replace("+", " ", $key) : null,

            )
        );


        return $this->render('JariffProjectBundle:Default:global_search.html.twig',
            array(
                'formSearchGlobal' => $formSearchGlobal->createView(),
                'q' => isset($key) ? str_replace("+", " ", $key) : null,
                'result' => $result,
                'message' => $message,
                'total' => empty($result) ? 0 : Util::ribuan($result->getTotalItemCount()),

            ));
    }

    /**
     * @Route(
     *   "/suppliers-count",
     *   name     = "ajax_count_supplier",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     *
     *
     */
    public function ajaxCountSuppliersAction()
    {

        $key = $this->getRequest()->get("key");
        $result = null;

        if (!empty($key)) {
            $finder = $this->container->get('fos_elastica.finder.jariff.supplier');
            $res = $finder->createPaginatorAdapter($key);

            $result = $this->paginate($res, 1, 10);

            $result->setUsedRoute('global_search_supplier');


        }


        return new Response(empty($result) ? 0 : Util::ribuan($result->getTotalItemCount()));
    }

    /**
     * @Route("/ajax-supplier", name="ajax_global_supplier_search")
     * @Template("JariffProjectBundle:Default:result-suppliers.html.twig")
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
            $result->setUsedRoute('global_search_supplier');
            $message = "founded";


        }

        return array(
            'q' => isset($data) ? str_replace("+", " ", $data) : null,
            'result' => $result,

            'total' => empty($result) ? 0 : Util::ribuan($result->getTotalItemCount()),
        );
    }

    /**
     * @Route("/ajax-facets-country-buyer", name="ajax_global_buyer_facets_country")
     * @Method("GET")
     */
    public function resultBuyerFacetsCountryAction()
    {
        $data = $this->getRequest()->get("key");

        $finder = $this->container->get('fos_elastica.finder.jariff.supplier');
        $query = new \Elastica\Query();
        $facet = new \Elastica\Facet\Terms('tags');
        $facet->setField("value.slugCountry");
        $facet->setSize(10);
        $facet->setNested("value");


        $queryDSL = array(
            'query' => array(
                'query_string' => array(
                    "query" => "$data"
                )
            ),
            "highlight" => array(
                "number_of_fragments" => 3,
                "fragment_size" => 150,
                "tag_schema" => "styled",
                "fields" => array(
                    "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "value.shipper_name" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "value.shipper_address" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "value.product_description" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "value.consignee_name" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>"))
                )
            )
        );

        $query->setRawQuery($queryDSL);
        $query->addFacet($facet);
        $res = $finder->findPaginated($query);


//

        echo "Searching Supplier keyword $data <br/><br/>";

        echo "Example result total country : <br/>";
        foreach ($res->getAdapter()->getFacets() as $r) {
            foreach ($r['terms'] as $row) {
                echo $row["term"] . " " . $row['count'] . "<br/>";
            }
        }
        echo "<br/>";echo "<br/>";

        echo "Example result location found : <br/>";
        echo "<br/>";$data = $this->getRequest()->get("key");

        $finder = $this->container->get('fos_elastica.finder.jariff.supplier');
        $query = new \Elastica\Query();
        $facet = new \Elastica\Facet\Terms('tags');
        $facet->setField("value.slugCountry");
        $facet->setSize(10);
        $facet->setNested("value");


        $queryDSL = array(
            'query' => array(
                'query_string' => array(
                    "query" => "$data"
                )
            ),
            "highlight" => array(
                "number_of_fragments" => 3,
                "fragment_size" => 150,
                "tag_schema" => "styled",
                "fields" => array(
                    "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "value.shipper_name" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "value.shipper_address" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "value.product_description" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "value.consignee_name" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>"))
                )
            )
        );

        $query->setRawQuery($queryDSL);
        $query->addFacet($facet);
        $res = $finder->findPaginated($query);
        $resHybird = $finder->findHybrid($query);
        foreach($resHybird as $rHybird)
        {
            echo "<a href=''>".$rHybird->getTransformed()->getValue()->getShipperName()."</a><br/>";

            echo "Matches In <br/>";

            $match = $rHybird->getResult()->getHit()["highlight"];

            if(isset($match['value.shipper_name'])){
                echo "Shipper Name : ".$match['value.shipper_name'][0];
                echo "<br/>";
            }

            if(isset($match['value.shipper_address'])){
                echo "Shipper Address : ".$match['value.shipper_address'][0];
                echo "<br/>";
            }

            if(isset($match['value.consignee_name'])){
                    if(count($match['value.consignee_name']) > 1){
                        echo "Customer : ".$match['value.consignee_name'][0]. " and ".count($match['value.consignee_name'])." is hidden.";
                    }else{
                        echo "Customer : ".$match['value.consignee_name'][0];
                    }
                echo "<br/>";
            }

            if(isset($match['value.product_description'])){
                echo "Product Description : ".$match['value.product_description'][0];
            }
            echo "<br/>";
            echo "<hr/>";
        }

//        $result = $this->paginate($res, empty($cur_page) ? 1 : $cur_page, empty($records_per_page) ? 1 : $records_per_page);
//
//        foreach($result->getTransformed() as $ress){
//           var_dump($res->getTransformed());
//        }

//        echo $result->getTotalItemCount();
        die();
    }

    /**
     * @Route(
     *   "/buyers-of/{key}/{page}",
     *   name     = "global_search_buyer",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     *
     *
     */
    public function buyersAction($key, $page = 1)
    {
        $result = null;

        if (!empty($key)) {
            $finder = $this->container->get('fos_elastica.finder.jariff.buyer');
            $res = $finder->createPaginatorAdapter($key);

            $result = $this->paginate($res, $page, 10);

            $result->setUsedRoute('global_search_buyer');
            $message = "founded";

        }

        $searchDocument = new ImportDocument();

        $formSearchGlobal = $this->createForm(new SearchGlobalFormType(), $searchDocument,
            array(
                'q' => isset($key) ? str_replace("+", " ", $key) : null,

            )
        );


        return $this->render('JariffProjectBundle:Default:global_search.html.twig',
            array(
                'formSearchGlobal' => $formSearchGlobal->createView(),
                'q' => isset($key) ? str_replace("+", " ", $key) : null,
                'resultBuyer' => $result,
                'message' => $message,
                'totalBuyer' => empty($result) ? 0 : Util::ribuan($result->getTotalItemCount()),
            ));
    }

    /**
     * @Route("/ajax-buyer", name="ajax_global_buyer_search")
     * @Template("JariffProjectBundle:Default:result-buyers.html.twig")
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
            $result->setUsedRoute('global_search_buyer');
            $message = "founded";


        }

        return array(
            'q' => isset($key) ? str_replace("+", " ", $key) : null,
            'resultBuyer' => $result,
            'message' => $message,
            'totalBuyer' => empty($result) ? 0 : Util::ribuan($result->getTotalItemCount()),
        );
    }

    /**
     * @Route(
     *   "/buyers-count",
     *   name     = "ajax_count_buyer",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     *
     *
     */
    public function ajaxCountBuyersAction()
    {

        $key = $this->getRequest()->get("key");
        $result = null;

        if (!empty($key)) {
            $finder = $this->container->get('fos_elastica.finder.jariff.buyer');
            $res = $finder->createPaginatorAdapter($key);

            $result = $this->paginate($res, 1, 10);

        }


        return new Response(empty($result) ? 0 : Util::ribuan($result->getTotalItemCount()));
    }

//http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=

}
