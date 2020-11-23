<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\ProjectBundle\Controller\Search;

use Jariff\MemberBundle\Form\Widget\CategoryEmbedFormType;
use Jariff\MemberBundle\Model\SearchEmbed;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\ProjectBundle\Util as Util;

use Jariff\MemberBundle\Model\SearchFieldCollect;
use Jariff\MemberBundle\Model\SearchFieldQ;
use Jariff\MemberBundle\Model\FilterCountry;
use Jariff\MemberBundle\Model\Country;
use Jariff\MemberBundle\Model\FilterCountryEmbed;

use Jariff\MemberBundle\Model\Category;
use Jariff\MemberBundle\Model\CategoryEmbed;


use Jariff\MemberBundle\Form\Demo\SearchEmbedFormType;
use Jariff\MemberBundle\Form\Widget\Field\CountryFormType;
use Jariff\MemberBundle\Form\Demo\SearchGlobalFormType;
use Jariff\MemberBundle\Model\SearchGlobalModel;

use Jariff\MemberBundle\Form\Widget\FilterCountryEmbedFormType;
use Jariff\MemberBundle\Form\Widget\Field\CategoryFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
class DemoSearchGlobalController extends BaseController
{

    /**
     * @Route(
     *   "/search",
     *   name     = "demo_global_search"
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo:index.html.twig")
     */
    public function indexAction()
    {
        $entity = new SearchGlobalModel();

        // $search1 = new SearchFieldCollect();
        // $search1->collect = '';
        // $entity->getCollect()->add($search1);

        // $searchQ = new SearchFieldQ();
        // $searchQ->q = '';
        // $entity->getQ()->add($searchQ);

        $entity->setCategory('buyers');

        $form = $this->createForm(new SearchGlobalFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        return array(
            'form' => $form->createView()
            );
    }

    /**
     * @Route(
     *   "/search/ref",
     *   name     = "demo_global_search_submit"
     * )
     * @Method("POST")
     * @Template("JariffProjectBundle:Search\Demo:index.html.twig")
     */
    public function submitAction(Request $request)
    {
        $entity = new SearchGlobalModel();

        $form = $this->createForm(new SearchGlobalFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            // $util = new StringTwig();

            // $cache = array(
            //     'search' => array(
            //         array(
            //             'category' => $data->getCategory(),
            //             'condition' => '',
            //             'collect' => '',
            //             'q' => $data->getQ()
            //             )
            //         )
            //     );

            // $s_cache = $util->encrypted(json_encode($cache));

            return $this->redirectUrl("demo_global_search_res",array('category' => $data->getCategory(),'keyword' => $data->getQ()));
            

            // if (preg_match('/product_description/i', $data->getCollect()[0]->collect)) {

            //     $cache = array_merge($cache, array('category' => 'product'));

            //     $s_cache = $util->encrypted(json_encode($cache));


            //     return $this->redirectUrl('demo_global_search_show_product', array(
            //         'keyword' => $data->getQ()[0]->q,
            //         's_cache' => $s_cache
            //         ));
            // }

            // if (preg_match('/consignee/i', $data->getCollect()[0]->collect)) {

            //     $cache = array_merge($cache, array('category' => 'importer'));

            //     $s_cache = $util->encrypted(json_encode($cache));
            //     return $this->redirectUrl('demo_global_search_show_importer', array(
            //         'keyword' => $data->getQ()[0]->q,
            //         's_cache' => $s_cache
            //         ));
            // }

            // if (preg_match('/shipper/i', $data->getCollect()[0]->collect)) {

            //     $cache = array_merge($cache, array('category' => 'exporter'));

            //     $s_cache = $util->encrypted(json_encode($cache));
            //     return $this->redirectUrl('demo_global_search_show_exporter', array(
            //         'keyword' => $data->getQ()[0]->q,
            //         's_cache' => $s_cache
            //         ));
            // }

            // return $this->redirectUrl('demo_global_search_show_all', array(
            //     'keyword' => $data->getQ()[0]->q,
            //     ));

        }

        return array(
            'form' => $form->createView()
            );
    }

    /**
     * @Route(
     *   "/search/{category}/{keyword}",
     *   name     = "demo_global_search_res"
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo\New:results.html.twig")
     */
    public function globalSearchResultAction($category,$keyword)
    {

        $entity = new SearchGlobalModel();
        $s_cache = $this->getRequest()->get('s_cache');

        $entity->setCategory($category);
        $entity->setQ($keyword);

        $form = $this->createForm(new SearchGlobalFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        if(!empty($s_cache)){
            $url = $this->generateUrl('demo_'.$category.'_res',array('keyword' => $keyword,'s_cache' => $s_cache));
            $url_window = $this->generateUrl('demo_global_search_res',array('category' => $category,'keyword' => $keyword,'s_cache' => $s_cache));

        }

        return array(
            'form' => $form->createView(),
            's_cache' => $s_cache,
            $category."_url" => (isset($url)) ? $url : null,
            $category."_url_window" => (isset($url_window)) ? $url_window : null
            );

    }

    /**
     * @Route(
     *   "/search-res/buyers/{keyword}",
     *   name     = "demo_buyers_res",
     *   options={"expose"=true}
     * )
     *
     * 
     */
    public function buyersSearchAction($keyword)
    {

        return new Response("Access Denied"); 

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

        $query->addAggregation($company_as);


        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'stglobals');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);

        // var_dump(array("us" => "aass"));
        // die();
        
        $res->setMaxPerPage(20);
        $res->setCurrentPage(1);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page = 1) {
            return $this->generateUrl('member_search_shipments_result_us_imports_ajax', array('page' => $page, 's_cache' => "test"));
        }, $options);
        


        $params = array(

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
                "total_".$res->getCurrentPageResults()->getAggregations()['company_as']['buckets'][0]["key"] => $res->getCurrentPageResults()->getAggregations()['company_as']['buckets'][0]["doc_count"],
                'html_string' => $this->renderView('JariffProjectBundle:Search\Demo\New\Append:buyers.html.twig', $params),

                )
            ));

    }

    /**
     * @Route(
     *   "/search-res/buyers-form-country/{keyword}",
     *   name     = "demo_buyers_show_filter_country_res",
     *   options={"expose"=true}
     * )
     *
     * 
     */
    public function buyersSearchFormCountryAction($keyword)
    {
        return new Response("Access Denied"); 

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
        $searchable = new Index($client, 'stglobals');

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
            );

        $twigEx = new StringTwig;
        return new Response(json_encode(
            array(
                "success" => true,
                'html_string' => $this->renderView('JariffProjectBundle:Search\Demo\New\Append\Col:buyers_col_md.html.twig', $params),

                )
            ));

    }

    /**
     * @Route(
     *   "/search-res/suppliers/{keyword}",
     *   name     = "demo_suppliers_res",
     *   options={"expose"=true}
     * )
     *
     *
     */
    public function suppliersSearchAction($keyword)
    {

        return new Response("Access Denied"); 
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

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

                        "default_field" => 'slug_country',
                        "query" => $qCountry

                    )
                )
            );
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
                "tag_schema" => "styled",
                "fields" => array(
                    "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    "product_desc" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                )
            )
        );

        $query = new \Elastica\Query();

        $countryAggs = new \Elastica\Aggregation\Terms('slug_country');


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

        // $s_cache = json_encode($util->decrypted($this->getRequest()->get('s_cache')));


        $query->setRawQuery($queryDSL);

        $query->addAggregation($company_as);


        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'stglobals');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);

        // var_dump($res->getCurrentPageResults()->getAggregations()['slug_country']['buckets']);
        // die();

        $res->setMaxPerPage(20);
        $res->setCurrentPage(1);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page = 1) {
            return $this->generateUrl('member_search_shipments_result_us_imports_ajax', array('page' => $page, 's_cache' => "test"));
        }, $options);


        $params = array(

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
                'html_string' => $this->renderView('JariffProjectBundle:Search\Demo\New\Append:suppliers.html.twig', $params),

            )
        ));

    }

    /**
         * @Route(
         *   "/search-res/suppliers-form-country/{keyword}",
         *   name     = "demo_suppliers_show_filter_country_res",
         *   options={"expose"=true}
         * )
         *
         * 
         */
    public function suppliersSearchFormCountryAction($keyword)
    {
        return new Response("Access Denied"); 
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

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

        if (isset($s_cache->country)) {
            $qCountry[] = $s_cache->country[0];
            $i = 0;
            foreach ($s_cache->country as $country) {
                if ($i > 0) {
                    $qCountry[] = $country;
                }

                $i++;
            }



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
                "tag_schema" => "styled",
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

        // $s_cache = json_encode($util->decrypted($this->getRequest()->get('s_cache')));



        $query->setRawQuery($queryDSL);


        $query->addAggregation($countryAggs);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'stglobals');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);


        $country = new Country();

        if(isset($qCountry))
            $country->country = $qCountry;

        $formCountry = $this->createForm(new CountryFormType(), $country, array(
            'action' => '',
            'method' => 'POST',
            'dataCountry' => $res->getCurrentPageResults()->getAggregations()['slug_country']['buckets']
            ));

        $params = array(
            'form' => $formCountry->createView(),
            );

        $twigEx = new StringTwig;

        return new Response(json_encode(
            array(
                "success" => true,          
                'html_string' => $this->renderView('JariffProjectBundle:Search\Demo\New\Append\Col:suppliers_col_md.html.twig', $params),

                )
            ));

    }

    /**
         * @Route(
         *   "/search-res/logistics/{keyword}",
         *   name     = "demo_logistics_res",
         *   options={"expose"=true}
         * )
         *
         * 
         */
    public function logiticsSearchAction($keyword)
    {
        return new Response("Access Denied"); 
        $queryFirst = array(
            "query_string" => array(
                'default_field' => "product_desc",
                "query" => $keyword
                )
            )
        ;

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

        $query->addAggregation($company_as);
        $query->addAggregation($countryAggs);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'stglobals');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);

                // var_dump($res->getCurrentPageResults()->getAggregations()['slug_country']['buckets']);
                // die();

        $res->setMaxPerPage(20);
        $res->setCurrentPage(1);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page = 1) {
            return $this->generateUrl('member_search_shipments_result_us_imports_ajax', array('page' => $page, 's_cache' => "test"));
        }, $options);

                // $country = new Country();

                // $formCountry = $this->createForm(new CountryFormType(), $country, array(
                //     'action' => '',
                //     'method' => 'POST',
                //     'dataCountry' => $res->getCurrentPageResults()->getAggregations()['slug_country']['buckets']
                //     ));

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
                'html_string' => $this->renderView('JariffProjectBundle:Search\Demo\New\Append:logistics.html.twig', $params),

                )
            ));

    }

    /**
     * @Route(
     *   "/search-filter/submit/country/{cat}/{keyword}",
     *   name     = "demo_submit_country",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     */
    public function submitCountryAction(Request $request,$cat,$keyword)
    {
        return new Response("Access Denied"); 
        $data = $request->get('country_widgets');
        $util = new StringTwig();

        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));


        $qCountrys = array();
        if (isset($data['country'])) {

            foreach ($data['country'] as $country) {
                $qCountrys[] = $country;
            }
        }

        $s_cache = (array)$s_cache;
        if (isset($s_cache))
            unset($s_cache);

        $s_cache = array_merge(empty($s_cache) ? array() : $s_cache,
            !empty($qCountrys) ? array('country' => $qCountrys) : array());

        $s_cacheJson = json_encode($s_cache);

        $s_cache = $util->encrypted($s_cacheJson);

        return new Response(json_encode(
            array(
                'success' => true,
                'urls' => $this->generateUrl('demo_global_search_res', array('category'=>$cat,'keyword' => $keyword,'s_cache' => $s_cache)),
                'urls_res' => $this->generateUrl('demo_'. $cat.'_res', array('keyword' => $keyword,'s_cache' => $s_cache)),

                )
            )
        );

    }


    /**
     * @Route(
     *   "/search/global-importer/{keyword}",
     *   name     = "demo_global_search_show_importer"
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo:result.html.twig")
     */
    public function globalImporterSearchAction($keyword)
    {
        return new Response("Access Denied"); 
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

        if (!isset($s_cache->search)) {
            $queryFirst = array(
                "query_string" => array(
                    'query' => $keyword
                    )
                );

            $collect0 = 'all';
        } else {

            //karena ini hanya demo jadi tidakk perlu perulangan langsung pada value pertama
            $collect0 = $s_cache->search[0]->collect;

            if (preg_match('/product_description/i', $util->decrypted($this->getRequest()->get('s_cache')))) {
                $queryFirst = array(
                    "query_string" => array(
                        'query' => $keyword
                        )
                    );
            } else {

                $queryFirst = array(


//                        "query" => array(
                    "query_string" => array(

                        "default_field" =>  $collect0,
                        "query" => $keyword

                        )
//                        )


                    );


            }
        }

        $filterKeyword = $this->repo('JariffAdminBundle:FilterKeywords')->findAll();

        foreach ($filterKeyword as $key) {

            if (!isset($keywords))
                $keywords = $key->getKeyword();
            else
                $keywords .= ' or ' . $key->getKeyword();
        }

        $filter['bool']['must'][] = array(

            "query" => array(
                "query_string" => array(

                    "default_field" => 'company_as',
                    "query" => 'importer'

                    )
                )

            );


        $filter['bool']['must_not'][] = array(
            'query' => array(
                "query_string" => array(
                    'query' => $keywords
                    )
                )
            );

        $highlight = array();

        $toPacket = 'fos_elastica.finder.jariff.company_detail';

        $finder = $this->container->get($toPacket);
        $query = new \Elastica\Query();
        $countryAggs = new \Elastica\Aggregation\Terms('country');

        $countryAggs->setField('slug_country');
        $countryAggs->setSize(10);

//        $facet = new \Elastica\Facet\Terms('tags');
//        $facetCategory = new \Elastica\Facet\Terms('category');
//
//
//        $facetCategory->setField("value.company_as");
//        $facetCategory->setSize(2);
//        $facetCategory->setOrder("reverse_term");
//        $facetCategory->setNested("value");
//
//        $facet->setField("value.slug_country");
//        $facet->setSize(10);
//        $facet->setNested("value");

        $queryDSL = array_merge(array(
            "query" => array_merge(array(
                "filtered" => array_merge(array(
                    "query" => $queryFirst
                    ), (!empty($filter)) ? array("filter" => $filter) : array()
                )
                )
            )
            )
        , $highlight,
        array('from' => 0)
        );


        $query->setRawQuery($queryDSL);
//        $query->addFacet($facet);
//        $query->addFacet($facetCategory);

        $query->addAggregation($countryAggs);
        $res = $finder->findPaginated($query);

        $res->setMaxPerPage(10);
        $res->setCurrentPage(1);
        $view = new TwitterBootstrapView();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('signup');
        }, $options);

        $resHybird = $finder->findHybrid($query, 10);

        $entity = new SearchEmbed();

        $search1 = new SearchFieldCollect();
        $search1->collect = $collect0;
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = $keyword;
        $entity->getQ()->add($searchQ);


        $form = $this->createForm(new SearchEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $country = new Country();
        $country->country = array('importer','exporter');


        $formCountry = $this->createForm(new CountryFormType(), $country, array(
            'action' => '',
            'method' => 'POST',
            'dataCountry' => $res->getAdapter()->getAggregations()['country']['buckets']
            ));

        return array(
            'form' => $form->createView(),
            'res' => $res,
            'resHybird' => $resHybird,
            'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'size' => isset($size) ? $size : 10,
            'pagination' => $html,
            'formCountry' => $formCountry->createView(),
            'keyword' => $keyword
            );
    }

    /**
     * @Route(
     *   "/search/global-exporter/{keyword}",
     *   name     = "demo_global_search_show_exporter"
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo:result.html.twig")
     */
    public function globalExporterSearchAction($keyword)
    {
return new Response("Access Denied"); 
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));



        if (!isset($s_cache->search)) {
            $queryFirst = array(
                "query_string" => array(
                    'query' => $keyword
                    )
                );

            $collect0 = 'all';
        } else {
            $collect0 = $s_cache->search[0]->collect;

            if (preg_match('/product_description/i', $util->decrypted($this->getRequest()->get('s_cache')))) {
                $queryFirst = array(
                    "query_string" => array(
                        'query' => $keyword
                        )
                    );
            } else {
                $queryFirst = array(


//                        "query" => array(
                    "query_string" => array(

                        "default_field" => $collect0,
                        "query" => $keyword

                        )
//                        )


                    );
            }
        }


        $filterKeyword = $this->repo('JariffAdminBundle:FilterKeywords')->findAll();

        foreach ($filterKeyword as $key) {

            if (!isset($keywords))
                $keywords = $key->getKeyword();
            else
                $keywords .= ' or ' . $key->getKeyword();
        }

        $filter['bool']['must'][] = array(

            "query" => array(
                "query_string" => array(

                    "default_field" => 'company_as',
                    "query" => 'exporter'

                    )
                )

            );

        $filter['bool']['must_not'][] = array(
            'query' => array(
                "query_string" => array(
                    'query' => $keywords
                    )
                )
            );

        $highlight = array();


        $toPacket = 'fos_elastica.finder.jariff.company_detail';

        $finder = $this->container->get($toPacket);
        $query = new \Elastica\Query();
        $countryAggs = new \Elastica\Aggregation\Terms('country');

        $countryAggs->setField('slug_country');
        $countryAggs->setSize(10);

        $queryDSL = array_merge(array(
            "query" => array_merge(array(
                "filtered" => array_merge(array(
                    "query" => $queryFirst
                    ), (!empty($filter)) ? array("filter" => $filter) : array()
                )
                )
            )
            )
        , $highlight,
        array('from' => 0)
        );


        $query->setRawQuery($queryDSL);
        $query->addAggregation($countryAggs);
        $res = $finder->findPaginated($query);

        $res->setMaxPerPage(10);
        $res->setCurrentPage(1);
        $view = new TwitterBootstrapView();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('signup');
        }, $options);

        $resHybird = $finder->findHybrid($query, 10);

        $entity = new SearchEmbed();

        $search1 = new SearchFieldCollect();
        $search1->collect = $collect0;
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = $keyword;
        $entity->getQ()->add($searchQ);


        $form = $this->createForm(new SearchEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $country = new Country();
        $country->country = array('importer','exporter');


        $formCountry = $this->createForm(new CountryFormType(), $country, array(
            'action' => '',
            'method' => 'POST',
            'dataCountry' => $res->getAdapter()->getAggregations()['country']['buckets']
            ));

        return array(
            'form' => $form->createView(),
            'res' => $res,
            'resHybird' => $resHybird,
            'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'size' => isset($size) ? $size : 10,
            'pagination' => $html,
            'keyword' => $keyword,
            'formCountry' => $formCountry->createView()
            );
    }

    /**
     * @Route(
     *   "/search/global-product/{keyword}",
     *   name     = "demo_global_search_show_product"
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo:result.html.twig")
     */
    public function globalProductSearchAction($keyword)
    {

        return new Response("Access Denied"); 

        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

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

        $filter['bool']['must'][] = array(

            "query" => array(
                "query_string" => array(

                    "default_field" => 'company_as',
                    "query" => 'product'

                    )
                )

            );

        $filter['bool']['must'][] = array(

            "query" => array(
                "query_string" => array(

                    "default_field" => 'product_desc',
                    "query" => $keyword

                    )
                )

            );


        $filter['bool']['must_not'][] = array(
            'query' => array(
                "query_string" => array(
                    'query' => $keywords
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

//        $highlight = array();

        $toPacket = 'fos_elastica.finder.jariff.company_detail';

        $finder = $this->container->get($toPacket);
        $query = new \Elastica\Query();
        $countryAggs = new \Elastica\Aggregation\Terms('country');

        $countryAggs->setField('slug_country');
        $countryAggs->setSize(10);


        $queryDSL = array_merge(array(
            "query" => array_merge(array(
                "filtered" => array_merge(array(
                    "query" => $queryFirst
                    ), (!empty($filter)) ? array("filter" => $filter) : array()
                )
                )
            )
            )
        , $highlight,
        array('from' => 0)
        );


        $query->setRawQuery($queryDSL);

        $query->addAggregation($countryAggs);
        $res = $finder->findPaginated($query);

        $res->setMaxPerPage(10);
        $res->setCurrentPage(1);
        $view = new TwitterBootstrapView();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('signup');
        }, $options);

        $resHybird = $finder->findHybrid($query, 10);

        $entity = new SearchEmbed();

        $search1 = new SearchFieldCollect();
        $search1->collect = !isset($s_cache->search) ? 'all' : 'product_description';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = $keyword;
        $entity->getQ()->add($searchQ);


        $form = $this->createForm(new SearchEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $country = new Country();
        $country->country = array('importer','exporter');


        $formCountry = $this->createForm(new CountryFormType(), $country, array(
            'action' => '',
            'method' => 'POST',
            'dataCountry' => $res->getAdapter()->getAggregations()['country']['buckets']
            ));

        return array(
            'form' => $form->createView(),
            'res' => $res,
            'resHybird' => $resHybird,

//            'facetCategory' => $res->getAdapter()->getFacets()['category']['terms'],
            'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'size' => isset($size) ? $size : 10,
            'pagination' => $html,
            'keyword' => $keyword,
            'formCountry' => $formCountry->createView()
            );
    }

    /**
     * @Route(
     *   "/search/global-route",
     *   name     = "demo_global_search_route"
     * )
     * @Method("POST")
     *
     */
    public function globalRouteSearchAction()
    {
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));


    }

    /**
     * @Route(
     *   "/search/global-category/{keyword}",
     *   name     = "demo_global_search_show_category"
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo:result.html.twig")
     */
    public function globalCombineSearchAction($keyword)
    {
        return new Response("Access Denied"); 
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));


        if (!isset($s_cache->search)) {
            $queryFirst = array(
                "query_string" => array(
                    'query' => $keyword
                    )
                );


            $collect0 = 'all';
        } else {
            $collect0 = $s_cache->search[0]->collect;

            if (preg_match('/product_description/i', $collect0) or
                preg_match('/all/i', $collect0)
                ) {

                $queryFirst = array(
                    "query_string" => array(
                        'query' => $keyword
                        )
                    );
        } else {
            $queryFirst = array(


                "query" => array(
                    "query_string" => array(

                        "default_field" =>  $collect0,
                        "query" => $keyword

                        )
                    )


                );
        }
    }


    $filterKeyword = $this->repo('JariffAdminBundle:FilterKeywords')->findAll();

    foreach ($filterKeyword as $key) {

        if (!isset($keywords))
            $keywords = $key->getKeyword();
        else
            $keywords .= ' or ' . $key->getKeyword();
    }


    if (in_array('importer', $s_cache->category) and in_array('exporter', $s_cache->category)) {
        $filter['bool']['must_not'][] = array(

            "query" => array(
                "query_string" => array(

                    "default_field" => 'company_as',
                    "query" => 'product'

                    )
                )

            );
    }

    if (in_array('product', $s_cache->category) and in_array('exporter', $s_cache->category)) {
        $filter['bool']['must_not'][] = array(

            "query" => array(
                "query_string" => array(

                    "default_field" => 'company_as',
                    "query" => 'importer'

                    )
                )

            );
    }

    if (in_array('product', $s_cache->category) and in_array('importer', $s_cache->category)) {
        $filter['bool']['must_not'][] = array(

            "query" => array(
                "query_string" => array(

                    "default_field" => 'company_as',
                    "query" => 'exporter'

                    )
                )

            );
    }

    $filter['bool']['must_not'][] = array(
        'query' => array(
            "query_string" => array(
                'query' => $keywords
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

//        $highlight = array();

    $toPacket = 'fos_elastica.finder.jariff.company_detail';

    $finder = $this->container->get($toPacket);
    $query = new \Elastica\Query();
    $query = new \Elastica\Query();
    $countryAggs = new \Elastica\Aggregation\Terms('country');

    $countryAggs->setField('slug_country');
    $countryAggs->setSize(10);

    $queryDSL = array_merge(array(
        "query" => array_merge(array(
            "filtered" => array_merge(array(
                "query" => $queryFirst
                ), (!empty($filter)) ? array("filter" => $filter) : array()
            )
            )
        )
        )
    , $highlight,
    array('from' => 0)
    );


    $query->setRawQuery($queryDSL);

    $query->addAggregation($countryAggs);

    $res = $finder->findPaginated($query);

    $res->setMaxPerPage(10);
    $res->setCurrentPage(1);
    $view = new TwitterBootstrapView();
    $options = array('proximity' => 3);
    $html = $view->render($res, function ($page) {
        return $this->generateUrl('signup');
    }, $options);

    $resHybird = $finder->findHybrid($query, 10);

    $entity = new SearchEmbed();

    $search1 = new SearchFieldCollect();
    $search1->collect = !isset($s_cache->search) ? 'all' : $collect0;
    $entity->getCollect()->add($search1);

    $searchQ = new SearchFieldQ();
    $searchQ->q = $keyword;
    $entity->getQ()->add($searchQ);


    $form = $this->createForm(new SearchEmbedFormType(), $entity, array(
        'action' => '',
        'method' => 'POST',
        ));


    $country = new Country();
    $country->country = array('importer','exporter');


    $formCountry = $this->createForm(new CountryFormType(), $country, array(
        'action' => '',
        'method' => 'POST',
        'dataCountry' => $res->getAdapter()->getAggregations()['country']['buckets']
        ));

    return array(
        'form' => $form->createView(),
        'res' => $res,
        'resHybird' => $resHybird,

        'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
        'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
        'size' => isset($size) ? $size : 10,
        'pagination' => $html,
        'keyword' => $keyword,
        'formCountry' => $formCountry->createView()
        );


}


}

