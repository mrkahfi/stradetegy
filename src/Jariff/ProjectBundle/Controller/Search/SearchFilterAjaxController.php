<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\ProjectBundle\Controller\Search;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\DocumentBundle\Document\ImportDocument;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\AdminBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Util as Util;
use Pagerfanta\View\TwitterBootstrapView;

/**
 *
 * @Route("/")
 */
class SearchFilterAjaxController extends BaseController
{


    /**
     * @Route(
     *   "/global-search-filter-ajax/{page}",
     *   name     = "demo_search_filter_ajax",
     *  defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     * options={"expose"=true}
     * )
     *
     *
     */
    public function indexAction($page = 1)
    {
        $request = $this->getRequest();
        $q = $request->get('q');
        $collect = $request->get('collect');
        $condition = $request->get('condition');
        $filter_left = $request->get('filter_left_bar');
        $filCountry = $request->get('country_filter');
        $product = false;
        $export = true;
        $size = $request->get('size');
        $size = (empty($size)) ? 10 : $size;

        $filter = array();

        $queryFirst = array(
            "query_string" => array(
                'query' => Util\Util::slugify($q[0])
            )
        );

        if (!empty($filter_left)) {
            $filter_left_bar = preg_split("/ or /", $filter_left);
//            if ($filter_left_bar[3] > 0)
//                $perPage = $filter_left_bar[3];

            $export = preg_match("/exporter/i", $filter_left);

            if (preg_match("/product/i", $filter_left))
                $product = true;

            $filter_left = 'exporter or ' . $filter_left;
//                $filter_left = preg_replace('/ or product/', '', $filter_left);
//
//                if (!$export) {
//                    $filter['bool']['must'][] = array(
//                        "query" => array(
//                            "nested" => array(
//                                "path" => "value.companyjoin",
//                                "query" => array(
//                                    "query_string" => array(
//
//                                        "default_field" => 'value.companyjoin.product_description',
//                                        "query" => $q[0]
//
//                                    )
//                                )
//                            )
//                        )
//                    );
//                }
//            }

            $filter['bool']['must'][] = array(
                "query" => array(
                    "nested" => array(
                        "path" => "value",
                "query" => array(
                    "query_string" => array(

                        "default_field" => 'value.company_as',
                        "query" => $filter_left

                    )
                )
                    )
                )
            );

        } else {
            return new Response('To show data please choice category');
        }

        if ($filCountry != '') {
            $filSendToView = preg_split("/ or /", $filCountry);

            $countryChecked = array();
//            foreach ($filSendToView as $fstv) {
            $filter['bool']['should'][] = array(
                "query" => array(
                    "nested" => array(
                        "path" => "value",
                "query" => array(
                    "query_string" => array(

                        "default_field" => 'value.slug_country',
                        "query" => $filCountry

                    )
                )
                    )
                )
            );
//            }
        }


//        digunkan untuk mengisi kondisi di awal sebagai kunci utama
//        start here
        $i = 0;
        if ($collect[0] != 'all') {
            $filter['bool']['must'][] = array(
                "query" => array(
                    "nested" => array(
                        "path" => (preg_match('/value/i', $collect[0])) ? "value" : "value",
                "query" => array(
                    "query_string" => array(

                        "default_field" => $this->nameOfField($collect[0]),
                        "query" => Util\Util::slugify($q[0])

                    )
                )
                    )
                )
            );
            $i = 1;
        }


        if (!empty($condition)) {
            foreach ($condition as $cond) {
                if ($cond == 'and') {
                    if ($collect[$i] == 'all') {
                        $filter['bool']['must'][] = array(
                            'query' => array(
                                "query_string" => array(
                                    'query' => Util\Util::slugify($q[$i])
                                )
                            )
                        );
                    } else {

                        if ($this->nameOfField($collect[$i]) == 'value.company_name' or $this->nameOfField($collect[$i]) == 'value.company_address' or $collect[$i] == "value.country") {
                            $filter['bool']['must'][] = array(
                                "query" => array(
                                    "nested" => array(
                                        "path" => "value",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => $this->nameOfField($collect[$i]),
                                        "query" => Util\Util::slugify($q[$i])
                                    )
                                )
                                    )
                                )
                            );
                        } else {
                            $filter['bool']['must'][] = array(
                                "query" => array(
                                    "nested" => array(
                                        "path" => "value.companyjoin",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => $this->nameOfField($collect[$i]),
                                        "query" => Util\Util::slugify($q[$i])
                                    )
                                )
                                    )
                                )
                            );
                        }


                    }
                }

                if ($cond == 'or') {
                    if ($collect[$i] == 'all') {
                        $filter['bool']['should'][] = array(
                            'query' => array(
                                "query_string" => array(
                                    'query' => Util\Util::slugify($q[$i])
                                )
                            )
                        );
                    } else {

                        if ($this->nameOfField($collect[$i]) == 'value.company_name' or $this->nameOfField($collect[$i]) == 'value.company_address' or $collect[$i] == "value.country") {
                            $filter['bool']['should'][] = array(
                                "query" => array(
                                    "nested" => array(
                                        "path" => "value",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => $this->nameOfField($collect[$i]),
                                        "query" => Util\Util::slugify($q[$i])
                                    )
                                )
                                    )
                                )
                            );
                        } else {
                            $filter['bool']['should'][] = array(
                                "query" => array(
                                    "nested" => array(
                                        "path" => "value.companyjoin",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => $this->nameOfField($collect[$i]),
                                        "query" => Util\Util::slugify($q[$i])
                                    )
                                )
                                    )
                                )
                            );
                        }


                    }
                }

                if ($cond == 'not') {
                    if ($collect[$i] == 'all') {
                        $filter['bool']['must_not'][] = array(
                            'query' => array(
                                "query_string" => array(
                                    'query' => Util\Util::slugify($q[$i])
                                )
                            )
                        );
                    } else {

                        if ($this->nameOfField($collect[$i]) == 'value.company_name' or $this->nameOfField($collect[$i]) == 'value.company_address' or $collect[$i] == "value.country") {
                            $filter['bool']['must_not'][] = array(
                                "query" => array(
                                    "nested" => array(
                                        "path" => "value",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => $this->nameOfField($collect[$i]),
                                        "query" => Util\Util::slugify($q[$i])
                                    )
                                )
                                    )
                                )
                            );
                        } else {

                            $filter['bool']['must_not'][] = array(
                                "query" => array(
                                    "nested" => array(
                                        "path" => "value.companyjoin",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => $this->nameOfField($collect[$i]),
                                        "query" => Util\Util::slugify($q[$i])
                                    )
                                )
                                    )
                                )
                            );
                        }
                    }
                }

                $i++;
            }

        }

        $filterKeyword = $this->repo('JariffAdminBundle:FilterKeywords')->findAll();

        foreach ($filterKeyword as $key) {

            if (!isset($keyword))
                $keyword = $key->getKeyword();
            else
                $keyword .= ' or ' . $key->getKeyword();
        }


        $filter['bool']['must_not'][] = array(
            'query' => array(
                "query_string" => array(
                    'query' => $keyword
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
                    "value.product_description" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                )
            )
        );

        $toPacket = 'fos_elastica.finder.jariff.all_data';

        $finder = $this->container->get($toPacket);
        $query = new \Elastica\Query();
        $facet = new \Elastica\Facet\Terms('tags');
        $facetCategory = new \Elastica\Facet\Terms('category');


        $facetCategory->setField("value.company_as");
        $facetCategory->setSize(2);
        $facetCategory->setOrder("reverse_term");
        $facetCategory->setNested("value");

        $facet->setField("value.slug_country");
        $facet->setSize(10);
        $facet->setNested("value");

        $queryDSL = array_merge(array(
                "query" => array_merge(array(
                        "filtered" => array_merge(array(
                                "query" => $queryFirst
                            ), (!empty($filter)) ? array("filter" => $filter) : array()
                        )
                    )
                )
            ),
            ($product) ? $highlight : array(),
            array('from' => (($page - 1) * $size))
        );


        $query->setRawQuery($queryDSL);
        $query->addFacet($facet);
        $query->addFacet($facetCategory);



        $res = $finder->findPaginated($query);

        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);

        $view = new TwitterBootstrapView();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('demo_search_filter_ajax', array('page' => $page));
        }, $options);

        $resHybird = $finder->findHybrid($query, $size);


        return $this->render('JariffProjectBundle:Search\Result:result_ajax.html.twig', array(
            'resInDb' => $res,
            'resHybird' => $resHybird,
            'facetCountry' => $res->getAdapter()->getFacets()['tags']['terms'],
            'facetCategory' => $res->getAdapter()->getFacets()['category']['terms'],
            'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'q' => isset($q) ? $q : array(),
            'collect' => isset($collect) ? $collect : array(),
            'condition' => isset($condition) ? $condition : array(),
            'size' => isset($size) ? $size : 10,
            'export' => $export,
            'pagination' => $html
        ));


    }

    /**
     * @Route(
     *   "/global-search-fix-ajax",
     *   name     = "demo_search_fix_ajax",
     * options={"expose"=true}
     * )
     *
     *
     */
    public
    function searchFixAjaxAction()
    {
        $request = $this->getRequest();
        $q = $request->get('q');
        $collect = $request->get('collect');
        $condition = $request->get('condition');

//        var_dump($q);
//        var_dump($collect);
//        var_dump($condition);

        $filter = array();

        $queryFirst = array(
            "query_string" => array(
                'query' => $q[0]
            )
        );
//        digunkan untuk mengisi kondisi di awal sebagai kunci utama
//        start here
        $i = 0;
        if ($collect[0] != 'all') {
            $filter['bool']['must'][] = array(
                "query" => array(
                    "nested" => array(
                        "path" => (preg_match('/value.companyjoin/i', $collect[0])) ? "value.companyjoin" : "value",
                        "query" => array(
                            "query_string" => array(

                                "default_field" => $this->nameOfField($collect[0]),
                                "query" => Util\Util::slugify($q[0])

                            )
                        )
                    )
                )
            );
            $i = 1;
        }

        $filter['bool']['must'][] = array(
            "query" => array(
                "nested" => array(
                    "path" => "value.companyjoin",
                    "query" => array(
                        "query_string" => array(

                            "default_field" => 'value.companyjoin.product_description',
                            "query" => Util\Util::slugify($q[0])

                        )
                    )
                )
            )
        );

        if (!empty($condition)) {
            foreach ($condition as $cond) {
                if ($cond == 'and') {
                    if ($collect[$i] == 'all') {
                        $filter['bool']['must'][] = array(
                            'query' => array(
                                "query_string" => array(
                                    'query' => Util\Util::slugify($q[$i])
                                )
                            )
                        );
                    } else {

                        if ($this->nameOfField($collect[$i]) == 'value.company_name' or $this->nameOfField($collect[$i]) == 'value.company_address' or $collect[$i] == "value.country") {
                            $filter['bool']['must'][] = array(
                                "query" => array(
                                    "nested" => array(
                                        "path" => "value",
                                        "query" => array(
                                            "query_string" => array(
                                                "default_field" => $this->nameOfField($collect[$i]),
                                                "query" => Util\Util::slugify($q[$i])
                                            )
                                        )
                                    )
                                )
                            );
                        } else {
                            $filter['bool']['must'][] = array(
                                "query" => array(
                                    "nested" => array(
                                        "path" => "value.companyjoin",
                                        "query" => array(
                                            "query_string" => array(
                                                "default_field" => $this->nameOfField($collect[$i]),
                                                "query" => Util\Util::slugify($q[$i])
                                            )
                                        )
                                    )
                                )
                            );
                        }


                    }
                }

                if ($cond == 'or') {
                    if ($collect[$i] == 'all') {
                        $filter['bool']['should'][] = array(
                            'query' => array(
                                "query_string" => array(
                                    'query' => Util\Util::slugify($q[$i])
                                )
                            )
                        );
                    } else {

                        if ($this->nameOfField($collect[$i]) == 'value.company_name' or $this->nameOfField($collect[$i]) == 'value.company_address' or $collect[$i] == "value.country") {
                            $filter['bool']['should'][] = array(
                                "query" => array(
                                    "nested" => array(
                                        "path" => "value",
                                        "query" => array(
                                            "query_string" => array(
                                                "default_field" => $this->nameOfField($collect[$i]),
                                                "query" => Util\Util::slugify($q[$i])
                                            )
                                        )
                                    )
                                )
                            );
                        } else {
                            $filter['bool']['should'][] = array(
                                "query" => array(
                                    "nested" => array(
                                        "path" => "value.companyjoin",
                                        "query" => array(
                                            "query_string" => array(
                                                "default_field" => $this->nameOfField($collect[$i]),
                                                "query" => Util\Util::slugify($q[$i])
                                            )
                                        )
                                    )
                                )
                            );
                        }


                    }
                }

                if ($cond == 'not') {
                    if ($collect[$i] == 'all') {
                        $filter['bool']['must_not'][] = array(
                            'query' => array(
                                "query_string" => array(
                                    'query' => Util\Util::slugify($q[$i])
                                )
                            )
                        );
                    } else {

                        if ($this->nameOfField($collect[$i]) == 'value.company_name' or $this->nameOfField($collect[$i]) == 'value.company_address' or $collect[$i] == "value.country") {
                            $filter['bool']['must_not'][] = array(
                                "query" => array(
                                    "nested" => array(
                                        "path" => "value",
                                        "query" => array(
                                            "query_string" => array(
                                                "default_field" => $this->nameOfField($collect[$i]),
                                                "query" => Util\Util::slugify($q[$i])
                                            )
                                        )
                                    )
                                )
                            );
                        } else {

                            $filter['bool']['must_not'][] = array(
                                "query" => array(
                                    "nested" => array(
                                        "path" => "value.companyjoin",
                                        "query" => array(
                                            "query_string" => array(
                                                "default_field" => $this->nameOfField($collect[$i]),
                                                "query" => Util\Util::slugify($q[$i])
                                            )
                                        )
                                    )
                                )
                            );
                        }
                    }
                }

                $i++;
            }

        }

        $filterKeyword = $this->repo('JariffAdminBundle:FilterKeywords')->findAll();

        foreach ($filterKeyword as $key) {

            if (!isset($keyword))
                $keyword = $key->getKeyword();
            else
                $keyword .= ' or ' . $key->getKeyword();
        }


        $filter['bool']['must_not'][] = array(
            'query' => array(
                "query_string" => array(
                    'query' => $keyword
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
                    "value.companyjoin.product_description" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                )
            )
        );

        $toPacket = 'fos_elastica.finder.jariff.all_data';

        $finder = $this->container->get($toPacket);
        $query = new \Elastica\Query();
        $facet = new \Elastica\Facet\Terms('tags');
        $facetCategory = new \Elastica\Facet\Terms('category');


        $facetCategory->setField("value.company_as");
        $facetCategory->setSize(2);
        $facetCategory->setOrder("reverse_term");
        $facetCategory->setNested("value");

        $facet->setField("value.slug_country");
        $facet->setSize(10);
        $facet->setNested("value");

        $queryDSL = array_merge(array(
                "query" => array_merge(array(
                        "filtered" => array_merge(array(
                                "query" => $queryFirst
                            ), (!empty($filter)) ? array("filter" => $filter) : array()
                        )
                    )
                )
            )
            , $highlight
        );

        $query->setRawQuery($queryDSL);
        $query->addFacet($facet);
        $query->addFacet($facetCategory);

        $res = $finder->findPaginated($query);


        $resHybird = $finder->findHybrid($query);
        $checkSession = $this->session()->get('cacheChoice');
        $check = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findBy(array('session' => $checkSession, 'category' => 'product'));

        foreach ($check as $checkTolist) {
            $dataChoices[$checkTolist->getCategory() . '_' . $checkTolist->getSlug()] = true;
        }

        return $this->render('JariffProjectBundle:Search\Result:result_ajax.html.twig', array(
            'resInDb' => $res,
            'resHybird' => $resHybird,
            'facetCountry' => $res->getAdapter()->getFacets()['tags']['terms'],
            'facetCategory' => $res->getAdapter()->getFacets()['category']['terms'],
            'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'q' => isset($q) ? $q : array(),
            'collect' => isset($collect) ? $collect : array(),
            'condition' => isset($condition) ? $condition : array(),
            'size' => isset($size) ? $size : 10,


        ));


    }

    /**
     * @Route(
     *   "/global-search-fix-total-product",
     *   name     = "demo_search_fix_ajax_total_product",
     *  options={"expose"=true}
     * )
     *
     *
     */
    public
    function totalProductAction()
    {
        $request = $this->getRequest();
        $q = $request->get('q');
        $collect = $request->get('collect');
        $condition = $request->get('condition');
        $product = false;

        $filter = array();


        $filter['bool']['must'][] = array(
            "query" => array(
                "nested" => array(
                    "path" => "value",
            "query" => array(
                "query_string" => array(

                    "default_field" => 'value.company_as',
                    "query" => 'exporter'

                )
            )
                )
            )
        );

        $queryFirst = array(
            "nested" => array(
                "path" => "value",
                "query" => array(
            "query_string" => array(

                "default_field" => 'value.product_description',
                "query" => Util\Util::slugify($q[0])

            )
                )
            )
        );
//        digunkan untuk mengisi kondisi di awal sebagai kunci utama
//        start here
        $i = 0;
        if ($collect[0] != 'all') {
            $filter['bool']['must'][] = array(
                "query" => array(
                    "nested" => array(
                        "path" => "value",
                "query" => array(
                    "query_string" => array(

                        "default_field" => $this->nameOfField($collect[0]),
                        "query" => Util\Util::slugify($q[0])

                    )
                )
                    )
                )
            );
            $i = 1;
        }


        if (!empty($condition)) {
            foreach ($condition as $cond) {
                if ($cond == 'and') {
                    if ($collect[$i] == 'all') {
                        $filter['bool']['must'][] = array(
                            'query' => array(
                                "query_string" => array(
                                    'query' => Util\Util::slugify($q[$i])
                                )
                            )
                        );
                    } else {

                        if ($this->nameOfField($collect[$i]) == 'value.company_name' or $this->nameOfField($collect[$i]) == 'value.company_address' or $collect[$i] == "value.country") {
                            $filter['bool']['must'][] = array(
//                                "query" => array(
//                                    "nested" => array(
//                                        "path" => "value",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => $this->nameOfField($collect[$i]),
                                        "query" => Util\Util::slugify($q[$i])
                                    )
                                )
//                                    )
//                                )
                            );
                        } else {
                            $filter['bool']['must'][] = array(
//                                "query" => array(
//                                    "nested" => array(
//                                        "path" => "value.companyjoin",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => $this->nameOfField($collect[$i]),
                                        "query" => Util\Util::slugify($q[$i])
                                    )
                                )
//                                    )
//                                )
                            );
                        }


                    }
                }

                if ($cond == 'or') {
                    if ($collect[$i] == 'all') {
                        $filter['bool']['should'][] = array(
                            'query' => array(
                                "query_string" => array(
                                    'query' => Util\Util::slugify($q[$i])
                                )
                            )
                        );
                    } else {

                        if ($this->nameOfField($collect[$i]) == 'value.company_name' or $this->nameOfField($collect[$i]) == 'value.company_address' or $collect[$i] == "value.country") {
                            $filter['bool']['should'][] = array(
//                                "query" => array(
//                                    "nested" => array(
//                                        "path" => "value",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => $this->nameOfField($collect[$i]),
                                        "query" => Util\Util::slugify($q[$i])
                                    )
                                )
//                                    )
//                                )
                            );
                        } else {
                            $filter['bool']['should'][] = array(
//                                "query" => array(
//                                    "nested" => array(
//                                        "path" => "value.companyjoin",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => $this->nameOfField($collect[$i]),
                                        "query" => Util\Util::slugify($q[$i])
                                    )
                                )
//                                    )
//                                )
                            );
                        }


                    }
                }

                if ($cond == 'not') {
                    if ($collect[$i] == 'all') {
                        $filter['bool']['must_not'][] = array(
                            'query' => array(
                                "query_string" => array(
                                    'query' => Util\Util::slugify($q[$i])
                                )
                            )
                        );
                    } else {

                        if ($this->nameOfField($collect[$i]) == 'value.company_name' or $this->nameOfField($collect[$i]) == 'value.company_address' or $collect[$i] == "value.country") {
                            $filter['bool']['must_not'][] = array(
//                                "query" => array(
//                                    "nested" => array(
//                                        "path" => "value",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => $this->nameOfField($collect[$i]),
                                        "query" => Util\Util::slugify($q[$i])
                                    )
                                )
//                                    )
//                                )
                            );
                        } else {

                            $filter['bool']['must_not'][] = array(
//                                "query" => array(
//                                    "nested" => array(
//                                        "path" => "value.companyjoin",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => $this->nameOfField($collect[$i]),
                                        "query" => Util\Util::slugify($q[$i])
                                    )
                                )
//                                    )
//                                )
                            );
                        }
                    }
                }

                $i++;
            }

        }

        $filterKeyword = $this->repo('JariffAdminBundle:FilterKeywords')->findAll();

        foreach ($filterKeyword as $key) {

            if (!isset($keyword))
                $keyword = $key->getKeyword();
            else
                $keyword .= ' or ' . $key->getKeyword();
        }


        $filter['bool']['must_not'][] = array(
            'query' => array(
                "query_string" => array(
                    'query' => $keyword
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
                    "value.product_description" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                )
            )
        );

        $toPacket = 'fos_elastica.finder.jariff.all_data';

        $finder = $this->container->get($toPacket);
        $query = new \Elastica\Query();
        $facet = new \Elastica\Facet\Terms('tags');
        $facetCategory = new \Elastica\Facet\Terms('category');


        $facetCategory->setField("value.company_as");
        $facetCategory->setSize(2);
        $facetCategory->setOrder("reverse_term");
        $facetCategory->setNested("value");

        $facet->setField("value.slug_country");
        $facet->setSize(10);
        $facet->setNested("value");

        $queryDSL = array_merge(array(
                "query" => array_merge(array(
                        "filtered" => array_merge(array(
                                "query" => $queryFirst
                            ), (!empty($filter)) ? array("filter" => $filter) : array()
                        )
                    )
                )
            ),
            ($product) ? $highlight : array()
        );

        $query->setRawQuery($queryDSL);
        $query->addFacet($facet);
        $query->addFacet($facetCategory);

        $res = $finder->findPaginated($query);


        return new Response($res->getNbResults());


    }

    public
    function nameOfField($collect)
    {
        if ($collect == 'importer_name' or $collect == 'exporter_name') {
            return "value.company_name";
        }

        if ($collect == 'importer_address' or $collect == 'exporter_adress') {
            return "value.company_address";
        }

        return $collect;
    }

}