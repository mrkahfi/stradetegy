<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\ProjectBundle\Controller\Search\Shipments;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Jariff\MemberBundle\Model\SearchEmbed;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\ProjectBundle\Util as Util;

use Jariff\MemberBundle\Model\SearchFieldCollect;
use Jariff\MemberBundle\Model\SearchFieldQ;
use Jariff\MemberBundle\Model\CustomData;
use Jariff\MemberBundle\Form\Demo\SearchShipmentsEmbedFormType;
use Jariff\MemberBundle\Form\Demo\Shipments\CustomDataFormType;

use Jariff\MemberBundle\Model\BillTypeCode;
use Jariff\MemberBundle\Model\FieldUsCustom;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldBillTypeCodeFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldUsImportFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\SaveSearchShipmentsFormType;

use Jariff\MemberBundle\Model\SearchFieldSize;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\DateRangeFormType;

use Jariff\MemberBundle\Model\DateRange;
use Jariff\MemberBundle\Model\CustomCountryData;
use Jariff\MemberBundle\Form\SearchGlobal\Field\FieldSizeFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomCountryDataFormType;

use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;

use Pagerfanta\View\TwitterBootstrap3View;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ElasticaAdapter;
use Elastica\Index;
use Elastica\Query as QueryEs;
use Elastica\Client as ClientEs;


/**
 *
 * @Route("/")
 */
class DemoShipmentsSearchController extends BaseController
{

    /**
     * @Route(
     *   "/shipments",
     *   name     = "demo_shipments_search"
     * )
     *
     * @Template("JariffProjectBundle:Search\Shipments:index.html.twig")
     */
    public function indexAction()
    {
        $entity = new SearchEmbed();
        $customData = new CustomData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);


        $form = $this->createForm(new SearchShipmentsEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
        ));

        return array(
            'form' => $form->createView(),
            'formCustom' => $formCustom->createView(),
        );
    }

    private $s_cache_json;

    /**
     * @Route(
     *   "/search-shipments-result/us-imports/{page}",
     *   name     = "demo_search_shipments_result_us_imports",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo\Shipments:results.html.twig")
     */
    public function resultAction($page = 1)
    {

        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        // if (!isset($s_cache->time)) {
        //     return $this->errorRedirect('Your search is expired, please submit again', 'member_search_shipments');
        // }


        // $active_subscription = $this->user()->getLastSubscription();
        // if (is_null($active_subscription) || ($active_subscription->getActive() != 1)) {
        //     return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        // }
        $today = new \DateTime('now');
        // $historyValue = $active_subscription->getHistoryValue();
        // $diff = abs(strtotime($today->format("Y-m-d")) - strtotime($s_cache->date_range->from));

        // $maxHistory = $diff / (3600 * 24 * 30);

        // if (floor($maxHistory) > $historyValue) {
        //     return $this->errorRedirect('You dont have any history more than ' . $historyValue . ' month. Please update your subscription.', 'member_search_shipments');
        // }

        if (preg_match('/all/i', $s_cache->search[0]->collect)) {
            $queryFirst = array(
                "query_string" => array(
                    'query' => $s_cache->search[0]->q
                )
            );
        } else {

            $queryFirst = array(
                "query_string" => array(
                    'query' => $s_cache->search[0]->q
                )
            );

            $filter['bool']['must'][] = array(
                "query" => array(
                    "query_string" => array(

                        "default_field" => $s_cache->search[0]->collect,
                        "query" => $s_cache->search[0]->q

                    )
                )
            );
        }


        $filterKeyword = $this->repo('JariffAdminBundle:FilterKeywords')->findAll();

        foreach ($filterKeyword as $key) {

            if (!isset($keywords))
                $keywords = $key->getKeyword();
            else
                $keywords .= ' or ' . $key->getKeyword();
        }


        $filter['bool']['must_not'][] = array(
            'query' => array(
                "query_string" => array(
                    'query' => $keywords
                )
            )
        );

        $i = 0;


        foreach ($s_cache->search as $s) {
            if ($i > 0) {
                if (preg_match('/all/i', $s->collect)) {
                    if (preg_match('/and/i', $s->condition)) {
                        $filter['bool']['must'][] = array(
                            'query' => array(
                                "query_string" => array(
                                    'query' => $s->q
                                )
                            )
                        );
                    }

                    if (preg_match('/or/i', $s->condition)) {
                        $filter['bool']['should'][] = array(
                            'query' => array(
                                "query_string" => array(
                                    'query' => $s[$i]->q
                                )
                            )
                        );
                    }

                    if (preg_match('/not/i', $s->condition)) {
                        $filter['bool']['must_not'][] = array(
                            'query' => array(
                                "query_string" => array(
                                    'query' => $s->q
                                )
                            )
                        );
                    }
                } else {


                    if (preg_match('/and/i', $s->condition)) {
                        $filter['bool']['must'][] = array(
                            "query" => array(
                                "query_string" => array(

                                    "default_field" => $s->collect,
                                    "query" => $s->q

                                )
                            )
                        );
                    }

                    if (preg_match('/or/i', $s->condition)) {
                        $filter['bool']['should'][] = array(
                            "query" => array(
                                "query_string" => array(

                                    "default_field" => $s->collect,
                                    "query" => $s->q

                                )
                            )
                        );
                    }

                    if (preg_match('/not/i', $s->condition)) {
                        $filter['bool']['must_not'][] = array(
                            "query" => array(
                                "query_string" => array(

                                    "default_field" => $s->collect,
                                    "query" => $s->q

                                )
                            )
                        );
                    }
                }
            }
            $i++;
        }

        if (isset($s_cache->country)) {
            $qCountry = $s_cache->country[0];
            $i = 0;
            foreach ($s_cache->country as $country) {
                if ($i > 0) {
                    $qCountry .= ' or ' . $country;
                }

                $i++;
            }


            $filter['bool']['must'][] = array(
                "query" => array(
                    "query_string" => array(

                        "default_field" => 'slug_country_ori_shipper',
                        "query" => $qCountry

                    )
                )
            );
        }

        if (isset($s_cache->bill_type_code)) {
            $filter['bool']['must'][] = array(
                "query" => array(
                    "query_string" => array(

                        "default_field" => 'bill_type_code',
                        "query" => $s_cache->bill_type_code

                    )
                )
            );
        }

        if (isset($s_cache->top_importer)) {
            $filter['bool']['must'][] = array(
                "query" => array(
                    "query_string" => array(

                        "default_field" => 'slug_consignee_name',
                        "query" => $s_cache->top_importer

                    )
                )
            );
        }

        if (isset($s_cache->top_exporter)) {
            $filter['bool']['must'][] = array(
                "query" => array(
                    "query_string" => array(

                        "default_field" => 'slug_shipper_name',
                        "query" => $s_cache->top_exporter

                    )
                )
            );
        }

        $filter['bool']['must'][] = array(
            "range" => array(
                "actual_arrival_date" => array(

                    "from" => $s_cache->date_range->from,
                    "to" => $s_cache->date_range->to

                )
            )
        );

        $highlight = array(
            "highlight" => array(
                "number_of_fragments" => 3,
                "fragment_size" => 150,
                "tag_schema" => "styled",
                "fields" => array(
                    "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "product_desc" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                )
            )
        );

        $query = new \Elastica\Query();

        $aggsBillTypeCode = new \Elastica\Aggregation\Terms('bill_type_code');
        $aggsDateHistogramViewMonth = new \Elastica\Aggregation\DateHistogram("date_histogram_view_month", "actual_arrival_date", "month");
        $aggsTotalWeight = new \Elastica\Aggregation\Sum("total_weight");
        $aggsShipperName = new \Elastica\Aggregation\Terms('identity_shipper_name');

        $aggsBillTypeCode->setField('bill_type_code');
        $aggsTotalWeight->setField("weight");
        $aggsShipperName->setField("identity_shipper_name");
        $aggsShipperName->setSize(5);
        $aggsShipperName->addAggregationTop('top_shipper', array("shipper_name"));

        $queryDSL = array_merge(array(
                "query" => array_merge(array(
                        "filtered" => array_merge(array(
                                "query" => $queryFirst
                            ), (!empty($filter)) ? array("filter" => $filter) : array(),
                            array(
                                "_cache" => true
                            )
                        )
                    )
                )
            ),
            $highlight,
            array('from' => (($page - 1) * $size))
        );


        $query->setRawQuery($queryDSL);

        $query->addAggregation($aggsBillTypeCode);
        $query->addAggregation($aggsDateHistogramViewMonth);
        $query->addAggregation($aggsTotalWeight);
        $query->addAggregation($aggsShipperName);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'stradetegy');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);


        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('member_search_shipments_result_us_imports_ajax', array('page' => $page, 's_cache' => $this->s_cache_json));
        }, $options);

        // $diff = abs(strtotime($today->format("Y-m-d H:i:s")) - $s_cache->time);


        // $maxTime = $diff / 3600;
        // $member_search = $active_subscription;

        // if ($this->get('session')->get('checkSubmit')) {

        //     $querycek = new QueryEs();
        //     $querycek->setRawQuery(array("query" => array("match" => array("user_id" => $this->getUser()->getId()))));
        //     $searchableCek = new Index($client, 'logst-' . $today->format("Ymd"));
        //     $adapterCek = new ElasticaAdapter($searchableCek, $querycek);
        //     $resCek = new Pagerfanta($adapterCek);

        //     if (($resCek->getNbResults() + 1) > $member_search->getSearchValue()) {
        //         return $this->errorRedirect('Your search limit', 'member_search_shipments');
        //     }

        //     $this->get('session')->remove('checkSubmit');
        //     $history = array(
        //         "total_row" => $res->getNbResults(),
        //         "date_search" => $today->format('Y-m-d'),
        //         "user_id" => $this->getUser()->getId(),
        //         "query" => $this->s_cache_json,
        //         "priority" => 0,
        //         "set_pin" => "unpin"
        //     );

        //     $doc[] = new \Elastica\Document('', $history, "shipments_log", 'logst-' . $today->format("Ymd"));

        //     $client->addDocuments($doc);
        //     $client->getIndex('logst-' . $today->format("Ymd"))->refresh();
        // }

//         if ($maxTime > 1) {
//             $querycek = new QueryEs();
//             $querycek->setRawQuery(array("query" => array("match" => array("user_id" => $this->getUser()->getId()))));
//             $searchableCek = new Index($client, 'logst-' . $today->format("Ymd"));
//             $adapterCek = new ElasticaAdapter($searchableCek, $querycek);
//             $resCek = new Pagerfanta($adapterCek);

//             if (($resCek->getNbResults() + 1) > $member_search->getSearchValue()) {
//                 return $this->errorRedirect('Your search limit', 'member_search_shipments');
//             }

//             $history = array(
//                 "total_row" => $res->getNbResults(),
//                 "date_search" => $today->format('Y-m-d'),
//                 "user_id" => $this->getUser()->getId(),
//                 "query" => $this->s_cache_json,
//                 "priority" => 0,
//                 "set_pin" => "unpin"
//             );

//             $doc[] = new \Elastica\Document('', $history, "shipments_log", 'logst-' . $today->format("Ymd"));

//             $client->addDocuments($doc);
//             $client->getIndex('logst-' . $today->format("Ymd"))->refresh();

// //            $searchHistory = new SearchHistory();
// //            $searchHistory->setUserId($this->getUser()->getId());
// //            $searchHistory->setQuery($this->s_cache_json);
// //            $searchHistory->setDateSearch($today->format('Y-m-d'));
// //            $searchHistory->setIsDownload(0);
// //            $searchHistory->setTimezone($this->get('session')->get('timezone'));
// //            $searchHistory->setTotalRow($res->getNbResults());
// //            $searchHistory->setSearchOn('shipments-search');
// //
// //            $this->dm()->persist($searchHistory);
// //            $this->dm()->flush();

//             $s_caches = json_decode($util->decrypted($this->getRequest()->get('s_cache')), 1);

//             $timeIdentity = strtotime($today->format('YmdHis'));
//             unset($s_caches['time']);
//             $this->s_cache_json = $util->encrypted(json_encode(array_merge($s_caches, array('time' => $timeIdentity, 'time_over' => 1))));

//             $urlChanges = $this->generateUrl('member_search_shipments_result_us_imports', array('page' => $page, 's_cache' => $this->s_cache_json));

//         }


        // if ($res->getNbResults() == 0) {

        //     $inbound = new Inbound();
        //     $inbound->setDateCreate(new \DateTime('now'));
        //     $inbound->setEmail($this->getUser()->getUsername());
        //     $inbound->setDescription($this->s_cache_json);
        //     $inbound->setMember($this->getUser());
        //     $inbound->setVisitedPage($this->generateUrl('member_search_shipments_result_us_imports', array('s_cache' => $this->s_cache_json)));

        //     $this->em()->persist($inbound);
        //     $this->em()->flush();
        // }

        $entity = new SearchEmbed();

        foreach ($s_cache->search as $s) {
            $search1 = new SearchFieldCollect();
            $search1->collect = $s->collect;
            $entity->getCollect()->add($search1);

            $searchQ = new SearchFieldQ();
            $searchQ->q = $s->q;
            $entity->getQ()->add($searchQ);

            if (!is_null($s->condition)) {
                $searchCondition = new SearchFieldCondition();
                $searchCondition->condition = $s->condition;
                $entity->getCondition()->add($searchCondition);
            }
        }

        $form = $this->createForm(new SearchShipmentsEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));


        $fieldTypeCode = new BillTypeCode();

        $formBillTypeCode = $this->createForm(new FieldBillTypeCodeFormType(), $fieldTypeCode, array(
            'action' => '',
            'method' => 'POST',
            'dataBillTypeCode' => $res->getCurrentPageResults()->getAggregations()['bill_type_code']['buckets']
        ));


        $sizeModel = new SearchFieldSize();
        $sizeModel->setSize($size);

        $formSize = $this->createForm(new FieldSizeFormType(), $sizeModel, array(
            'action' => '',
            'method' => 'POST',
        ));


        $customData = new CustomData();
        $customData->setType($s_cache->type->custom_data);
        $dateRange = new DateRange();
        $dateRange->setDateFrom($s_cache->date_range->from);
        $dateRange->setDateTo($s_cache->date_range->to);
        $dateRange->setNotValidDate($s_cache->date_range->not_valid_date);

        $customCountryData = new CustomCountryData();
        $customCountryData->setType($s_cache->type->custom_country);


        $fieldUsCustom = new FieldUsCustom();
        if (empty($s_cache->column))
            $fieldUsCustom->field_us_custom = array('actual_arrival_date', 'consignee_name', 'shipper_name',
                'quantity', 'weight', 'product_desc', 'marks_and_numbers', 'bill_of_lading');
        else {
            $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
        }

        $formFieldUsCustom = $this->createForm(new FieldUsImportFormType(), $fieldUsCustom, array(
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

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
        ));


        $charts[] = array('Month/Year', 'Total Shipments');
        foreach ($res->getCurrentPageResults()->getAggregations()['date_histogram_view_month']['buckets'] as $googleChart) {
            if ($googleChart['key'] > 0) {
                $charts[] = array(date("M Y", strtotime($googleChart['key_as_string'])), $googleChart['doc_count']);
            } else {
                $charts[] = array('undefined', $googleChart['doc_count']);
            }

        }

        // $historyValue = $member_search->getHistoryValue();

//        if(count($member_search_history) > $member_search->getSearchValue()){
//            return $this->errorRedirect('Your search limit','member_search_shipments');
//        }

        return array(
            'form' => $form->createView(),
            'formCustom' => $formCustom->createView(),
            'formFieldUsCustom' => $formFieldUsCustom->createView(),
            'formBillTypeCode' => $formBillTypeCode->createView(),
            'formSize' => $formSize->createView(),
            'resHybird' => $res->getCurrentPageResults()->getResults(),
            'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'pagination' => $html,
            'column' => $fieldUsCustom->field_us_custom,
            // 'maxDownload' => $member_search->getDownloadLimit(),
            'chart' => $charts,
            'urlChanges' => isset($urlChanges) ? $urlChanges : null,
            'formDateRange' => $formDateRange->createView(),
            'formCustomCountry' => $formCustomCountry->createView(),
            // 'historyValue' => $historyValue
        );
    }

}

