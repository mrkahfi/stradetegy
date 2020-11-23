<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\BigPicture;

use Jariff\DocumentBundle\Document\KeywordHistory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\DocumentBundle\Document\ImportDocument;
use Jariff\DocumentBundle\Document\KeywordDocument;
use Jariff\DocumentBundle\Document\CompanyDetail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\AdminBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Util as Util;

use Elastica\Client;
use Elastica\Aggregation\TopHits;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\Match;

/**
 * @Route("/member")
 */
class ExporterBigPictureController extends BaseController
{
    /**
     * @Route(
     *   "/big-picture/exporter/json/{slug}/{mode}/{key}/{sort}/{date_range}/{country}/{limit}/{offset}", 
     * name="member_suppliers_big_picture_json",
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
    public function exporterBigPictureJsonAction($slug, $mode, $key, $sort, $date_range, $country, $limit, $offset)
    {

        $client = new Client(array("host" => $this->container->getParameter('es_server')));
        $index = $client->getIndex('trade-usa-imports');

        $query = new Query();

        // set up the aggregation
        $termsAgg = new Terms("group_by_slug_consignee_name");
        $termsAgg->setField("slug_consignee_name");

        // add the aggregation to a Query object
        $query->addAggregation($termsAgg);

        $topTagAggs = new TopHits("top_tag_hits");
        $include = array(
            'shipper_name',
            'consignee_name',
            'product_desc',
            'actual_arrival_date',
            'country',
            'slug_country',
            'slug_country_ori_consignee'
            );
        $topTagAggs->setSource($include);
        $termsAgg->addAggregation($topTagAggs);

        // add the aggregation to a Query object
        $query->addAggregation($termsAgg);

        $match = new Match();
        $match->setField('slug_shipper_name', $slug);

        $query->setQuery($match);

        // retrieve the results
        $aggs = $index->search($query)->getAggregations();

        $buckets = $aggs["group_by_slug_consignee_name"]["buckets"];

        // echo "bucket count " + count($buckets);
        $rootSource = array();
        $rootName = "";
        if (count($buckets) > 0) {
            $hits = $buckets[0]["top_tag_hits"]["hits"];
            $rootSource = $hits["hits"][0]["_source"];
            $rootName = $rootSource["shipper_name"];
        }

        
        $children = array();
        $countries = array();
        $totalShipments = 0;

        foreach ($buckets as $bucket) {
            $child = new \stdClass();
            $child->slug = $bucket["key"];
            $child->shipment_count = $bucket["doc_count"];
            $source = $bucket["top_tag_hits"]["hits"]["hits"][0]["_source"];
            $child->name = $source["consignee_name"];
            $child->actual_arrival_date = $source["actual_arrival_date"];
            $child->country = $source["country"];
            $child->slug_country = $source["slug_country_ori_consignee"];
            $child->product_desc = $source["product_desc"];
            $child->company_as = "importer";
            $children[] = $child;

            $totalShipments = $totalShipments + $bucket["doc_count"];
        }

        $children = array_slice($children, 0, intval($limit));

        foreach ($children as $child) {
            $countries[$child->slug_country] = trim($child->country);
        }
            
        $countries = array_unique($countries);

        if ($mode == 1) {
            $children = array_slice($children, 0, 5);
        }



        $root = new \stdClass();
        $root->slug = $slug;
        $root->name = $rootName;
        $root->company_as = "exporter";
        $root->countries = $countries;    
        $root->shipment_count = $totalShipments;    
        $root->children = $children;
        
        $json = json_encode($root);
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


}