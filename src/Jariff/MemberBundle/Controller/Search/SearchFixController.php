<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\Search;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\DocumentBundle\Document\ImportDocument;
use Jariff\DocumentBundle\Document\SearchHistory;
use Jariff\DocumentBundle\Document\KeywordHistory;
use Jariff\AdminBundle\Entity\Inbound;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\AdminBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Util as Util;

use Pagerfanta\View\TwitterBootstrapView;

/**
 *
 * @Route("/member")
 */
class SearchFixController extends BaseController
{

    /**
     * @Route(
     *   "/search",
     *   name     = "member_search"
     * )
     *
     *
     */
    public function indexAction()
    {
        $today = new \DateTime('now');
        $member_search_history = $this->repoMongo('JariffDocumentBundle:SearchHistory')->findBy(array(
            'user_id' => $this->getUser()->getId(),
            'date_search' => $today->format('Y-m-d')
        ));

        $active_subscription = $this->repo('JariffMemberBundle:MemberSubscription')->findOneBy(array('member' => $this->user()->getId(), 'active' => true));
        if (is_null($active_subscription)) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }
        return $this->render('JariffMemberBundle:Search\Result:landing_all_result.html.twig', array(
            'count_search_today' => count($member_search_history),
            'count_search_value' => $active_subscription->getSearchValue()
        ));
    }


    /**
     * @Route(
     *   "/search-result/{page}",
     *   name     = "member_search_result",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     *
     *
     */
    public function searchFixAction($page = 1)
    {


        $request = $this->getRequest();
        $q = $request->get('q');
        $collect = $request->get('collect');
        $condition = $request->get('condition');
        $size = $request->get('size');
        $size = (empty($size)) ? 10 : $size;

        $filter = array();

        $searchQuery = json_encode(array_merge(array('q' => $q, 'collect' => $collect, 'condition' => isset($condition) ? $condition : array())));

        $today = new \DateTime('now');
        if ($this->get('session')->get('timezone')) {
            $tz = new \DateTimeZone($this->get('session')->get('timezone'));
            $today = new \DateTime('now', $tz);
        }


        $member_search = $this->repo('JariffMemberBundle:MemberSubscription')->findOneBy(array(
            'member' => $this->getUser()->getId(),
            'active' => true
        ));


        if(!$member_search){
          return $this->errorRedirect('Currently there is no active subscription','member_search');
        }


        $member_search_history = $this->repoMongo('JariffDocumentBundle:SearchHistory')->findBy(array(
            'user_id' => $this->getUser()->getId(),
            'date_search' => $today->format('Y-m-d')
        ));

        if (count($member_search_history) == $member_search->getSearchValue())
            return $this->redirectUrl('member_search');

        $timeIdentity = strtotime($today->format('YmdHis'));
        foreach ($q as $key) {
            $keyWordHistory = new KeywordHistory();
            $keyWordHistory->setUserId($this->getUser()->getId());
            $keyWordHistory->setKeyword($key);
            $keyWordHistory->setIdentity($timeIdentity);
            $keyWordHistory->setSlug('');
            $this->dm()->persist($keyWordHistory);
        }

        $this->session()->set('set_identity', $timeIdentity);
        $this->dm()->flush();


        $queryFirst = array(
            "query_string" => array(
                'query' => Util\Util::slugify($q[0])
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
            )
            , $highlight,
            array('from' => (($page - 1) * $size))
        );


        $query->setRawQuery($queryDSL);
        $query->addFacet($facet);
        $query->addFacet($facetCategory);


        $res = $finder->findPaginated($query);

        if ($res->getNbResults() == 0) {

            $inbound = new Inbound();
            $inbound->setDateCreate(new \DateTime('now'));
            $inbound->setEmail($this->getUser()->getUsername());
            $inbound->setDescription($searchQuery);
            $inbound->setMember($this->getUser());
            $inbound->setVisitedPage($this->generateUrl('member_search_result'));

            $this->em()->persist($inbound);
            $this->em()->flush();
        }

        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrapView();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('member_search_filter_ajax', array('page' => $page));
        }, $options);

        $resHybird = $finder->findHybrid($query, $size);

        $checkSession = $this->session()->get('cacheChoice');
        $check = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findBy(array('user_id' => $this->getUser()->getId()));

        foreach ($check as $checkTolist) {
            $dataChoices[$checkTolist->getCategory() . '_' . $checkTolist->getSlug()] = true;
        }

        $searchHistory = new SearchHistory();
        $searchHistory->setUserId($this->getUser()->getId());
        $searchHistory->setQuery($searchQuery);
        $searchHistory->setDateSearch($today->format('Y-m-d'));
        $searchHistory->setIsDownload(0);
        $searchHistory->setTimezone($this->get('session')->get('timezone'));
        $searchHistory->setTotalRow($res->getNbResults());

        $this->dm()->persist($searchHistory);
        $this->dm()->flush();

        return $this->render('JariffMemberBundle:Search\Result:all_result.html.twig', array(
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
            'pagination' => $html,
            'count_search_today' => count($member_search_history),
            'count_search_value' => $member_search->getSearchValue(),
            'maxDownload' => ($member_search->getDownloadValue() - $member_search->getDownload())

        ));


    }

    /**
     * @Route(
     *   "/search-fix-ajax",
     *   name     = "member_search_fix_ajax",
     *  options={"expose"=true}
     * )
     *
     *
     */
    public function searchFixAjaxAction()
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
                'query' => Util\Util::slugify($q[0])
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
//        else {
//            $queryFirst = array(
//                "nested" => array(
//                    "path" => "value",
//                    "query" => array(
//                        "query_string" => array(
//                            "default_field" => $this->nameOfField($collect[0]),
//                            "query" => $q[0]
//                        )
//                    )
//                )
//            );
        // diincludkan dalam kondisi tertentu khusus untuk collect importer maupun exporter
//            if ($this->nameOfField($collect[0]) == 'value.company_name' or $this->nameOfField($collect[0]) == 'value.company_address') {
//                $filter['bool']['should'][] = array(
//                    "query" => array(
//                        "nested" => array(
//                            "path" => "value",
//                            "query" => array(
//                                "query_string" => array(
//                                    "default_field" => "value.company_as",
//                                    "query" => preg_split('/\_/', $collect[0])[0]
//                                )
//                            )
//                        )
//                    )
//                );
//            }
//        }
//        end


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
            $dataChoices[$checkTolist->getSlug()] = true;
        }


        return $this->render('JariffMemberBundle:Search\Result:result_ajax.html.twig', array(
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
     *   "/append_form",
     *   name     = "member_search_fix_append_form",
     *   options={"expose"=true}
     * )
     * @Method("GET")
     *
     */
    public function appendFormAction(Request $request)
    {
        return $this->render('JariffMemberBundle:Search:append-form-search-shows.html.twig');
    }


    public function nameOfField($collect)
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

