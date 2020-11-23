<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\Search;

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
use Jariff\MemberBundle\Model\SearchFieldCondition;
use Jariff\MemberBundle\Form\SearchGlobal\SearchEmbedFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;

use Pagerfanta\View\TwitterBootstrapView;

/**
 *
 * @Route("/member")
 */
class CountGlobalSearchController extends BaseController
{
    /**
     * @Route(
     *   "/search-global-importer-count",
     *   name     = "member_global_search_count_importer"
     * )
     * @Method("POST")
     *
     */
    public function globalImporterSearchAction(Request $request)
    {
        $entity = new SearchEmbed();

        $form = $this->createForm(new SearchEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            if (preg_match('/all/i', $data->getCollect()[0]->collect)) {
                $queryFirst = array(
                    "query_string" => array(
                        'query' => $data->getQ()[0]->q
                    )
                );
            } else {

                $queryFirst = array(
                    "query_string" => array(
                        'query' => $data->getQ()[0]->q
                    )
                );

                $filter['bool']['must'][] = array(

                            "query" => array(
                                "query_string" => array(

                                    "default_field" => $data->getCollect()[0]->collect,
                                    "query" => $data->getQ()[0]->q

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

            $i = 0;
            $j = 0;

            $condition = array();
            $q = array();
            $cache = array();

            foreach ($form->getData()->getCondition() as $key) {
                $condition[] = $key->condition;
            }

            foreach ($form->getData()->getQ() as $key) {
                $q[] = $key->q;
            }

            // mungkin ini agak muter2 tp karena angka yang dynamic dan disesuaikan dengan view
            foreach ($form->getData()->getCollect() as $key) {

                if ($j == 0) {
                    $cache['search'][] =
                        (object)array(
                            'condition' => null,
                            'collect' => $key->collect,
                            'q' => $q[$j]
                        );
                } else {
                    $cache['search'][] =
                        (object)array(
                            'condition' => $condition[$i],
                            'collect' => $key->collect,
                            'q' => $q[$j]
                        );
                    $i++;
                }

                $j++;

            }

            foreach ($cache['search'] as $s) {
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

            $toPacket = 'fos_elastica.finder.jariff.company_detail';

            $finder = $this->container->get($toPacket);
            $query = new \Elastica\Query();

            $queryDSL = array_merge(array(
                    "query" => array_merge(array(
                            "filtered" => array_merge(array(
                                    "query" => $queryFirst
                                ), (!empty($filter)) ? array("filter" => $filter) : array()
                            )
                        )
                    )
                )

            );


            $query->setRawQuery($queryDSL);
            $res = $finder->findPaginated($query);

            $result = array(
                'success' => true,
                'count' => $res->getNbResults()
            );
        } else {
            $result = array(
                'success' => false
            );
        }

        return new Response(json_encode($result));
    }

    /**
     * @Route(
     *   "/search-global-exporter-count",
     *   name     = "member_global_search_count_exporter",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     */
    public function globalExporterSearchAction(Request $request)
    {
        $entity = new SearchEmbed();

        $form = $this->createForm(new SearchEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $form->handleRequest($request);


        if ($form->isValid()) {

            $data = $form->getData();

            if (preg_match('/all/i', $data->getCollect()[0]->collect)) {
                $queryFirst = array(
                    "query_string" => array(
                        'query' => $data->getQ()[0]->q
                    )
                );
            } else {

                $queryFirst = array(
                    "query_string" => array(
                        'query' => $data->getQ()[0]->q
                    )
                );

                $filter['bool']['must'][] = array(
                            "query" => array(
                                "query_string" => array(

                                    "default_field" => $data->getCollect()[0]->collect,
                                    "query" => $data->getQ()[0]->q

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

            $i = 0;
            $j = 0;

            $condition = array();
            $q = array();
            $cache = array();

            foreach ($form->getData()->getCondition() as $key) {
                $condition[] = $key->condition;
            }

            foreach ($form->getData()->getQ() as $key) {
                $q[] = $key->q;
            }

            // mungkin ini agak muter2 tp karena angka yang dynamic dan disesuaikan dengan view
            foreach ($form->getData()->getCollect() as $key) {

                if ($j == 0) {
                    $cache['search'][] =
                        (object) array(
                            'condition' => null,
                            'collect' => $key->collect,
                            'q' => $q[$j]
                        );
                } else {
                    $cache['search'][] =
                        (object) array(
                            'condition' => $condition[$i],
                            'collect' => $key->collect,
                            'q' => $q[$j]
                        );
                    $i++;
                }

                $j++;

            }


            foreach ($cache['search'] as $s) {
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

            $toPacket = 'fos_elastica.finder.jariff.company_detail';

            $finder = $this->container->get($toPacket);
            $query = new \Elastica\Query();

            $queryDSL = array_merge(array(
                    "query" => array_merge(array(
                            "filtered" => array_merge(array(
                                    "query" => $queryFirst
                                ), (!empty($filter)) ? array("filter" => $filter) : array()
                            )
                        )
                    )
                )
            );


            $query->setRawQuery($queryDSL);
            $res = $finder->findPaginated($query);
            $result = array(
                'success' => true,
                'count' => $res->getNbResults()
            );
        } else {
            $result = array(
                'success' => false
            );
        }

        return new Response(json_encode($result));
    }

    /**
     * @Route(
     *   "/search-global-product-count",
     *   name     = "member_global_search_count_product",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     * @Template("JariffProjectBundle:Search\Demo:result.html.twig")
     */
    public function globalProductSearchAction(Request $request)
    {

        $entity = new SearchEmbed();

        $form = $this->createForm(new SearchEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {


            $data = $form->getData();

            $queryFirst = array(
                "query_string" => array(
                    'query' => $data->getQ()[0]->q
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
                                "query" => $data->getQ()[0]->q

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

            $i = 0;
            $j = 0;

            $condition = array();
            $q = array();
            $cache = array();

            foreach ($form->getData()->getCondition() as $key) {
                $condition[] = $key->condition;
            }

            foreach ($form->getData()->getQ() as $key) {
                $q[] = $key->q;
            }

            // mungkin ini agak muter2 tp karena angka yang dynamic dan disesuaikan dengan view
            foreach ($form->getData()->getCollect() as $key) {

                if ($j == 0) {
                    $cache['search'][] =
                        (object) array(
                            'condition' => null,
                            'collect' => $key->collect,
                            'q' => $q[$j]
                        );
                } else {
                    $cache['search'][] =
                        (object) array(
                            'condition' => $condition[$i],
                            'collect' => $key->collect,
                            'q' => $q[$j]
                        );
                    $i++;
                }

                $j++;

            }


            foreach ($cache['search'] as $s) {
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

                                                "default_field" =>  $s->collect,
                                                "query" => $s->q

                                            )
                                        )
                            );
                        }

                        if (preg_match('/not/i', $s->condition)) {
                            $filter['bool']['must_not'][] = array(
                                        "query" => array(
                                            "query_string" => array(

                                                "default_field" =>  $s->collect,
                                                "query" => $s->q

                                            )
                                        )
                            );
                        }
                    }
                }
                $i++;
            }

            $toPacket = 'fos_elastica.finder.jariff.company_detail';

            $finder = $this->container->get($toPacket);
            $query = new \Elastica\Query();

            $queryDSL = array_merge(array(
                    "query" => array_merge(array(
                            "filtered" => array_merge(array(
                                    "query" => $queryFirst
                                ), (!empty($filter)) ? array("filter" => $filter) : array()
                            )
                        )
                    )
                )
            );


            $query->setRawQuery($queryDSL);

            $res = $finder->findPaginated($query);
            $result = array(
                'success' => true,
                'count' => $res->getNbResults()
            );
        } else {
            $result = array(
                'success' => false
            );
        }

        return new Response(json_encode($result));
    }

}

