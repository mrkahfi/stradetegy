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
use Jariff\MemberBundle\Model\Country;

use Jariff\MemberBundle\Model\SearchFieldCollect;
use Jariff\MemberBundle\Model\SearchFieldQ;
use Jariff\MemberBundle\Model\SearchFieldCondition;
use Jariff\MemberBundle\Model\SearchFieldSize;
use Jariff\MemberBundle\Form\SearchGlobal\SearchEmbedFormType;
use Jariff\MemberBundle\Form\Widget\Field\CountryFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Field\FieldSizeFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

//use Jariff\AdminBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Util as Util;
use Jariff\MemberBundle\Model\SearchEmbed;
use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;

use Pagerfanta\View\TwitterBootstrapView;

/**
 *
 * @Route("/member")
 */
class SearchGlobalAjaxController extends BaseController
{

    private $s_cache_json;

    /**
     * @Route(
     *   "/search-global-result-ajax/{page}",
     *   name     = "member_search_global_result_ajax",
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
    public function resultAction($page = 1)
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

        $today = new \DateTime('now');
        $member_search_history = $this->repoMongo('JariffDocumentBundle:SearchHistory')->findBy(array(
            'user_id' => $this->getUser()->getId(),
            'date_search' => $today->format('Y-m-d')
        ));

        $member_search = $this->repo('JariffMemberBundle:MemberSubscription')->findOneBy(array(
            'member' => $this->getUser()->getId(),
            'active' => true
        ));

        $active_subscription = $this->repo('JariffMemberBundle:MemberSubscription')->findOneBy(array('member' => $this->user()->getId(), 'active' => true));

        if (is_null($active_subscription)) {
            return $this->errorRedirect('You don\'t have any active subscription. Please update your subscription.', 'dashboard_member');
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

        $countryKey = '';
        if (isset($s_cache->country)) {
            $qCountry = '';

            $i = 0;
            foreach ($s_cache->country as $country) {
                $qCountry = $qCountry . $country;
                $countryKey = $countryKey . $util->twolettercodecountry($country);

                if (count($s_cache->country) > 0 and $i < count($s_cache->country)) {
                    $qCountry = $qCountry . ' or ';
                    $countryKey = $countryKey . ', ';
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

        if (isset($s_cache->category)) {
            if (count($s_cache->category) >= 1 and count($s_cache->category) <= 2) {
                $keyCategory = '';
                foreach ($s_cache->category as $cat) {
                    if (empty($keyCategory)) {
                        $keyCategory = $cat;
                    } else {
                        $keyCategory = $keyCategory . ' or ' . $cat;
                    }
                }

                $filter['bool']['must'][] = array(
                    "query" => array(
                        "query_string" => array(

                            "default_field" => 'company_as',
                            "query" => $keyCategory

                        )
                    )
                );
            }

        }

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

        $toPacket = 'fos_elastica.finder.stradetegy.company_detail';

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
            array('from' => (($page - 1) * $size))
        );


        $query->setRawQuery($queryDSL);
        $query->addAggregation($countryAggs);

        $res = $finder->findPaginated($query);

        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrapView();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('member_search_global_result_ajax', array('page' => $page, 's_cache' => $this->s_cache_json));
        }, $options);

        $resHybird = $finder->findHybrid($query, $size);

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

        $form = $this->createForm(new SearchEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $country = new Country();
        $country->country = array('importer', 'exporter');

        $formCountry = $this->createForm(new CountryFormType(), $country, array(
            'action' => '',
            'method' => 'POST',
            'dataCountry' => $res->getAdapter()->getAggregations()['country']['buckets']
        ));

        $today = new \DateTime('now');
        if ($this->get('session')->get('timezone')) {
            $tz = new \DateTimeZone($this->get('session')->get('timezone'));
            $today = new \DateTime('now', $tz);
        }

        $diff = abs(strtotime($today->format("Y-m-d H:i:s")) - $s_cache->time);


        $maxTime = $diff / 3600;

        if ($maxTime > 1 or $maxTime <= 0.003) {
            $searchHistory = new SearchHistory();
            $searchHistory->setUserId($this->getUser()->getId());
            $searchHistory->setQuery($this->s_cache_json);
            $searchHistory->setDateSearch($s_cache->time);
            $searchHistory->setIsDownload(0);
            $searchHistory->setTimezone($this->get('session')->get('timezone'));
            $searchHistory->setTotalRow($res->getNbResults());
            $searchHistory->setSearchOn('global-search');

            $this->dm()->persist($searchHistory);
            $this->dm()->flush();

            $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')), 1);

            $timeIdentity = strtotime($today->format('YmdHis'));
            $this->s_cache_json = $util->encrypted(json_encode(array_merge($s_cache, array('time' => $timeIdentity))));

        }

        if ($res->getNbResults() == 0) {

            $inbound = new Inbound();
            $inbound->setDateCreate(new \DateTime('now'));
            $inbound->setEmail($this->getUser()->getUsername());
            $inbound->setDescription($this->s_cache_json);
            $inbound->setMember($this->getUser());
            $inbound->setVisitedPage($this->generateUrl('member_search_global_result'));

            $this->em()->persist($inbound);
            $this->em()->flush();
        }

        $check = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findBy(array('user_id' => $this->getUser()->getId()));

        foreach ($check as $checkTolist) {
            $dataChoices[$checkTolist->getCategory() . '_' . $checkTolist->getSlug()] = true;
        }

        $param = array(
            'form' => $form->createView(),
            'res' => $res,
            'resHybird' => $resHybird,
            'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'size' => isset($size) ? $size : 10,
            'pagination' => $html,
            'keyword' => 'test',
            'formCountry' => $formCountry->createView(),
            'count_search_today' => count($member_search_history),
            'count_search_value' => $member_search->getSearchValue(),
            'maxDownload' => ($member_search->getDownloadValue() - $member_search->getDownload())

        );

        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Global\Ajax:result_ajax.html.twig', $param),
                    's_cache' => $this->s_cache_json,
                    'urls' => $this->generateUrl('member_search_global_result', array('s_cache' => $this->s_cache_json)),
                    'total' => $util->ribuan($res->getNbResults()),
                    'q_country' => empty($countryKey) ? '' : ' And Filter by Countries ' . preg_replace('/or/', ', ', $countryKey)
                )

            )
        );
    }

    /**
     * @Route(
     *   "/search-global-submit-size",
     *   name     = "member_search_global_submit_size"
     * )
     * @Method("POST")
     *
     */
    public function submitSizeAction(Request $request)
    {
        $entity = new SearchFieldSize();
        $util = new StringTwig();

        $form = $this->createForm(new FieldSizeFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')), 1);

            if (isset($s_cache['size']))
                unset($s_cache['size']);

            $cache = array_merge($s_cache, array('size' => $form->getData()->getSize()));

            $s_cacheJson = json_encode($cache);

            $s_cache = $util->encrypted($s_cacheJson);

            return $this->redirectUrl('member_search_global_result_ajax', array(
                's_cache' => $s_cache
            ));

        }

        return new Response($form->getErrorsAsString());

    }

    /**
     * @Route(
     *   "/search-global-submit-country",
     *   name     = "member_search_global_submit_country",
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
    public function indexAction($page = 1)
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


        $s_cache = $util->encrypted($s_cacheJson);


        return $this->redirectUrl('member_search_global_result_ajax', array(
            's_cache' => $s_cache,
        ));

    }

    /**
     * @Route(
     *   "/search-global-show-form-country-ajax/{total}",
     *   name     = "member_show_form_global_country",
     *   defaults = {
     *     "total"  : "1"
     *   },
     *   requirements = {
     *     "total"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     *
     *
     */
    public function showCountryAction($total = 100)
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


        $active_subscription = $this->repo('JariffMemberBundle:MemberSubscription')->findOneBy(array('member' => $this->user()->getId(), 'active' => true));

        if (is_null($active_subscription)) {
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


        $countryForms = array();
        $qCountry = null;
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


//            $filter['bool']['must'][] = array(
//                "query" => array(
//                    "query_string" => array(
//
//                        "default_field" => 'slug_country',
//                        "query" => $qCountry
//
//                    )
//                )
//            );
        }

        if (isset($s_cache->category)) {
            if (count($s_cache->category) >= 1 and count($s_cache->category) <= 2) {
                $keyCategory = '';
                foreach ($s_cache->category as $cat) {
                    if (empty($keyCategory)) {
                        $keyCategory = $cat;
                    } else {
                        $keyCategory = $keyCategory . ' or ' . $cat;
                    }
                }

                $filter['bool']['must'][] = array(
                    "query" => array(
                        "query_string" => array(

                            "default_field" => 'company_as',
                            "query" => $keyCategory

                        )
                    )
                );
            }

        }


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

        $toPacket = 'fos_elastica.finder.stradetegy.company_detail';

        $finder = $this->container->get($toPacket);
        $query = new \Elastica\Query();
        $countryAggs = new \Elastica\Aggregation\Terms('country');

        $countryAggs->setField('slug_country');
        $countryAggs->setSize(100);

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

        $res->setMaxPerPage($size);

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

        $country = new Country();
        $country->country = $countryForms;

        $formCountry = $this->createForm(new CountryFormType(), $country, array(
            'action' => '',
            'method' => 'POST',
            'dataCountry' => $res->getAdapter()->getAggregations()['country']['buckets']
        ));


        $today = new \DateTime('now');

        $diff = abs(strtotime($today->format("Y-m-d H:i:s")) - $s_cache->time);


        $param = array(
            'formCountry' => $formCountry->createView(),
        );

        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Global\Ajax:form_country.html.twig', $param),
                    's_cache' => $this->s_cache_json,
                )
            )
        );
    }
}

