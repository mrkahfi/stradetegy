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

class RivalMarkController extends BaseController
{

    /**
     * @Route("/ajax-buyerof-product-by-country/{queryString}", name="ajax_buyer_of_product_by_country")
     * @Method("GET")
     */
    public function findBuyerOfProductByCountryAction($queryString)
    {
        $data = $this->getRequest()->get("key");

        $finder = $this->container->get('fos_elastica.finder.jariff.buyer');
        $query = new \Elastica\Query\QueryString($queryString);
        $facet = new \Elastica\Filter\Term(array('slugCountry' => 'china'));
        // $facet->setSize(10);

        $filteredQuery = new \Elastica\Query\Filtered($query, $facet);
        $results = $finder->find($filteredQuery);
        // $facet->setNested("value");


        // $queryDSL = array(
        //     'query' => array(
        //         'query_string' => array(
        //             "query" => "$data"
        //             )
        //         ),
        //     "highlight" => array(
        //         "number_of_fragments" => 3,
        //         "fragment_size" => 150,
        //         "tag_schema" => "styled",
        //         "fields" => array(
        //             "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
        //             "value.shipper_name" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
        //             "value.shipper_address" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
        //             "value.product_description" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
        //             "value.consignee_name" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>"))
        //             )
        //         )
        //     );
        var_dump($results);
        die();

    }

}

