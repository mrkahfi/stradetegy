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
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldUsImportFormType;
use Jariff\MemberBundle\Model\FieldUsCustom;

use Jariff\AdminBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Util as Util;

use Elastica\Client;
use Elastica\Aggregation\TopHits;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\Match;

use Pagerfanta\View\TwitterBootstrap3View;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ElasticaAdapter;
use Elastica\Index;
use Elastica\Query as QueryEs;
use Elastica\Client as ClientEs;

/**
 * @Route("/member")
 */
class ImporterBigPictureController extends BaseController
{
    /**
     * @Route(
     *   "/big-picture/importer/json/{slug}/{mode}/{key}/{sort}/{date_range}/{country}/{limit}/{offset}", 
     * name="member_buyers_big_picture_json",
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
    public function importerBigPictureJsonAction($slug, $mode, $key, $sort, $date_range, $country, $limit, $offset)
    {

        $client = new Client(array("host" => $this->container->getParameter('es_server')));
        $index = new Index($client, 'trade-usa-imports');

        $query = new Query();

        // set up the aggregation
        $termsAgg = new Terms("group_by_slug_shipper_name");
        $termsAgg->setField("slug_shipper_name");

        // add the aggregation to a Query object
        // $query->addAggregation($termsAgg);

        $topTagAggs = new TopHits("top_tag_hits");
        $include = array(
            'shipper_name',
            'consignee_name',
            'product_desc',
            'actual_arrival_date',
            'country',
            'slug_country',
            'slug_country_ori_shipper'
            );
        $topTagAggs->setSource($include);
        $termsAgg->addAggregation($topTagAggs);

        // add the aggregation to a Query object
        $query->addAggregation($termsAgg);

        $match = new Match();
        $match->setField('slug_consignee_name', $slug);

        $query->setQuery($match);

        // retrieve the results
        $aggs = $index->search($query)->getAggregations();

        $buckets = $aggs["group_by_slug_shipper_name"]["buckets"];

        $rootSource = array();
        $rootName = "";
        if (count($buckets) > 0) {
            $hits = $buckets[0]["top_tag_hits"]["hits"];
            $rootSource = $hits["hits"][0]["_source"];
            $rootName = $rootSource["consignee_name"];
        }

        
        $children = array();
        $countries = array();
        $totalShipments = 0;

        foreach ($buckets as $bucket) {
            $child = new \stdClass();
            $child->slug = $bucket["key"];
            $child->shipment_count = $bucket["doc_count"];
            $source = $bucket["top_tag_hits"]["hits"]["hits"][0]["_source"];
            $child->name = $source["shipper_name"];
            $child->actual_arrival_date = $source["actual_arrival_date"];
            $child->country = $source["country"];
            $child->slug_country = $source["slug_country_ori_shipper"];
            $child->product_desc = $source["product_desc"];
            $child->company_as = "exporter";
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
        $root->company_as = "importer";
        $root->countries = $countries;    
        $root->shipment_count = $totalShipments;    
        $root->children = $children;
        
        $json = json_encode($root);
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    
    /**
     * @Route(
     *   "/big-picture/importer/search/us-import/{slug}/{page}", 
     * name="member_importer_big_picture_search",
     *   options={"expose"=true})
     * @Template("JariffMemberBundle:BigPicture\USImporter:results.html.twig")
     */
    public function importerBigPictureSearchAction($slug,$page=1)
    {
        
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || ($active_subscription->getActive() != 1)) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        
        $today = new \DateTime('now');
       

        $fields = array('consignee_name');

        $cache['search'][] =
                       (object) array(
                            'condition' => null,
                            'collect' => 'consignee_name',
                            'q' => $slug
                        );

                $cache['date_range'] = (object)array(
                                'from' => $todayMin,
                                'to' => $today->format("Y-m-d"),
                                'not_valid_date' => null,
                            );

        $queryDSL = $this->queryDSL((object)$cache,"actual_arrival_date","product_desc",$fields,$size,$page);




        $query = new \Elastica\Query();
        $query->setRawQuery($queryDSL);


        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'trade-usa-imports');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);


        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('member_importer_big_picture_search_us_import_page', array('page' => $page, 's_cache' => $this->s_cache_json));
        }, $options);         


        $fieldUsCustom = new FieldUsCustom();
        if (empty($s_cache->column))
            $fieldUsCustom->field_us_custom = array('actual_arrival_date', 'product_desc',
                'consignee_name', 'consignee_address', 'shipper_name', 'shipper_address',
                'notify_party_name','notify_party_address','quantity','quantity_unit','measure','measure_unit',
                'teu','hs_code','marks_and_numbers','last_vist_foreign_port','mode_of_transportation',
                'weight', 'weight_unit', 'container_id');
        else {
            $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
        }

       $member_search = $active_subscription;

        return array(
            'resHybird' => $res->getCurrentPageResults()->getResults(),
            'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'pagination' => $html,
            'column' => $fieldUsCustom->field_us_custom,
            'maxDownload' => $member_search->getDownloadLimit(),
            'urlChanges' => isset($urlChanges) ? $urlChanges : null,            
            'downloadValue' => $member_search->getDownloadValue(),
            'downloadLimit' => $member_search->getDownloadLimit(),
            'historyValue' => $historyValue,
            
            "total_row" => $res->getNbResults(),
            'took' => $res->getCurrentPageResults()->getResponse()->getData()["took"] / 1000
        );
    }

    /**
     * @Route(
     *   "/big-picture/importer/search/us-import-page/{slug}/{page}", 
     * name="member_importer_big_picture_search_us_import_page",
     *   options={"expose"=true})
     * @Template("JariffMemberBundle:BigPicture\USImporter\Add:results_ajax.html.twig")
     */
    public function importerPageBigPictureSearchAction($slug,$page=1)
    {
        
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

                $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || ($active_subscription->getActive() != 1)) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        
        $today = new \DateTime('now');
       

        $fields = array('consignee_name');

        $cache['search'][] =
                       (object) array(
                            'condition' => null,
                            'collect' => 'consignee_name',
                            'q' => $slug
                        );

                $cache['date_range'] = (object)array(
                                'from' => $todayMin,
                                'to' => $today->format("Y-m-d"),
                                'not_valid_date' => null,
                            );

        $queryDSL = $this->queryDSL((object)$cache,"actual_arrival_date","product_desc",$fields,$size,$page);




        $query = new \Elastica\Query();
        $query->setRawQuery($queryDSL);


        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'trade-usa-imports');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);


        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('member_search_shipments_result_us_imports_ajax', array('page' => $page, 's_cache' => $this->s_cache_json));
        }, $options);         


        $fieldUsCustom = new FieldUsCustom();
        if (empty($s_cache->column))
            $fieldUsCustom->field_us_custom = array('actual_arrival_date', 'product_desc',
                'consignee_name', 'consignee_address', 'shipper_name', 'shipper_address',
                'notify_party_name','notify_party_address','quantity','quantity_unit','measure','measure_unit',
                'teu','hs_code','marks_and_numbers','last_vist_foreign_port','mode_of_transportation',
                'weight', 'weight_unit', 'container_id');
        else {
            $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
        }

       $member_search = $active_subscription;

        return array(
            'resHybird' => $res->getCurrentPageResults()->getResults(),
            'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'pagination' => $html,
            'column' => $fieldUsCustom->field_us_custom,
            'maxDownload' => $member_search->getDownloadLimit(),
            'urlChanges' => isset($urlChanges) ? $urlChanges : null,            
            'downloadValue' => $member_search->getDownloadValue(),
            'downloadLimit' => $member_search->getDownloadLimit(),
            'historyValue' => $historyValue,
            
            "total_row" => $res->getNbResults(),
            'took' => $res->getCurrentPageResults()->getResponse()->getData()["took"] / 1000
        );
    }

}