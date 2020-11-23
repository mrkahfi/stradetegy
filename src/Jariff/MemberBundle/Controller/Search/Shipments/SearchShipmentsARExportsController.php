<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\Search\Shipments;

use Jariff\MemberBundle\Entity\ContactCompany;
use Jariff\MemberBundle\Entity\MemberTodaySearch;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ExportsSearchShipmentsFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;

use Jariff\AdminBundle\Entity\Inbound;
use Jariff\MemberBundle\Model\BillTypeCode;
use Jariff\MemberBundle\Model\FieldUsCustom;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldBillTypeCodeFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldUsImportFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\SaveSearchShipmentsFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ARExports\FieldArExportFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ARExports\SearchShipmentsArExportEmbedFormType;

use Jariff\MemberBundle\Entity\SavedSearch;
use Jariff\MemberBundle\Entity\ExportTools;
use Jariff\MemberBundle\Entity\MemberActivityFeed;
use Jariff\MemberBundle\Model\SearchFieldCollect;
use Jariff\MemberBundle\Model\SearchFieldQ;
use Jariff\MemberBundle\Model\SearchFieldSize;
use Jariff\MemberBundle\Model\SearchFieldCondition;
use Jariff\MemberBundle\Form\SearchGlobal\Field\FieldSizeFormType;
use Jariff\MemberBundle\Model\CustomData;
use Jariff\MemberBundle\Form\SearchGlobal\SearchShipmentsEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomDataFormType;

use Jariff\MemberBundle\Model\DateRange;
use Jariff\MemberBundle\Model\CustomCountryData;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\DateRangeFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomCountryDataFormType;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\ProjectBundle\Util as Util;
use Jariff\MemberBundle\Model\SearchEmbed;
use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;
use Jariff\ProjectBundle\Twig\StringExtension;

use Pagerfanta\View\TwitterBootstrap3View;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ElasticaAdapter;
use Elastica\Index;
use Elastica\Query as QueryEs;
use Elastica\Client as ClientEs;

/**
 *
 * @Route("/member")
 */
class SearchShipmentsARExportsController extends BaseController
{

    private $s_cache_json;

    /**
     * @Route(
     *   "/search-shipments-result/ar-exports/{page}",
     *   name     = "member_search_shipments_result_ar_exports",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     *
     * @Template("JariffMemberBundle:Search\ShipmentsArExports:results.html.twig")
     */
    public function resultAction($page = 1)
    {

        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        if (!isset($s_cache->time)) {
            return $this->errorRedirect('Your search is expired, please submit again', 'member_search_shipments');
        }


        $active_subscription = $this->user()->getLastSubscription();

        if (is_null($active_subscription) || !$active_subscription->isActive()) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

        $today = new \DateTime('now');
        if ($this->get('session')->get('timezone')) {
            $tz = new \DateTimeZone($this->get('session')->get('timezone'));
            $today = new \DateTime('now', $tz);
        }

        $expired = $active_subscription->getDateExpired();
        if (!empty($expired)) {
            if ($active_subscription->getDateExpired()->format("Y-m-d H:i:s") < $today->format("Y-m-d H:i:s")) {
                return $this->redirect($this->generateUrl('member_expired_subscription'));
            }
        }

        $historyValue = $active_subscription->getHistoryValue();
        $diff = abs(strtotime($today->format("Y-m-d")) - strtotime($s_cache->date_range->from));

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

        $form = $this->createForm(new SearchShipmentsArExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $customData = new CustomData();
        $customData->setType($s_cache->type->custom_data);
        $dateRange = new DateRange();
        $dateRange->setDateFrom($s_cache->date_range->from);
        $dateRange->setDateTo($s_cache->date_range->to);
        $dateRange->setNotValidDate($s_cache->date_range->not_valid_date);
        $dateRange->setMarkedMaster($s_cache->marked_master);

        $customCountryData = new CustomCountryData();
        $customCountryData->setType($s_cache->type->custom_country);


        $fieldUsCustom = new FieldUsCustom();
        if (empty($s_cache->column))
            $fieldUsCustom->field_us_custom = array("date",
                "export_id",
                "operation_type",
                "custom",
                "country_destination",
                "type_of_transport",
                "incoterms",
                "total_fob",
                "insurance_us",
                "total_cif",
                "number_of_packages",
                "gross_weight",
                "weight_unit",
                "item_number",
                "commercial_quantity",
                "commercial_unit",
                "fob_item",
                "hs_code",
                "product",
                "cif_item",
                "subitem_number",
                "brand",
                "variety",
                "attributes",
                "us_fob_subitem",
                "quantity_subitem");
        else {
            $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
        }

        $formFieldUsCustom = $this->createForm(new FieldArExportFormType(), $fieldUsCustom, array(
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

        $member_search = $active_subscription;
        $historyValue = $member_search->getHistoryValue();

        if (!$active_subscription->getEverythingPlan()) {
            $cekSearch = $this->repo("JariffMemberBundle:MemberTodaySearch")->findOneBy(array(
                'member' => $this->getUser()->getId(),
                'date_search' => $today
                ));

            if (!empty($cekSearch)) {
                if (($cekSearch->getTotal() + 1) > $active_subscription->getSearchValue()) {
                    return $this->errorRedirect('Sorry, Total number of searches for Total Package today is limited', 'member_search_shipments');
                }
            }

            $maxHistory = $diff / (3600 * 24 * 30);

            if (floor($maxHistory) > $historyValue) {
                return $this->errorRedirect('You dont have any history more than ' . $historyValue . ' month. Please update your subscription.', 'member_search_shipments');
            }
        }

        return array(
            'form' => $form->createView(),
            'formCustom' => $formCustom->createView(),
            'formFieldUsCustom' => $formFieldUsCustom->createView(),
            'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'column' => $fieldUsCustom->field_us_custom,
            'maxDownload' => $member_search->getDownloadLimit(),
            'urlChanges' => isset($urlChanges) ? $urlChanges : null,
            'formDateRange' => $formDateRange->createView(),
            'formCustomCountry' => $formCustomCountry->createView(),
            'downloadValue' => $active_subscription->getDownloadValue(),
            'downloadLimit' => $active_subscription->getDownloadLimit(),
            'historyValue' => $historyValue,
            'query_search' => $this->s_cache_json,
            'columnName' => array_merge(array('number','detail_company'),$fieldUsCustom->field_us_custom)
            ,
            'columnNameFilter' => array_merge(array('number'),$fieldUsCustom->field_us_custom)
            );
    }

    /**
     * @Route(
     *   "/search-shipments-result-ajax/ar-exports/{page}",
     *   name     = "member_search_shipments_result_ar_exports_ajax",
     *  options={"expose"=true}
     * )
     *
     * @Template("JariffMemberBundle:Search\ShipmentsArExports\Add:results_ajax.html.twig")
     */
    public function resultAjaxAction($page = 1)
    {

        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        if (!isset($s_cache->time)) {
            return $this->errorRedirect('Your search is expired, please submit again', 'member_search_global');
        }

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || !$active_subscription->isActive()) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

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
                                    'query' => $s->q
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



        $filter['bool']['must'][] = array(
            "range" => array(
                "date" => array(

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
                    "product" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    )
                )
            );

        $query = new \Elastica\Query();


        $aggsDateHistogramViewMonth = new \Elastica\Aggregation\DateHistogram("date_histogram_view_month", "date", "month");
        $aggsTotalWeight = new \Elastica\Aggregation\Sum("gross_weight");


        $aggsTotalWeight->setField("weight");

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


        $query->addAggregation($aggsDateHistogramViewMonth);
        $query->addAggregation($aggsTotalWeight);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'stradetegy_ar_exports');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);

        $today = new \DateTime('now');

        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('member_search_shipments_result_ar_exports_ajax', array('page' => $page, 's_cache' => $this->s_cache_json));
        }, $options);

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


        $sizeModel = new SearchFieldSize();
        $sizeModel->setSize($size);

        $formSize = $this->createForm(new FieldSizeFormType(), $sizeModel, array(
            'action' => '',
            'method' => 'POST',
            ));

        $member_search = $active_subscription;

        $customData = new CustomData();
        $customData->setType($s_cache->type->custom_data);
        $dateRange = new DateRange();
        $dateRange->setDateFrom($s_cache->date_range->from);
        $dateRange->setDateTo($s_cache->date_range->to);

        $customCountryData = new CustomCountryData();
        $customCountryData->setType($s_cache->type->custom_country);

        $fieldUsCustom = new FieldUsCustom();
        if (empty($s_cache->column))
            $fieldUsCustom->field_us_custom = array('date', 'export_id', 'country_destination',
                'type_of_transport', 'gross_weight','brand','variety', 'product', 'hs_code', 'fob_item');
        else {
            $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
        }

        $formFieldUsCustom = $this->createForm(new FieldArExportFormType(), $fieldUsCustom, array(
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

        $historyValue = $member_search->getHistoryValue();

        $params = array(
            'form' => $form->createView(),
            'formCustom' => $formCustom->createView(),
            'formFieldUsCustom' => $formFieldUsCustom->createView(),
            'formSize' => $formSize->createView(),
            'res' => $res,
            'resHybird' => $res->getCurrentPageResults()->getResults(),
            'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'pagination' => $html,
            'column' => $fieldUsCustom->field_us_custom,
            'chart' => json_encode($charts),
            'urlChanges' => isset($urlChanges) ? $urlChanges : null,
            'formDateRange' => $formDateRange->createView(),
            'formCustomCountry' => $formCustomCountry->createView(),
            'historyValue' => $historyValue
            );

        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\ShipmentsArExports\Add:results_ajax.html.twig', $params),
                    's_cache' => $this->s_cache_json,
                    'url_changes' => $this->generateUrl('member_search_shipments_result_ar_exports', array('page' => $page, 's_cache' => $this->s_cache_json))
                    )
                )
            );
    }

    /**
     * @Route(
     *   "/search-shipments-columns-submit-ar-exports",
     *   name     = "member_search_columns_category_submit_ar_exports"
     * )
     * @Method("POST")
     *
     */
    public function submitCategoryAction(Request $request)
    {
        $entity = new FieldUsCustom();

        $form = $this->createForm(new FieldArExportFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $form->handleRequest($request);

        $util = new StringTwig();
        $s_cache = $this->getRequest()->get('s_cache');


        if ($form->isValid()) {

            $catArray = array();
            foreach ($form->getData()->getFieldUsCustom() as $category) {
                $catArray[] = $category;
            }

            $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

            if (isset($s_cache->category))
                unset($s_cache->category);


            $s_cache = array_merge((array)$s_cache, array('column' => $catArray));
            $jsonS_cache = json_encode($s_cache);

            $s_cache = $util->encrypted($jsonS_cache);
        }


        return $this->redirectUrl('member_search_shipments_result_ar_exports', array('s_cache' => $s_cache));

    }

    /**
     * @Route(
     *   "/search-shipments-columns-show-ar-exports",
     *   name     = "member_search_columns_category_columns_show_ar_exports"
     * )
     *
     *
     */
    public function showChoiceColoumnAction(Request $request)
    {

        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($request->get('s_cache')));

        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        $fieldUsCustom = new FieldUsCustom();
        if (empty($s_cache->column))
            $fieldUsCustom->field_us_custom = array('date', 'export_id', 'country_destination',
                'type_of_transport', 'gross_weight','brand','variety', 'product', 'hs_code', 'fob_item');
        else {
            $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
        }

        $formFieldUsCustom = $this->createForm(new FieldArExportFormType(), $fieldUsCustom, array(
            'action' => '',
            'method' => 'POST',
            ));

        $params = array(
            'formFieldUsCustom' => $formFieldUsCustom->createView(),
            's_cache' => $this->s_cache_json
            );

        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\ShipmentsArExports\Add\Form:field_column.html.twig', $params),

                    )
                )
            );


    }

    /**
     * @Route(
     *   "/company-display-one-shipments-ar-exports/{index}/{type}/{objId}",
     *   name     = "company_display_one_shipments_ar_exports",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function displayOneShipmentsAction($index, $type, $objId)
    {

        $client = new \Elasticsearch\Client(array("hosts" => array("52.27.146.43:9200")));


        $params['index'] = $index;
        $params['type'] = $type;
        $params['id'] = $objId;
        $ret = $client->getSource($params);


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\ShipmentsArExports\Add:modal_show_one_shipments.html.twig', array('entity' => $ret,'params' => $params)),

                    )
                )
            );
    }

    /**
     * @Route(
     *   "/render-company-display-one-shipments-ar-exports/{index}/{type}/{objId}",
     *   name     = "company_render_display_one_shipments_ar_exports",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function renderDisplayOneShipmentsAction($index, $type, $objId)
    {

        $client = new \Elasticsearch\Client(array("hosts" => array("52.27.146.43:9200")));


        $params['index'] = $index;
        $params['type'] = $type;
        $params['id'] = $objId;
        $ret = $client->getSource($params);

        $html = $this->renderView('JariffMemberBundle:Search\ShipmentsArExports\Add:modal_show_one_shipments.html.twig', array(
            'entity' => $ret
            ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
                )
            );

//        return new Response(
//            json_encode(
//                array(
//                    'success' => true,
//                    'html_string' => $this->renderView('JariffMemberBundle:Search\ShipmentsArExports\Add:modal_show_one_shipments.html.twig', array('entity' => $ret)),
//
//                )
//            )
//        );
    }


    /**
     * @Route(
     *   "/member/shipments-search-not-valid-date-exports/{page}",
     *   name     = "member_search_result_not_valid_date_exports"
     * )
     *
     *
     */
    public function notValidDateAction($page = 1)
    {
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        if (!isset($s_cache->time)) {
            return $this->errorRedirect('Your search is expired, please submit again', 'member_search_shipments');
        }


        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || !$active_subscription->isActive()) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }
        $today = new \DateTime('now');
        $historyValue = $active_subscription->getHistoryValue();
        $diff = abs(strtotime($today->format("Y-m-d")) - strtotime($s_cache->date_range->from));

        $maxHistory = $diff / (3600 * 24 * 30);

        if (floor($maxHistory) > $historyValue) {
            return $this->errorRedirect('You dont have any history more than ' . $historyValue . ' month. Please update your subscription.', 'member_search_shipments');
        }

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
                                    'query' => $s->q
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

        $highlight = array(
            "highlight" => array(
                "number_of_fragments" => 3,
                "fragment_size" => 150,
                "tag_schema" => "styled",
                "fields" => array(
                    "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "product" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    )
                )
            );

        $query = new \Elastica\Query();

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

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'stradetegy-ex-ar-unvalid-date');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);


        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('member_search_result_not_valid_date_exports', array('page' => $page, 's_cache' => $this->s_cache_json));
        }, $options);

        $fieldUsCustom = new FieldUsCustom();
        if (empty($s_cache->column))
            $fieldUsCustom->field_us_custom = array('date', 'export_id', 'country_destination',
                'type_of_transport', 'gross_weight','brand','variety', 'product', 'hs_code', 'fob_item');
        else {
            $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
        }

        $params = array(
            'pagination' => $html,
            'resHybird' => $res->getCurrentPageResults()->getResults(),
            'column' => $fieldUsCustom->field_us_custom,
            );

        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\ShipmentsArExports\Add:not_valid_date.html.twig', $params),
                    's_cache' => ''
                    )
                )
            );

    }

    /**
     * @Route(
     *   "/search-shipments-result-ajax/ar-exports/jsonp",
     *   name     = "member_search_shipments_result_ar_exports_ajax_jsonp",
     *  options={"expose"=true}
     * )
     *
     */
    public function resultAjaxJsonpAction(Request $request)
    {

     // if (strtolower(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) !== 'xmlhttprequest') {
     //       // I'm AJAX!
     //     die('Access denied');
     // }

        $active_subscription = $this->user()->getLastSubscription();

        if (is_null($active_subscription) || !$active_subscription->isActive()) 
        {
         $jsonp = array(
            "errors" => true,
            'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:error_on_search.html.twig', 
                array( "title" => '<span class="icon-warning-info"></span> Your subscription',
                    "message" => "You dont have any active subscription. Please update your subscription.",
                    "button" => array(
                        '<a href="'.$this->generateUrl("support_ticket_inbound_form").'" class="btn btn-info"><span class="icon-user"></span> Customer Service</a>'
                        )
                    )
                ),

            );


         return new Response(
            json_encode(
                $jsonp
                )
            );
         return $this->errorRedirect('', 'dashboard_member');
     }

     $util = new StringTwig();
     $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
     $size = 15;

     $page = $request->get('page');
     $rows = $request->get('rp');



     if(empty($page)) $page = 1;

     if(isset($rows)) $size = $rows;

     $this->s_cache_json = $this->getRequest()->get('s_cache');        

     $today = new \DateTime('now');
     if ($this->get('session')->get('timezone')) 
     {
        $tz = new \DateTimeZone($this->get('session')->get('timezone'));
        $today = new \DateTime('now', $tz);
    }
    
    $dateSubscriptions = $active_subscription->getDateUpdate();
    $dateSubscriptions->modify("+ " . $active_subscription->getMonthValue() . " month");

    $diff = abs(strtotime($today->format("Y-m-d H:i:s")) - $s_cache->time);
    $maxTime = $diff / 3600;        

    if (!isset($s_cache->time)) {
        $jsonp = array(
            "errors" => true,
            'message' => "Your URL is denied, please submit again",
            "title" => "Error search",
            'buttons' => array(
                array(
                    'label' => 'New Search',
                    'action' => $this->generateUrl("member_search_shipments")
                    ),
                array(
                    'cssClass' => 'btn btn-info',
                    'icon' => 'icon-user',
                    'label' => 'Customer Service',
                    'action' => $this->generateUrl("support_ticket_inbound_form")
                    )
                )

            );


        return new Response(
            json_encode(
                $jsonp
                )
            );     
    }


    $fields = array('product',
        "operation_type",
        "custom",
        "country_destination",
        "type_of_transport",
        "incoterms",
        "commercial_unit",
        "brand",
        "variety",
        "attributes");


    $queryDSL = $this->queryDSL($s_cache, "date", "product", $fields, $size, $page);


    $query = new \Elastica\Query();
    $query->setRawQuery($queryDSL);

    $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
    $searchable = new Index($client, 'trade-ar-exports-*');

    $adapter = new ElasticaAdapter($searchable, $query);
    $res = new Pagerfanta($adapter);

    $res->setMaxPerPage($size);
    $res->setCurrentPage($page);


    $entity = new SearchEmbed();

    if ($maxTime > 1) {
       $jsonp = array(
        "errors" => true,
        'message' => "Your URL is expired, please submit again",
        "title" => "Error search",
        'buttons' => array(
            array(
                'label' => 'New Search',
                'action' => $this->generateUrl("member_search_shipments")
                ),
            array(
                'cssClass' => 'btn btn-info',
                'icon' => 'icon-user',
                'label' => 'Customer Service',
                'action' => $this->generateUrl("support_ticket_inbound_form")
                )
            )

        );


       return new Response(
        json_encode(
            $jsonp
            )
        );  
   }


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

$member_search = $active_subscription;

$fieldUsCustom = new FieldUsCustom();
if (empty($s_cache->column))
    $fieldUsCustom->field_us_custom = array(
        "date",
        "export_id",
        "operation_type",
        "custom",
        "country_destination",
        "type_of_transport",
        "incoterms",
        "total_fob",
        "total_cif",
        "number_of_packages",
        "gross_weight",
        "weight_unit",
        "item_number",
        "commercial_quantity",
        "commercial_unit",
        "fob_item",
        "hs_code",
        "product",
        "cif_item",
        "subitem_number",
        "brand",
        "variety",
        "attributes",
        "us_fob_subitem",
        "quantity_subitem");
else {
    $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
}

$columnMd = array();
$allRes = array();
$util2 = new StringExtension();
$util->setEm($this->em());

if ($res->getNbResults() == 0) {

    $inbound = new Inbound();
    $inbound->setDateCreate(new \DateTime('now'));
    $inbound->setEmail($this->getUser()->getUsername());
    $inbound->setDescription($this->s_cache_json);
    $inbound->setMember($this->getUser());
    $inbound->setVisitedPage($this->generateUrl('member_search_shipments_result_us_exports', array('s_cache' => $this->s_cache_json)));

    $this->em()->persist($inbound);
    $this->em()->flush();

    $jsonp = array(
        "errors" => true,
        'message' => "Sorry, we could not find data the keyword you enter",
        "title" => "Empty Results",
        'buttons' => array(
            array(
                'label' => 'New Search',
                'action' => $this->generateUrl("member_search_shipments")
                ),
            array(
                'cssClass' => 'btn btn-info',
                'icon' => 'icon-user',
                'label' => 'Customer Service',
                'action' => $this->generateUrl("support_ticket_inbound_form")
                )
            )

        );


    return new Response(
        json_encode(
            $jsonp
            )
        );
}

if ($this->get('session')->get('checkSubmit')) {

    $cek = $this->repo("JariffMemberBundle:MemberTodaySearch")->findOneBy(array(
        'member' => $this->getUser()->getId(),
        'date_search' => $today
        ));

    if ($active_subscription->getEverythingPlan()) {
        $active_subscription->setTotalSearches($active_subscription->getTotalSearches() + 1);
        $this->em()->persist($active_subscription);
        $this->em()->flush();
    } else {

        if (!empty($cek)) {
            if (($cek->getTotal() + 1) > $active_subscription->getSearchValue()) {
                $jsonp = array(
                    "errors" => true,
                    'message' => "Sorry, Total number of searches for Total Package today is limited",
                    "title" => "Search is Limited",
                    'buttons' => array(
                        array(
                            'cssClass' => 'btn btn-info',
                            'icon' => 'icon-user',
                            'label' => 'Customer Service',
                            'action' => $this->generateUrl("support_ticket_inbound_form")
                            )
                        )

                    );


                return new Response(
                    json_encode(
                        $jsonp
                        )
                    );



                    // return $this->errorRedirect('Sorry, Total number of searches for Total Package today is limited', 'member_search_shipments');
            }
        }

        if (empty($cek)) {
            $newIsert = new MemberTodaySearch();
            $newIsert->setDateSearch($today);
            $newIsert->setMember($this->getUser());


            $newIsert->setTotal($newIsert->getTotal() + 1);
            $this->em()->persist($newIsert);
            $this->em()->flush();
        } else {
            $cek->setTotal($cek->getTotal() + 1);
            $this->em()->persist($cek);
            $this->em()->flush();
        }
    }

    $history = array(
        "total_row" => $res->getNbResults(),
        "date_search" => $today->format("Y-m-d"),
        "time_search" => $today->format("H:i:s"),
        "timezone" => $today->format("T"),
        "user_id" => $this->getUser()->getId(),
        "query" => $this->s_cache_json,
        "priority" => 0,
        "set_pin" => "unpin"
        );

    $doc[] = new \Elastica\Document('', $history, "user_logs", 'trade-user-logs-' . $today->format("Y"));

    $client->addDocuments($doc);
    $client->getIndex('trade-user-logs-' . $today->format("Y"))->refresh();
    $this->get('session')->remove('checkSubmit');

}

$urlChanges = null;
$entity = $this->repo('JariffMemberBundle:MemberSetting')->findOneByMember($this->getUser());

if ($entity) {
    $weightOri = $entity->getWeight();
} else {
    $weightOri = 'KG';
}

$inumber = ($page == 1) ? 1 : (($page - 1) * $rows) + 1;
foreach ($res->getCurrentPageResults()->getResults() as $r) {
    $columnMd['number'] = $inumber++;
    $columnMd['detail_company'] = ' <a href="#"
    data-whatever="'.$r->getHit()["_source"]['export_id'].'"
    class="j-modal-company-one-shipments"
    data-toggle="modal" data-target="#myModal" data-backdrop="static"
    data-url="' . $this->generateUrl('company_display_one_shipments_ar_exports', array(
        'index' => $r->getHit()["_index"], 'type' => $r->getHit()["_type"], 'objId' => $r->getHit()["_id"]
        )) . '"><span class="icon-search3"></span></a>';

    foreach ($fieldUsCustom->field_us_custom as $col) {

        if ($col == "product") {
            
         $columnMd[$col] = preg_replace("/<br\W*?\/>/",", ",$r->getHit()["_source"][$col]);
         
     } elseif ($col == "gross_weight") {
        $columnMd[$col] = $util->setting_gross_weight($r->getHit()["_source"][$col], 'KG', $this->getUser());
    } elseif ($col == "marks_and_numbers") {
        $columnMd[$col] = $util2->readmore($r->getHit()["_source"][$col]);
    } elseif ($col == "weight_unit") {
        $columnMd[$col] = $weightOri;
    } elseif ($col == "mode_of_transportation") {
        $columnMd[$col] = $util->us_column_code_transportation($r->getHit()["_source"][$col]);
    } else {
        $columnMd[$col] = $r->getHit()["_source"][$col];
    }

    if(isset($r->getHit()["highlight"][$col][0])){
        $match = $r->getHit()["highlight"];

        $columnMd[$col] = preg_replace("/<br\W*?\/>/",", ",$match[$col][0]);                
    }

    $columnMd[$col] = $util->na($columnMd[$col]);
}


$allRes[] = $columnMd;
}


$total = $res->getNbResults() / $size;

$keywords = '<div class="alert alert-info"> Your search of Data for :';

foreach ($s_cache->search  as $key => $value) {
    if(isset($value->condition)){
        $keywords .= strtoupper($value->condition);
    }

    $keywords .= $util->globalfield($value->collect,$s_cache->type->custom_country.'_'.$s_cache->type->custom_data);

    $keywords .= ' <a href="javascript:void(0)" class="j-popover-q label label-danger"
    data-container="body" data-toggle="popover" data-trigger="focus"
    data-placement="bottom">'.$value->q.'</a>';
}
$took = $res->getCurrentPageResults()->getResponse()->getData()["took"] / 1000;
$keywords .= 'generated '.$util->ribuan($res->getNbResults()).' results in '.$took.'
seconds.
</div>';

$jsonp = array(
    "url_change" => $urlChanges,
    'took' => $res->getCurrentPageResults()->getResponse()->getData()["took"] / 1000,
    "records_ribuan" => $util->ribuan($res->getNbResults()),
    "records" => $res->getNbResults(),
    "page" => $page,
    "total" =>$res->getNbResults(),
    "rows" => $allRes,
    "keywords" => $keywords
    );


return new Response(
    json_encode(
        $jsonp
        )
    );
}

}

