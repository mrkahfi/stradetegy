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
use Jariff\MemberBundle\Model\SearchFieldCondition;
use Jariff\MemberBundle\Form\Demo\SearchEmbedFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;

use Pagerfanta\View\TwitterBootstrapView;

/**
 *
 * @Route("/")
 */
class CountGlobalSearchController extends BaseController
{
    /**
     * @Route(
     *   "/search/global-importer-count",
     *   name     = "demo_global_search_count_importer",
     *   options={"expose"=true}
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


        $data = $form->getData();

        if (preg_match('/product_description/i', $data->getCollect()[0]->collect) or preg_match('/all/i', $data->getCollect()[0]->collect)) {
            $queryFirst = array(
                "query_string" => array(
                    'query' => $data->getQ()[0]->q
                )
            );
        } else {
            $queryFirst = array(


//                    "query" => array(
                        "query_string" => array(

                            "default_field" => $data->getCollect()[0]->collect,
                            "query" => $data->getQ()[0]->q

                        )
//                        )

            );
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

        return new Response($res->getNbResults());
    }

    /**
     * @Route(
     *   "/search/global-exporter-count",
     *   name     = "demo_global_search_count_exporter",
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


        $data = $form->getData();

        if (preg_match('/product_description/i', $data->getCollect()[0]->collect) or preg_match('/all/i', $data->getCollect()[0]->collect)) {
            $queryFirst = array(
                "query_string" => array(
                    'query' => $data->getQ()[0]->q
                )
            );
        } else {
            $queryFirst = array(


//                    "query" => array(
                        "query_string" => array(

                            "default_field" => $data->getCollect()[0]->collect,
                            "query" => $data->getQ()[0]->q

                        )
//                    )


            );
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

        return new Response($res->getNbResults());
    }

    /**
     * @Route(
     *   "/search/global-product-count",
     *   name     = "demo_global_search_count_product",
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

        return new Response($res->getNbResults());
    }

}

