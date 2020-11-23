<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\BigPicture;

use Jariff\MemberBundle\Form\SearchGlobal\Form\SearchGlobalEmbedFormType;
use Jariff\MemberBundle\Model\CategoryEmbed;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\DocumentBundle\Document\SearchHistory;
use Jariff\DocumentBundle\Document\KeywordHistory;
use Jariff\DocumentBundle\Document\AlertsSaveGlobalSearch;
use Jariff\DocumentBundle\Document\ExportsSearchGlobal;
use Jariff\AdminBundle\Entity\Inbound;
use Jariff\MemberBundle\Model\Country;
use Jariff\MemberBundle\Model\Category;
use Jariff\MemberBundle\Model\CustomCountryData;
use Jariff\MemberBundle\Form\Widget\Field\CategoryFormType;

use Jariff\MemberBundle\Model\SearchFieldCollect;
use Jariff\MemberBundle\Model\SearchFieldQ;
use Jariff\MemberBundle\Model\SearchFieldSize;
use Jariff\MemberBundle\Model\SearchFieldCondition;
use Jariff\MemberBundle\Form\SearchGlobal\SearchEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Field\FieldSizeFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Field\AlertsSaveGlobalSearchFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Field\SaveGlobalSearchFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Field\ExportsSearchGlobalFormType;
use Jariff\MemberBundle\Form\Widget\Field\CountryFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomCountryDataFormType;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

//use Jariff\AdminBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Util as Util;
use Jariff\MemberBundle\Model\SearchEmbed;
use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;

use Pagerfanta\View\TwitterBootstrapView;
use Jariff\MemberBundle\Form\SearchGlobal\BigPicture\SearchGlobalFormType;
use Jariff\MemberBundle\Model\SearchBigPictureModel;

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
class SearchController extends BaseController
{

    /**
     * @Route(
     *   "/search-big-picture",
     *   name     = "member_search_big_picture"
     * )
     *
     * @Template("JariffMemberBundle:BigPicture\Search:index.html.twig")
     */
    public function indexAction()
    {
        $entity = new SearchBigPictureModel();

        // $search1 = new SearchFieldCollect();
        // $search1->collect = '';
        // $entity->getCollect()->add($search1);

        // $searchQ = new SearchFieldQ();
        // $searchQ->q = '';
        // $entity->getQ()->add($searchQ);

        $searchEmbed = new SearchEmbed();
        $formAppend = $this->createForm(new SearchGlobalEmbedFormType(), $searchEmbed, array(
            'action' => '',
            'method' => 'POST',
        ));


        $entity->setCategory('buyers');
        $form = $this->createForm(new SearchGlobalFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        return array(
            'form' => $form->createView(),
            'formAppend' => $formAppend->createView()
        );
    }

    /**
     * @Route(
     *   "/search-big-picture-submit",
     *   name     = "member_search_big_picture_submit"
     * )
     * @Method("POST")
     * @Template("JariffMemberBundle:BigPicture\Search:index.html.twig")
     */
    public function submitAction(Request $request)
    {
        $entity = new SearchBigPictureModel();

        $form = $this->createForm(new SearchGlobalFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $entityAppend = new SearchEmbed();


        $formAppend = $this->createForm(new SearchGlobalEmbedFormType(), $entityAppend, array(
            'action' => '',
            'method' => 'POST',
        ));

        $form->handleRequest($request);
        $formAppend->handleRequest($request);

        if ($form->isValid()) {

            $today = new \DateTime('now');
            if ($this->get('session')->get('timezone')) {
                $tz = new \DateTimeZone($this->get('session')->get('timezone'));
                $today = new \DateTime('now', $tz);
            }


            if (!$this->get('session')->get('checkSubmit')) {
                $this->get('session')->set('checkSubmit', "isubmit");
            }


            $q = array();
            $cache = array();

            $data = $form->getData();
            // mungkin ini agak muter2 tp karena angka yang dynamic dan disesuaikan dengan view
            $util = new StringTwig();
            $cache['time'] = strtotime($today->format('YmdHis'));

            $active_subscription = $this->user()->getLastSubscription();
            if (is_null($active_subscription) || !$active_subscription->isActive()) {
                return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
            }

            $historyValue = $active_subscription->getHistoryValue();

            $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));

            $condition = array();

            foreach ($formAppend->getData()->getCondition() as $key) {
                $condition[] = $key->condition;
            }

            foreach ($formAppend->getData()->getQ() as $key) {
                $q[] = $key->q;
            }

            // var_dump($data->getCountry());
            // die();

            $cache['search'][] =
                array(
                    'condition' => null,
                    'collect' => ($data->getCategory() == 'shipments') ? 'all' : $data->getCategory(),
                    'q' => $data->getQ()
                );

            if (!empty($condition)) {

                $j = 0;

                foreach ($formAppend->getData()->getCollect() as $key) {


                    $cache['search'][] =
                        array(
                            'condition' => $condition[$j],
                            'collect' => $key->collect,
                            'q' => $q[$j]
                        );
                    $j++;

                }
            }

            $qCountrys = array();
            $qCountrys[] =  $data->getCountry();
            
            $cache = (array)$cache;
            if (isset($cache['country']))
                unset($cache['country']);

            $cache = array_merge($cache,
                !empty($qCountrys) ? array('country' => $qCountrys) : array());

            $cacheJson = json_encode(array_merge($cache,
                        array('type' => array(
                            'custom_country' => $data->getCountry(),
                            'custom_data' => 'imports',
                        )
                        ),
                        array('date_range' =>
                            array(
                                'from' => $todayMin,
                                'to' => $today->format("Y-m-d"),
                                'not_valid_date' => null,

                            ),
                            'marked_master' => 'M'
                        )
                    )
                );


            // echo $cacheJson;
            // die();

            $s_cache = $util->encrypted($cacheJson);


            if ($data->getCategory() == 'shipments') {
                return $this->redirectUrl('member_search_shipments_result_us_imports', array(
                    's_cache' => $s_cache
                ));

            } else {

                $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
                $today = new \DateTime('now');
                $member_search = $this->user()->getLastSubscription();;

                $querycek = new QueryEs();
                $querycek->setRawQuery(array("query" => array("match" => array("user_id" => $this->getUser()->getId()))));
                // $searchableCek = new Index($client, 'logst-' . $today->format("Ym"));
                // $adapterCek = new ElasticaAdapter($searchableCek, $querycek);
                // $resCek = new Pagerfanta($adapterCek);

                // if (($resCek->getNbResults() + 1) > $member_search->getSearchValue()) {
                //     return $this->errorRedirect('Your search limit', 'member_search_shipments');
                // }

                $history = array(
                    "total_row" => 0,
                    "date_search" => $today->format('Y-m-d'),
                    "user_id" => $this->getUser()->getId(),
                    "query" => $s_cache,
                    "priority" => 0,
                    "set_pin" => "unpin"
                );

                // $doc[] = new \Elastica\Document('', $history, "shipments_log", 'logst-' . $today->format("Ym"));

                // $client->addDocuments($doc);
                // $client->getIndex('logst-' . $today->format("Ym"))->refresh();

                return $this->redirectUrl('member_search_big_picture_result', array(
                    'category' => $data->getCategory(),
                    's_cache' => $s_cache
                ));
            }
        } 
        // else {
        //     var_dump($form->getErrors());
        // }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *   "/search-big-picture-category-submit",
     *   name     = "member_search_big_picture_category_submit"
     * )
     * @Method("POST")
     * @Template("JariffMemberBundle:BigPicture\Search:index.html.twig")
     */
    public function submitCategoryAction(Request $request)
    {
        $entity = new Category();

        $form = $this->createForm(new CategoryFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        $util = new StringTwig();
        $s_cache = $this->getRequest()->get('s_cache');


        if ($form->isValid()) {

            $catArray = array();
            foreach ($form->getData()->getCategory() as $category) {
                $catArray[] = $category;
            }

            $countryArray = array();
            foreach ($form->getData()->getCountry() as $country) {
                $countryArray[] = $country;
            }

            $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

            if (isset($s_cache->category))
                unset($s_cache->category);

            if (isset($s_cache->country))
                unset($s_cache->country);


            $s_cache = array_merge((array)$s_cache, array('category' => $catArray));
            $s_cache = array_merge((array)$s_cache, array('country' => $countryArray));
            $jsonS_cache = json_encode($s_cache);

            $s_cache = $util->encrypted($jsonS_cache);
        }


        return new Response(
            json_encode(array(
                'success' => true,
                'urls' => $this->generateUrl('member_search_big_picture_result', array('s_cache' => $s_cache))
            ))
        );

    }

    private $s_cache_json;

    /**
     * @Route(
     *   "/search-big-picture-res/buyers/{page}",
     *   name     = "member_search_big_picture_buyers_res",
     *   options={"expose"=true}
     * )
     *
     *
     */
    public function buyersSearchAction($page = 1)
    {
        $util = new StringTwig();

        // var_dump($util->decrypted($this->getRequest()->get('s_cache')));
        // die();

        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        if (!isset($s_cache->time)) {
            return $this->errorRedirect('Your search is expired, please submit again', 'member_search_big_picture');
        }

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || !$active_subscription->isActive()) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }


        $keyword = $s_cache->search[0]->q;
        $queryFirst = array(
            "query_string" => array(
                'query' => $keyword
            )
        );

        $filterKeyword = $this->repo('JariffAdminBundle:FilterKeywords')->findAll();

        foreach ($filterKeyword as $key) {

            if (!isset($keywords))
                $keywords = $key->getKeyword();
            else
                $keywords .= ' or ' . $key->getKeyword();
        }


        $filter['bool']['must_not'][] =  array(
                "query_string" => array(
                    'query' => $keywords
                )
        );

        $i = 0;
        foreach ($s_cache->search as $s) {

            if ($i > 0) {


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


            }
            $i++;
        }


        $filter['bool']['must'][] = array(
                "query_string" => array(
                    'default_field' => "company_as",
                    "query" => "importer"
                )
        );

        $highlight = array(
            "highlight" => array(
                "number_of_fragments" => 3,
                "fragment_size" => 150,
                "fields" => array(
                    "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "product_desc" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                )
            )
        );

        $query = new \Elastica\Query();
        $company_as = new \Elastica\Aggregation\Terms('company_as');
        $company_as->setField('company_as');
        $company_as->setSize(30);

        $countryAggs = new \Elastica\Aggregation\Terms('slug_country');
        $countryAggs->setField('slug_country');
        $countryAggs->setSize(10);

        $queryDSL = array_merge(array(
                "query" => array_merge(array(
                        "bool" => array_merge(array(
                                "must" => $queryFirst
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

        $query->addAggregation($company_as);
        $query->addAggregation($countryAggs);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'trade-global-usa');
        // print json_encode($query->toArray()) . "\n";
        // die();

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);


        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('member_search_big_picture_buyers_res', array('page' => $page, 's_cache' => $this->s_cache_json));
        }, $options);
        // $html = "";

        $today = new \DateTime('now');
        $diff = abs(strtotime($today->format("Y-m-d H:i:s")) - $s_cache->time);


        $maxTime = $diff / 3600;
        $member_search = $active_subscription;

        $check = $this->repo("JariffMemberBundle:SavedCompany")->findBy(array('member' => $this->getUser(), 'category' => 'buyers'));

        foreach ($check as $checkTolist) {
            $dataChoices[$checkTolist->getSlugCompany()] = true;


            if ($checkTolist->getIsCompare()) {
                $dataCompare[$checkTolist->getSlugCompany()] = true;
            }
        }

        $params = array(

            'total_results' => $res->getNbResults(),
            'keyword' => $keyword,
            'pagination' => $html,
            'resHybird' => $res->getCurrentPageResults()->getResults(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'checkCompare' => isset($dataCompare) ? $dataCompare : array(),
        );
        // var_dump($params);
        // die();

        $slugs = array();
        $names = array();
        foreach ($res->getCurrentPageResults()->getResults() as $result) {
            $slug = $result->getData()["slug_consignee_name"];
            $name = $result->getData()["consignee_name"];
            $slugs[] = $slug;
            $names[] = $name;
        }


        $twigEx = new StringTwig;
        return new Response(json_encode(
            array(
                "success" => true, 
                'slugs' => $slugs,
                'names' => $names,
                'total_results' => $twigEx->ribuan($res->getNbResults()),
                "total_" . $res->getCurrentPageResults()->getAggregations()['company_as']['buckets'][0]["key"] => $res->getCurrentPageResults()->getAggregations()['company_as']['buckets'][0]["doc_count"],
                'html_string' => $this->renderView('JariffMemberBundle:BigPicture\Search:buyers.html.twig', $params),

            )
        ));

    }


    /**
     * @Route(
     *   "/search-big-picture-result/{category}/{page}",
     *   name     = "member_search_big_picture_result",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     *
     * @Template("JariffMemberBundle:BigPicture\Search:results.html.twig")
     */
    public function resultAction($category, $page = 1)
    {
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        // echo json_encode($s_cache); die();
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        if (!isset($s_cache->time)) {
            return $this->errorRedirect('Your search is expired, please submit again', 'member_search_big_picture');
        }

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || !$active_subscription->isActive()) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

        $entityPrimary = new SearchBigPictureModel();


        $entityPrimary->setCountry($s_cache->type->custom_country);
        $entityPrimary->setCategory($category);
        $entityPrimary->setQ($s_cache->search[0]->q);

        $form = $this->createForm(new SearchGlobalFormType(), $entityPrimary, array(
            'action' => '',
            'method' => 'POST',
        ));

        $entity = new SearchEmbed();

        foreach ($s_cache->search as $s) {

            if (!is_null($s->condition)) {
                $search1 = new SearchFieldCollect();
                $search1->collect = $s->collect;
                $entity->getCollect()->add($search1);

                $searchQ = new SearchFieldQ();
                $searchQ->q = $s->q;
                $entity->getQ()->add($searchQ);

                $searchCondition = new SearchFieldCondition();
                $searchCondition->condition = $s->condition;
                $entity->getCondition()->add($searchCondition);
            } 
        }

        $formAppend = $this->createForm(new SearchGlobalEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $keyword = $s_cache->search[0]->q;

        if (!empty($s_cache)) {

            $url = $this->generateUrl('member_' . $category . '_res', array('keyword' => $keyword, 's_cache' => $this->s_cache_json));
            $url_window = $this->generateUrl('member_search_big_picture_submit', array('category' => $category, 'keyword' => $keyword, 's_cache' => $this->s_cache_json));

        }

        unset($s_cache->search);

        $s_cache->search[] =
            array(
                'condition' => null,
                'collect' => 'all',
                'q' => $keyword
            );
        $s_cache->marked_master = array();

        $s_cache_for_shipments = $util->encrypted(json_encode($s_cache));


        return array(
            'form' => $form->createView(),
            'formAppend' => $formAppend->createView(),
            's_cache' => $this->s_cache_json,
            's_cache_for_shipments' => $s_cache_for_shipments,
            $category . "_url" => (isset($url)) ? $url : null,
            $category . "_url_window" => (isset($url_window)) ? $url_window : null,
            'category' => $category
        );
    }

    /**
     * @Route(
     *   "/search-res/buyers-form-country",
     *   name     = "member_buyers_show_filter_country_res",
     *   options={"expose"=true}
     * )
     *
     *
     */
    public function buyersSearchFormCountryAction()
    {

        $util = new StringTwig();

        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        if (!isset($s_cache->time)) {
            return $this->errorRedirect('Your search is expired, please submit again', 'member_search_big_picture');
        }

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || !$active_subscription->isActive()) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }


        $keyword = $s_cache->search[0]->q;

        $queryFirst = array(
            "query_string" => array(
                'query' => $keyword
            )
        );

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


        $filter['bool']['must'][] = array(
            'query' => array(
                "query_string" => array(
                    'default_field' => "company_as",
                    "query" => "importer"
                )
            )
        );

        $i = 0;
        foreach ($s_cache->search as $s) {

            if ($i > 0) {


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


            }
            $i++;
        }



        $highlight = array(
            "highlight" => array(
                "number_of_fragments" => 3,
                "fragment_size" => 150,
                "fields" => array(
                    "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "product_desc" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                )
            )
        );

        $query = new \Elastica\Query();

        $countryAggs = new \Elastica\Aggregation\Terms('slug_country');

        $countryAggs->setField('slug_country');
        $countryAggs->setSize(10);


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
            array('from' => 0)
        );


        $query->setRawQuery($queryDSL);
        $query->addAggregation($countryAggs);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'trade-global-usa');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);

        // var_dump(array("us" => "aass"));
        // die();


        $country = new Country();

        $formCountry = $this->createForm(new CountryFormType(), $country, array(
            'action' => '',
            'method' => 'POST',
            'dataCountry' => $res->getCurrentPageResults()->getAggregations()['slug_country']['buckets']
        ));

        $params = array(
            'form' => $formCountry->createView(),
            's_cache' => $this->s_cache_json
        );

        $twigEx = new StringTwig;
        return new Response(json_encode(
            array(
                "success" => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Global\New\Append\Col:buyers_col_md.html.twig', $params),

            )
        ));

    }

    /**
     * @Route(
     *   "/search-big-picture-res/suppliers/{page}",
     *   name     = "member_search_big_picture_suppliers_res",
     *   options={"expose"=true}
     * )
     *
     *
     */
    public function suppliersSearchAction($page = 1)
    {
        $util = new StringTwig();

        // var_dump($util->decrypted($this->getRequest()->get('s_cache')));
        // die();

        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        if (!isset($s_cache->time)) {
            return $this->errorRedirect('Your search is expired, please submit again', 'member_search_big_picture');
        }

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || !$active_subscription->isActive()) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }


        $keyword = $s_cache->search[0]->q;
        $queryFirst = array(
            "query_string" => array(
                'query' => $keyword
            )
        );

        $filterKeyword = $this->repo('JariffAdminBundle:FilterKeywords')->findAll();

        foreach ($filterKeyword as $key) {

            if (!isset($keywords))
                $keywords = $key->getKeyword();
            else
                $keywords .= ' or ' . $key->getKeyword();
        }


        $filter['bool']['must_not'][] =  array(
                "query_string" => array(
                    'query' => $keywords
                )
        );

        $i = 0;
        foreach ($s_cache->search as $s) {

            if ($i > 0) {


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


            }
            $i++;
        }


        $filter['bool']['must'][] = array(
                "query_string" => array(
                    'default_field' => "company_as",
                    "query" => "exporter"
                )
        );

        $highlight = array(
            "highlight" => array(
                "number_of_fragments" => 3,
                "fragment_size" => 150,
                "fields" => array(
                    "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "product_desc" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                )
            )
        );

        $query = new \Elastica\Query();
        $company_as = new \Elastica\Aggregation\Terms('company_as');
        $company_as->setField('company_as');
        $company_as->setSize(30);

        $countryAggs = new \Elastica\Aggregation\Terms('slug_country');
        $countryAggs->setField('slug_country');
        $countryAggs->setSize(10);

        $queryDSL = array_merge(array(
                "query" => array_merge(array(
                        "bool" => array_merge(array(
                                "must" => $queryFirst
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

        $query->addAggregation($company_as);
        $query->addAggregation($countryAggs);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'trade-global-usa');
        // print json_encode($query->toArray()) . "\n";
        // die();

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);


        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('member_search_big_picture_suppliers_res', array('page' => $page, 's_cache' => $this->s_cache_json));
        }, $options);
        // $html = "";

        $today = new \DateTime('now');
        $diff = abs(strtotime($today->format("Y-m-d H:i:s")) - $s_cache->time);


        $maxTime = $diff / 3600;
        $member_search = $active_subscription;

        $check = $this->repo("JariffMemberBundle:SavedCompany")->findBy(array('member' => $this->getUser(), 'category' => 'suppliers'));

        foreach ($check as $checkTolist) {
            $dataChoices[$checkTolist->getSlugCompany()] = true;


            if ($checkTolist->getIsCompare()) {
                $dataCompare[$checkTolist->getSlugCompany()] = true;
            }
        }

        $params = array(

            'total_results' => $res->getNbResults(),
            'keyword' => $keyword,
            'pagination' => $html,
            'resHybird' => $res->getCurrentPageResults()->getResults(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'checkCompare' => isset($dataCompare) ? $dataCompare : array(),
        );
        // var_dump($params);
        // die();

        $slugs = array();
        $names = array();
        foreach ($res->getCurrentPageResults()->getResults() as $result) {
            $slug = $result->getData()["slug_shipper_name"];
            $name = $result->getData()["shipper_name"];
            $slugs[] = $slug;
            $names[] = $name;
        }

        $twigEx = new StringTwig;

        return new Response(json_encode(
            array(
                "success" => true,
                'slugs' => $slugs,
                'names' => $names,
                'total_results' => $twigEx->ribuan($res->getNbResults()),
                'html_string' => $this->renderView('JariffMemberBundle:BigPicture\Search:suppliers.html.twig', $params),

            )
        ));

    }

    /**
     * @Route(
     *   "/search-big-picture-res/suppliers-form-country",
     *   name     = "member_search_big_picture_suppliers_show_filter_country_res",
     *   options={"expose"=true}
     * )
     *
     *
     */
    public function suppliersSearchFormCountryAction()
    {
        
        $util = new StringTwig();

        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        if (!isset($s_cache->time)) {
            return $this->errorRedirect('Your search is expired, please submit again', 'member_search_big_picture');
        }

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || !$active_subscription->isActive()) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }


        $keyword = $s_cache->search[0]->q;

        $queryFirst = array(
            "query_string" => array(
                'query' => $keyword
            )
        );

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


        $filter['bool']['must'][] = array(
            'query' => array(
                "query_string" => array(
                    'default_field' => "company_as",
                    "query" => "exporter"
                )
            )
        );

        $i = 0;
        foreach ($s_cache->search as $s) {

            if ($i > 0) {


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


            }
            $i++;
        }



        $highlight = array(
            "highlight" => array(
                "number_of_fragments" => 3,
                "fragment_size" => 150,
                "fields" => array(
                    "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "product_desc" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                )
            )
        );

        $query = new \Elastica\Query();

        $countryAggs = new \Elastica\Aggregation\Terms('slug_country');

        $countryAggs->setField('slug_country');
        $countryAggs->setSize(10);


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
            array('from' => 0)
        );


        $query->setRawQuery($queryDSL);
        $query->addAggregation($countryAggs);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'trade-global-usa');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);

        // var_dump(array("us" => "aass"));
        // die();


        $country = new Country();

        $formCountry = $this->createForm(new CountryFormType(), $country, array(
            'action' => '',
            'method' => 'POST',
            'dataCountry' => $res->getCurrentPageResults()->getAggregations()['slug_country']['buckets']
        ));

        $params = array(
            'form' => $formCountry->createView(),
            's_cache' => $this->s_cache_json
        );

        $twigEx = new StringTwig;

        return new Response(json_encode(
            array(
                "success" => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Global\New\Append\Col:suppliers_col_md.html.twig', $params),

            )
        ));

    }

    /**
     * @Route(
     *   "/search-big-picture-res/logistics/{page}",
     *   name     = "member_search_big_picture_logistics_res",
     *   options={"expose"=true}
     * )
     *
     *
     */
    public function logiticsSearchAction($page = 1)
    {
        $util = new StringTwig();

        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        if (!isset($s_cache->time)) {
            return $this->errorRedirect('Your search is expired, please submit again', 'member_search_big_picture');
        }

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || !$active_subscription->isActive()) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }


        $keyword = $s_cache->search[0]->q;

        $queryFirst = array(
            "query_string" => array(
                'default_field' => "product_desc",
                "query" => $keyword
            )
        );

        $filterKeyword = $this->repo('JariffAdminBundle:FilterKeywords')->findAll();

        foreach ($filterKeyword as $key) {

            if (!isset($keywords))
                $keywords = $key->getKeyword();
            else
                $keywords .= ' or ' . $key->getKeyword();
        }

        $i = 0;
        foreach ($s_cache->search as $s) {

            if ($i > 0) {


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


            }
            $i++;
        }


        $filter['bool']['must_not'][] = array(
            'query' => array(
                "query_string" => array(
                    'query' => $keywords
                )
            )
        );


        $filter['bool']['must'][] = array(
            'query' => array(
                "query_string" => array(
                    'default_field' => "company_as",
                    "query" => "exporter"
                )
            )
        );


        $highlight = array(
            "highlight" => array(
                "number_of_fragments" => 3,
                "fragment_size" => 150,
                "fields" => array(
                    "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "product_desc" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                )
            )
        );

        $query = new \Elastica\Query();

        $countryAggs = new \Elastica\Aggregation\Terms('slug_country');

        $countryAggs->setField('slug_country');
        $countryAggs->setSize(10);
        $company_as = new \Elastica\Aggregation\Terms('company_as');
        $company_as->setField('company_as');
        $company_as->setSize(30);

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
            array('from' => 0)
        );


        $query->setRawQuery($queryDSL);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'trade-global-usa');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);

        // var_dump($res->getCurrentPageResults()->getAggregations()['slug_country']['buckets']);
        // die();

        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page = 1) {
            return $this->generateUrl('member_search_big_picture_logistics_res', array('page' => $page, 's_cache' => $this->s_cache_json));
        }, $options);


        $today = new \DateTime('now');
        $diff = abs(strtotime($today->format("Y-m-d H:i:s")) - $s_cache->time);


        $maxTime = $diff / 3600;
        $member_search = $active_subscription;
//
//        if ($this->get('session')->get('checkSubmit')) {
//
//            $querycek = new QueryEs();
//            $querycek->setRawQuery(array("query" => array("match" => array("user_id" => $this->getUser()->getId()))));
//            $searchableCek = new Index($client, 'logst-' . $today->format("Ymd"));
//            $adapterCek = new ElasticaAdapter($searchableCek, $querycek);
//            $resCek = new Pagerfanta($adapterCek);
//
//            if (($resCek->getNbResults() + 1) > $member_search->getSearchValue()) {
//                return $this->errorRedirect('Your search limit', 'member_search_big_picture');
//            }
//
//            $this->get('session')->remove('checkSubmit');
//            $history = array(
//                "total_row" => $res->getNbResults(),
//                "date_search" => $today->format('Y-m-d'),
//                "user_id" => $this->getUser()->getId(),
//                "query" => $this->s_cache_json,
//                "priority" => 0,
//                "set_pin" => "unpin"
//            );
//
//            $doc[] = new \Elastica\Document('', $history, "shipments_log", 'logst-' . $today->format("Ymd"));
//
//            $client->addDocuments($doc);
//            $client->getIndex('logst-' . $today->format("Ymd"))->refresh();
//        }
//
        if ($maxTime > 1) {
            return new Response(json_encode(
                array(
                    "success" => true,
                    'total_results' => 0,
                    'html_string' => '<div class="alert alert-danger" role="alert"><h3>Your Search is expired, please submit again</h3></div>',

                )
            ));
        }

        $params = array(
            // 'form' => $formCountry->createView(),
            'total_results' => $res->getNbResults(),
            'keyword' => $keyword,
            'pagination' => $html,
            'resHybird' => $res->getCurrentPageResults()->getResults(),
        );

        $twigEx = new StringTwig;

        return new Response(json_encode(
            array(
                "success" => true,
                'total_results' => $twigEx->ribuan($res->getNbResults()),
                'html_string' => $this->renderView('JariffMemberBundle:Search\Global\New\Append:logistics.html.twig', $params),

            )
        ));

    }

    /**
     * @Route(
     *   "/search-filter/submit/country/{cat}",
     *   name     = "member_submit_country",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     */
    public function submitCountryAction(Request $request, $cat)
    {
        $data = $request->get('country_widgets');
        $util = new StringTwig();

        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')), 1);


        $qCountrys = array();
        if (isset($data['country'])) {

            foreach ($data['country'] as $country) {
                $qCountrys[] = $country;
            }
        }

        $s_cache = (array)$s_cache;
        if (isset($s_cache['country']))
            unset($s_cache['country']);

        $s_cache = array_merge($s_cache,
            !empty($qCountrys) ? array('country' => $qCountrys) : array());


        $s_cacheJson = json_encode($s_cache);

        $s_cache = $util->encrypted($s_cacheJson);

        return new Response(json_encode(
                array(
                    'success' => true,
                    'urls' => $this->generateUrl('member_search_big_picture_result', array('category' => $cat, 's_cache' => $s_cache)),
                    'urls_res' => $this->generateUrl('member_' . $cat . '_res', array('s_cache' => $s_cache)),

                )
            )
        );

    }


    /**
     * @Route(
     *   "/member-display-modal-alerts-global",
     *   name     = "member_display_modal_alerts_global",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function displayMemberAlertsGlobalAction()
    {

        $entity = new AlertsSaveGlobalSearch();

        $form = $this->createForm(new AlertsSaveGlobalSearchFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));


        $params = array(
            'formAlerts' => $form->createView(),
            's_cache' => $this->getRequest()->get('s_cache')
        );

        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Global\Add:modal_form_alerts.html.twig', $params),
                    's_cache' => ''
                )
            )
        );
    }

    /**
     * @Route(
     *   "/save-alerts-submit",
     *   name     = "member_display_modal_save_alerts_global_submit"
     * )
     * @Method("POST")
     *
     */
    public function submitSaveSearchAlertGlobalAction(Request $request)
    {
        $entity = new AlertsSaveGlobalSearch();

        $form = $this->createForm(new AlertsSaveGlobalSearchFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $form->handleRequest($request);


        $s_cache = $this->getRequest()->get('s_cache');


        if ($form->isValid()) {

            $entity->setUserId($this->getUser()->getId());
            $entity->setKeyword($s_cache);
            $entity->setIsAlerts(1);

            $this->dm()->persist($entity);
            $this->dm()->flush();

            $message = "Success Save Alerts";
        } else {
            $message = $form->getErrorsAsString();
        }


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'message' => "<div style='text-align:center;padding:10px'><h5>$message</h5><br/>" .
                        '<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>'
                        . "</div>"
                )
            )
        );

    }

    /**
     * @Route(
     *   "/member-display-modal-save-global",
     *   name     = "member_display_modal_save_global",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function displayMemberSaveGlobalAction()
    {

        $entity = new AlertsSaveGlobalSearch();

        $form = $this->createForm(new SaveGlobalSearchFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));


        $params = array(
            'formSave' => $form->createView(),
            's_cache' => $this->getRequest()->get('s_cache')
        );

        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Global\Add:modal_form_save.html.twig', $params),
                    's_cache' => ''
                )
            )
        );
    }

    /**
     * @Route(
     *   "/save-global-submit",
     *   name     = "member_display_modal_save_global_submit"
     * )
     * @Method("POST")
     *
     */
    public function submitSaveSearchGlobalAction(Request $request)
    {
        $entity = new AlertsSaveGlobalSearch();

        $form = $this->createForm(new SaveGlobalSearchFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $form->handleRequest($request);


        $s_cache = $this->getRequest()->get('s_cache');


        if ($form->isValid()) {

            $entity->setUserId($this->getUser()->getId());
            $entity->setKeyword($s_cache);

            $this->dm()->persist($entity);
            $this->dm()->flush();

            $message = "Success Save Alerts";
        } else {
            $message = $form->getErrorsAsString();
        }


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'message' => "<div style='text-align:center;padding:10px'><h5>$message</h5><br/>" .
                        '<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>'
                        . "</div>"
                )
            )
        );

    }

    /**
     * @Route(
     *   "/member-display-modal-global-shipments",
     *   name     = "member_display_modal_exports_global",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function displayMemberExportsGlobalAction()
    {

        $entity = new ExportsSearchGlobal();
        $entity->setEmail($this->getUser()->getEmail());
        $form = $this->createForm(new ExportsSearchGlobalFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || !$active_subscription->isActive()) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

        $historyDownload = $this->repoMongo("JariffDocumentBundle:ExportsSearchGlobal")->findBy(array('user_id' => $this->getUser()->getId()));

        $member_search = $this->repo('JariffMemberBundle:MemberSubscription')->findOneBy(array(
            'member' => $this->getUser()->getId(),
            'active' => true
        ));

        $params = array(
            'formExportsShipments' => $form->createView(),
            's_cache' => $this->getRequest()->get('s_cache'),
            'maxDownload' => $member_search->getDownloadLimit(),
            'historyDownload' => $historyDownload
        );


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Global\Add:modal_form_exports.html.twig', $params),
                    's_cache' => ''
                )
            )
        );
    }

    /**
     * @Route(
     *   "/exports-global-submit",
     *   name     = "member_display_modal_global_submit"
     * )
     * @Method("POST")
     *
     */
    public function submitExportsSearchGlobalAction(Request $request)
    {
        $entity = new ExportsSearchGlobal();

        $form = $this->createForm(new ExportsSearchGlobalFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $form->handleRequest($request);


        $s_cache = $this->getRequest()->get('s_cache');


        if ($form->isValid()) {

            if ($form->getData()->getDownloadFrom() > $form->getData()->getDownloadTo()) {
                return new Response(
                    json_encode(
                        array(
                            'success' => 'false',
                            'message' => 'from more than to'
                        )
                    )
                );
            }

            $active_subscription = $this->user()->getLastSubscription();
            if (is_null($active_subscription) || !$active_subscription->isActive()) {
                return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
            }
            $member_search = $this->repo('JariffMemberBundle:MemberSubscription')->findOneBy(array(
                'member' => $this->getUser()->getId(),
                'active' => true
            ));

            if ($member_search->getDownloadLimit() < ($form->getData()->getDownloadTo() - ($form->getData()->getDownloadFrom() - 1))) {
                return new Response(
                    json_encode(
                        array(
                            'success' => 'false',
                            'message' => 'Error max download'
                        )
                    )
                );
            }


            $totalDownload = $member_search->getDownload() + ($form->getData()->getDownloadTo() - ($form->getData()->getDownloadFrom() - 1));
            $member_search->setDownload($totalDownload);
            $member_search->setDownloadLimit($member_search->getDownloadLimit() + ($form->getData()->getDownloadTo() - ($form->getData()->getDownloadFrom() - 1)));


            $this->em()->persist($member_search);
            $this->em()->flush();

            $entity->setUserId($this->getUser()->getId());
            $entity->setKeyword($s_cache);
            $entity->setFileName($form->getData()->getFileName() . '-' . date("ymdhis"));

            $this->dm()->persist($entity);
            $this->dm()->flush();

            $success = true;
            $message = "Success save, request still on que";


        } else {
            $success = false;
            $message = $form->getErrorsAsString();
        }


        return new Response(
            json_encode(
                array(
                    'success' => $success,
                    'message' => $message
                )
            )
        );

    }

}

