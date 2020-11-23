<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\ProjectBundle\Controller\Search;

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
use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;

use Pagerfanta\View\TwitterBootstrapView;

/**
 *
 * @Route("/")
 */
class SearchAjaxController extends BaseController
{


    /**
     * @Route(
     *   "/global-ajax/{keyword}/{page}",
     *   name     = "demo_search_country_global_ajax",
     *  defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     * options={"expose"=true}
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo:result_ajax.html.twig")
     */
    public function indexAction($keyword, $page = 1)
    {
        $data = $this->getRequest()->get('country_widget');
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
        if (isset($s_cache['category'])) {
            if (count($s_cache['category']) == 3) {

                return $this->redirectUrl('demo_global_search_show_all_ajax', array('keyword' => $keyword, 's_cache' => $util->encrypted($s_cacheJson)));
            }

            if (count($s_cache['category']) == 2) {

                return $this->redirectUrl('demo_global_search_show_category_ajax', array('keyword' => $keyword, 's_cache' => $util->encrypted($s_cacheJson)));
            }

            if (in_array('importer', $s_cache['category'])) {

                return $this->redirectUrl('demo_global_search_show_importer_ajax', array('keyword' => $keyword, 's_cache' => $util->encrypted($s_cacheJson)));

            }

            if (in_array('exporter', $s_cache['category'])) {

                return $this->redirectUrl('demo_global_search_show_exporter_ajax', array('keyword' => $keyword, 's_cache' => $util->encrypted($s_cacheJson)));

            }

            if (in_array('product', $s_cache['category'])) {

                return $this->redirectUrl('demo_global_search_show_product_ajax', array('keyword' => $keyword, 's_cache' => $util->encrypted($s_cacheJson)));

            }


        }
        return $this->redirectUrl('demo_global_search_show_all_ajax', array('keyword' => $keyword, 's_cache' => $util->encrypted($s_cacheJson)));

    }

    /**
     * @Route(
     *   "/search/global-ajax/{keyword}",
     *   name     = "demo_global_search_show_all_ajax",
     * options={"expose"=true}
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo:result_ajax.html.twig")
     */
    public function globalSearchAction($keyword)
    {

        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

        if (isset($s_cache->country)) {
            $qCountry = '';
            $i = 0;
            foreach ($s_cache->country as $country) {
                $qCountry = $qCountry . $country;

                if (count($s_cache->country) > 0 and $i < count($s_cache->country)) {
                    $qCountry = $qCountry . ' or ';
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
                            ), (!empty($filter)) ? array("filter" => $filter) : array(),
                            array(
                                "_cache" => true
                            )
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

        return array(
            'res' => $res,
            'resHybird' => $resHybird,
            'pagination' => $html,
        );
    }

    /**
     * @Route(
     *   "/search/global-importer-ajax/{keyword}",
     *   name     = "demo_global_search_show_importer_ajax",
     * options={"expose"=true}
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo:result_ajax.html.twig")
     */
    public function globalImporterSearchAction($keyword)
    {

        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

        if (isset($s_cache->country)) {
            $qCountry = '';
            $i = 0;
            foreach ($s_cache->country as $country) {
                $qCountry = $qCountry . $country;

                if (count($s_cache->country) > 0 and $i < count($s_cache->country)) {
                    $qCountry = $qCountry . ' or ';
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


                        "query" => array(
                            "query_string" => array(

                                "default_field" => $collect0,
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

        $res = $finder->findPaginated($query);

        $res->setMaxPerPage(10);
        $res->setCurrentPage(1);
        $view = new TwitterBootstrapView();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('signup');
        }, $options);

        $resHybird = $finder->findHybrid($query, 10);


        return array(
            'res' => $res,
            'resHybird' => $resHybird,
            'pagination' => $html,
        );
    }

    /**
     * @Route(
     *   "/search/global-exporter-ajax/{keyword}",
     *   name     = "demo_global_search_show_exporter_ajax",
     * options={"expose"=true}
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo:result_ajax.html.twig")
     */
    public function globalExporterSearchAction($keyword)
    {
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

        if (isset($s_cache->country)) {
            $qCountry = '';
            $i = 0;
            foreach ($s_cache->country as $country) {
                $qCountry = $qCountry . $country;

                if (count($s_cache->country) > 0 and $i < count($s_cache->country)) {
                    $qCountry = $qCountry . ' or ';
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


        $res = $finder->findPaginated($query);

        $res->setMaxPerPage(10);
        $res->setCurrentPage(1);
        $view = new TwitterBootstrapView();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('signup');
        }, $options);

        $resHybird = $finder->findHybrid($query, 10);

        return array(
            'res' => $res,
            'resHybird' => $resHybird,
            'pagination' => $html,
        );
    }

    /**
     * @Route(
     *   "/search/global-product-ajax/{keyword}",
     *   name     = "demo_global_search_show_product_ajax",
     * options={"expose"=true}
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo:result_ajax.html.twig")
     */
    public function globalProductSearchAction($keyword)
    {

        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

        if (isset($s_cache->country)) {
            $qCountry = '';
            $i = 0;
            foreach ($s_cache->country as $country) {
                $qCountry = $qCountry . $country;

                if (count($s_cache->country) > 0 and $i < count($s_cache->country)) {
                    $qCountry = $qCountry . ' or ';
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

        $filter['bool']['must_not'][] = array(

                    "query" => array(
                        "query_string" => array(

                            "default_field" => 'company_as',
                            "query" => 'importer or exporter'

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


        $res = $finder->findPaginated($query);

        $res->setMaxPerPage(10);
        $res->setCurrentPage(1);
        $view = new TwitterBootstrapView();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('signup');
        }, $options);

        $resHybird = $finder->findHybrid($query, 10);

        return array(
            'res' => $res,
            'resHybird' => $resHybird,
            'pagination' => $html,
        );

    }

    /**
     * @Route(
     *   "/search/global-category-ajax/{keyword}",
     *   name     = "demo_global_search_show_category_ajax",
     * options={"expose"=true}
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo:result_ajax.html.twig")
     */
    public function globalCategorySearchAction($keyword)
    {

        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));


        if (isset($s_cache->country)) {
            $qCountry = '';
            $i = 0;
            foreach ($s_cache->country as $country) {
                $qCountry = $qCountry . $country;

                if (count($s_cache->country) > 0 and $i < count($s_cache->country)) {
                    $qCountry = $qCountry . ' or ';
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



        $res = $finder->findPaginated($query);

        $res->setMaxPerPage(10);
        $res->setCurrentPage(1);
        $view = new TwitterBootstrapView();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('signup');
        }, $options);

        $resHybird = $finder->findHybrid($query, 10);

        return array(
            'res' => $res,
            'resHybird' => $resHybird,
            'pagination' => $html,
        );
    }
}