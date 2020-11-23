<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\Search\Shipments;

use Jariff\DocumentBundle\Document\KeywordHistory;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldShipperNameFormType;
use Jariff\MemberBundle\Model\ShipperName;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;
use Jariff\MemberBundle\Model\FieldUsCustom;

use Jariff\MemberBundle\Model\Country;
use Jariff\ProjectBundle\Util as Util;
use Jariff\MemberBundle\Form\Widget\Field\CountryFormType;

use Jariff\MemberBundle\Model\BillTypeCode;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldBillTypeCodeFormType;


use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldConsigneeNameFormType;
use Jariff\MemberBundle\Model\ConsigneeName;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ElasticaAdapter;
use Elastica\Index;
use Elastica\Query;
use Pagerfanta\View\TwitterBootstrap3View;


/**
 * @Route("/")
 */
class AjaxShowFilterController extends BaseController
{
    private $s_cache_json;

    /**
     * @Route(
     *   "/member/search-shipments-advance-filter/{index}/{string_date}/{country_type}",
     *   name     = "member_search_shipments_advance_filter"
     * )
     *
     *
     */
    public function indexAction($index,$string_date,$country_type)
    {
        $s_cache = $this->getRequest()->get('s_cache');

        $fields = array('consignee_name',
            'consignee_address',
            'shipper_name',
            'shipper_address',
            'product_desc',
            'notify_party_names',
            'notify_party_address',
            'bill_of_lading',
            'master_bill_of_lading',
            'container_id',
            'place_of_receipt',
            'vessel_name',
            'carrier_sasc_code');


        $queryDSL = $this->queryDSL2($s_cache,$string_date,"product_desc",$fields);

        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($s_cache));

        $query = new \Elastica\Query();

        $aggsBillTypeCode = new \Elastica\Aggregation\Terms('bill_type_code');
        $aggsShipperName = new \Elastica\Aggregation\Terms('identity_shipper_name');
        $aggsConsigneeName = new \Elastica\Aggregation\Terms('identity_consignee_name');
        $countryShipperAggs = new \Elastica\Aggregation\Terms('country_shipper');

        $aggsBillTypeCode->setField('bill_type_code');

        $aggsShipperName->setField("slug_shipper_name");
        $aggsShipperName->setSize(5);
        $aggsShipperName->addAggregationTop('top_shipper', array("shipper_name",'slug_shipper_name'));

        $aggsConsigneeName->setField("slug_consignee_name");
        $aggsConsigneeName->setSize(5);
        $aggsConsigneeName->addAggregationTop('top_consignee', array("consignee_name",'slug_consignee_name'));

        $countryShipperAggs->setField('slug_country_ori_shipper');
        $countryShipperAggs->setSize(5);



        $query->setRawQuery($queryDSL);
        $query->addAggregation($aggsBillTypeCode);
        $query->addAggregation($aggsShipperName);
        $query->addAggregation($countryShipperAggs);
        $query->addAggregation($aggsConsigneeName);


        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, $index);

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);



        $fieldTypeCode = new BillTypeCode();

        if (isset($s_cache->bill_type_code)) {
            $btc = preg_split('/\or/', $s_cache->bill_type_code);
            $fieldTypeCode->setBillTypeCode($btc);
        }


        $formBillTypeCode = $this->createForm(new FieldBillTypeCodeFormType(), $fieldTypeCode, array(
            'action' => '',
            'method' => 'POST',
            'dataBillTypeCode' => $res->getCurrentPageResults()->getAggregations()['bill_type_code']['buckets']
        ));

        $countryForms = array();
        if (isset($s_cache->country)) {
            foreach ($s_cache->country as $country) {
                $countryForms[] = $country;
            }
        }

        $country = new Country();
        $country->country = $countryForms;


        $formCountry = $this->createForm(new CountryFormType(), $country, array(
            'action' => '',
            'method' => 'POST',
            'dataCountry' => $res->getCurrentPageResults()->getAggregations()['country_shipper']['buckets']


        ));

        $consigneeName = new ConsigneeName();
        if (isset($s_cache->top_importer)) {
            $btc = preg_split('/\or/', $s_cache->top_importer);
            $consigneeName->setConsigneeName($btc);
        }


        $formConsigneeName = $this->createForm(new FieldConsigneeNameFormType(), $consigneeName, array(
            'action' => '',
            'method' => 'POST',
            'dataConsigneeName' => $res->getCurrentPageResults()->getAggregations()['identity_consignee_name']['buckets']


        ));

        $shipperName = new ShipperName();
        if (isset($s_cache->top_exporter)) {
            $btc = preg_split('/\or/', $s_cache->top_exporter);
            $shipperName->setShipperName($btc);
        }



        $formShipperName = $this->createForm(new FieldShipperNameFormType(), $shipperName, array(
            'action' => '',
            'method' => 'POST',
            'dataShipperName' => $res->getCurrentPageResults()->getAggregations()['identity_shipper_name']['buckets']


        ));


        $params = array(
            'formBillTypeCode' => $formBillTypeCode->createView(),
            'formCountry' => $formCountry->createView(),
            'formTopConsigneeName' => $formConsigneeName->createView(),
            'formTopShipperName' => $formShipperName->createView(),
            'country_type' => $country_type
        );


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Filter:form_bill_type_code.html.twig', $params),
                    's_cache' => ''
                )
            )
        );

    }

    /**
     * @Route(
     *   "/member/filter-bill-type-code-submit/{country_type}",
     *   name     = "member_filter_bill_type_code_submit"
     * )
     * @Method("POST")
     *
     */
    public function submitBillTypeCodeAction(Request $request,$country_type)
    {

        $data = isset($request->get('field_bill_type_code')['bill_type_code']) ? $request->get('field_bill_type_code')['bill_type_code'] : null;

        $util = new StringTwig();
        $s_cache = $request->get('s_cache');
        $cache = json_decode($util->decrypted($s_cache), 1);


        if (!empty($data)) {

            $billTypeCode = $data[0];
            $i = 0;
            foreach ($data as $row) {
                if ($i > 0) {
                    $billTypeCode .= ' or ' . $row;
                }

                $i++;
            }

            if (isset($cache['bill_type_code'])) {
                unset($cache['bill_type_code']);
            }

            $s_cache = $util->encrypted(json_encode(array_merge($cache, array('bill_type_code' => $billTypeCode))));

        } else {
            if (isset($cache['bill_type_code'])) {
                unset($cache['bill_type_code']);
            }

            $s_cache = $util->encrypted(json_encode($cache));

        }

        return new Response(json_encode(
                array(
                    'success' => true,
                    'urls' => $this->generateUrl('member_search_shipments_result_'.$country_type, array('s_cache' => $s_cache))
                )
            )
        );


    }

    /**
     * @Route(
     *   "/member/country-submit/{country_type}",
     *   name     = "member_country_submit"
     * )
     * @Method("POST")
     *
     */
    public function submitCountryAction(Request $request,$country_type)
    {
        $data = $request->get('country_widget');
        $util = new StringTwig();

        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));


        $qCountrys = array();
        if (isset($data['country'])) {

            foreach ($data['country'] as $country) {
                $qCountrys[] = $country;
            }
        }

        $s_cache = (array)$s_cache;
        if (isset($s_cache['country']))
            unset($s_cache['country']);

        $s_cache = array_merge(empty($s_cache) ? array() : $s_cache,
            !empty($qCountrys) ? array('country' => $qCountrys) : array());

        $s_cacheJson = json_encode($s_cache);

        $s_cache = $util->encrypted($s_cacheJson);

        return new Response(json_encode(
                array(
                    'success' => true,
                    'urls' => $this->generateUrl('member_search_shipments_result_'.$country_type, array('s_cache' => $s_cache))
                )
            )
        );

    }

    /**
     * @Route(
     *   "/member/top-importer-submit/{country_type}",
     *   name     = "member_top_importer_submit"
     * )
     * @Method("POST")
     *
     */
    public function submitTopImporterAction(Request $request,$country_type)
    {

        $data = isset($request->get('field_consignee_name')['consignee_name']) ? $request->get('field_consignee_name')['consignee_name'] : null;


        $util = new StringTwig();
        $s_cache = $request->get('s_cache');
        $cache = json_decode($util->decrypted($s_cache), 1);


        if (!empty($data)) {

            $topImporter = $data[0];
            $i = 0;
            foreach ($data as $row) {
                if ($i > 0) {
                    $topImporter .= ' or ' . $row;
                }

                $i++;
            }

            if (isset($cache['top_importer'])) {
                unset($cache['top_importer']);
            }

            $s_cache = $util->encrypted(json_encode(array_merge($cache, array('top_importer' => $topImporter))));

        } else {
            if (isset($cache['top_importer'])) {
                unset($cache['top_importer']);
            }

            $s_cache = $util->encrypted(json_encode(array_merge($cache)));

        }

        return new Response(json_encode(
                array(
                    'success' => true,
                    'urls' => $this->generateUrl('member_search_shipments_result_'.$country_type, array('s_cache' => $s_cache))
                )
            )
        );

    }

    /**
     * @Route(
     *   "/member/top-exporter-submit/{country_type}",
     *   name     = "member_top_exporter_submit"
     * )
     * @Method("POST")
     *
     */
    public function submitTopExporterAction(Request $request,$country_type)
    {

        $data = isset($request->get('field_shipper_name')['shipper_name']) ? $request->get('field_shipper_name')['shipper_name'] : null;

        $util = new StringTwig();
        $s_cache = $request->get('s_cache');
        $cache = json_decode($util->decrypted($s_cache), 1);


        if (!empty($data)) {

            $topExporter = $data[0];
            $i = 0;
            foreach ($data as $row) {
                if ($i > 0) {
                    $topExporter .= ' or ' . $row;
                }

                $i++;
            }

            if (isset($cache['top_exporter'])) {
                unset($cache['top_exporter']);
            }

            $s_cache = $util->encrypted(json_encode(array_merge($cache, array('top_exporter' => $topExporter))));

        } else {
            if (isset($cache['top_exporter'])) {
                unset($cache['top_exporter']);
            }

            $s_cache = $util->encrypted(json_encode($cache));

        }

        return new Response(json_encode(
                array(
                    'success' => true,
                    'urls' => $this->generateUrl('member_search_shipments_result_'.$country_type, array('s_cache' => $s_cache))
                )
            )
        );

    }

    /**
     * @Route(
     *   "/member/chart-shipments/{index}/{string_date}",
     *   name     = "member_chart_shipments"
     * )
     *
     *
     */
    public function chartShipmentsAction($index,$string_date)
    {
        $s_cache = $this->getRequest()->get('s_cache');
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

//        $toPacket = 'fos_elastica.finder.stradetegy_us_imports.usa_importers';
//
//        $finder = $this->container->get($toPacket);
        $query = new \Elastica\Query();

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, $index);

        $aggsDateHistogramViewMonth = new \Elastica\Aggregation\DateHistogram("date_histogram_view_month", $string_date, "month");

        $queryDSL = $this->queryDsl2($s_cache,$string_date);

        $query->setRawQuery($queryDSL);

        $query->addAggregation($aggsDateHistogramViewMonth);

//        $res = $finder->findPaginated($query);

//        $res->setMaxPerPage($size);

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);

        $charts[] = array('Month/Year', 'Total Shipments');
        foreach ($res->getCurrentPageResults()->getAggregations()['date_histogram_view_month']['buckets'] as $googleChart) {
            if ($googleChart['key'] > 0) {
                $charts[] = array(date("M Y", strtotime($googleChart['key_as_string'])), $googleChart['doc_count']);
            } else {
                $charts[] = array('undefined', $googleChart['doc_count']);
            }


        }

        return new Response(json_encode($charts));
    }

    /**
     * @Route(
     *   "/member/total-shipments-us-importers",
     *   name     = "member_total_shipments_us_importers"
     * )
     *
     *
     */
    public function totalShipmentsAction()
    {

        $s_cache = $this->getRequest()->get('s_cache');

//        $toPacket = 'fos_elastica.finder.stradetegy.usa_importers';

//        $finder = $this->container->get($toPacket);
        $query = new \Elastica\Query();
        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'stradetegy_us_imports');

        $aggsDateHistogramViewMonth = new \Elastica\Aggregation\DateHistogram("date_histogram_view_month", "actual_arrival_date", "month");
        $aggsTotalWeight = new \Elastica\Aggregation\Sum("total_weight");
        $aggsTotalWeight->setField("weight");

        $queryDSL = $this->queryDsl2($s_cache,'actual_arrival_date');

        $query->setRawQuery($queryDSL);

        $query->addAggregation($aggsDateHistogramViewMonth);
        $query->addAggregation($aggsTotalWeight);

//        $res = $finder->findPaginated($query);
        $util = new StringTwig();
        $util->setEm($this->em());

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);

        $params = array(
            'success' => true,
            'total_row' => $res->getNbResults(),
            'total_weight' => $util->setting_gross_weight($res->getCurrentPageResults()->getAggregations()['total_weight']['value'], $this->getUser()->getId())
        );


        return new Response(json_encode($params));
    }

    public function queryDsl2($s_cache,$string_column)
    {
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($s_cache));



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

        $filter['bool']['must'][] = array(
            "range" => array(
                $string_column => array(

                    "from" => $s_cache->date_range->from,
                    "to" => $s_cache->date_range->to

                )
            )
        );

        $countryForms = array();
        if (isset($s_cache->country)) {
            $qCountry = '';
            $i = 0;
            foreach ($s_cache->country as $country) {
                $qCountry = $qCountry . $country;

                if (count($s_cache->country) > 0 and $i < count($s_cache->country)) {
                    $qCountry = $qCountry . ' or ';
                }

                $countryForms[] = $country;

                $i++;
            }

        }

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
            )
        );


        return $queryDSL;

    }


}