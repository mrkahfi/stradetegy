<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\Stats;

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
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineODMMongoDBAdapter;
use Pagerfanta\Adapter\MandangoAdapter;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ElasticaAdapter;
use Elastica\Index;
use Elastica\Query as QueryEs;
use Elastica\Client as ClientEs;
use Pagerfanta\View\TwitterBootstrap3View;

/**
 *
 * @Route("/member")
 */
class SearchHistoryController extends BaseController
{

    /**
     * @Route(
     *   "/search-history/{page}",
     *   name     = "member_search_history",
     *  defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   }
     * )
     *
     *
     */
    public function indexAction($page = 1)
    {
        
        $filterBy = $this->get('request')->get('filter');

        $client = new ClientEs(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'trade-user-logs-*');
        $size = 10;

        $query = new QueryEs();


        if (empty($filterBy)) {

            $queryDsl = array(
                "query" => array(
                    "bool" => array(
                        "must" => array(
                            "match" => array(
                                "user_id" => $this->getUser()->getId()
                            )
                        )
                    )

                ),
                "sort" => array(
                    array(
                        "date_search" => array(
                            "order" => "desc"
                        )
                    )
                )
            );
        } else {
            $queryDsl = array(
                "query" => array(
                    "bool" => array(
                        "must" => array(
                            "match" => array(
                                "user_id" => $this->getUser()->getId()
                            )
                        )
                    )

                ),
                "sort" => array(
                    array(
                        "priority" => array(
                            "order" => "desc"
                        )
                    )
                )
            );


        }

        // echo json_encode($queryDsl);
        // die();

        $aggsDateHistogramViewMonth = new \Elastica\Aggregation\DateHistogram("date_histogram_view_month", "date_search", "day");
        $query->setRawQuery($queryDsl);

        $aggsDateHistogramViewMonth->setOrder("_key", "desc");

        $query->addAggregation($aggsDateHistogramViewMonth);

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);

        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('member_search_history', array('page' => $page));
        }, $options);

        $charts[] = array('Day', 'Total Shipments');
        foreach ($res->getCurrentPageResults()->getAggregations()['date_histogram_view_month']['buckets'] as $googleChart) {
            if (count($charts) == 8) {
                break;
            }

            if ($googleChart['key'] > 0) {
                $charts[] = array(date("d M Y", strtotime($googleChart['key_as_string'])), $googleChart['doc_count']);
            } else {
                $charts[] = array('undefined', $googleChart['doc_count']);
            }

        }




        return $this->render('JariffMemberBundle:Stats:index.html.twig',
            array(
                'data' => $res->getCurrentPageResults(),
                'pagination' => $html,
                "total" => $res->getNbResults(),
                'chart' => json_encode($charts)

            )
        );
    }

    /**
     * @Route(
     *   "/search-history-total",
     *   name     = "member_search_history_total"
     * )
     *
     *
     */
    public function total()
    {
//        $tz = new \DateTimeZone($this->get('session')->get('timezone'));
        $today = new \DateTime('now');
         if ($this->get('session')->get('timezone')) {
            $tz = new \DateTimeZone($this->get('session')->get('timezone'));
            $today = new \DateTime('now', $tz);
        }

        $lastWeek = strtotime('-7 day', strtotime($today->format("Y-m-d")));

        $qb = $this->dm()->createQueryBuilder('JariffDocumentBundle:SearchHistory')
            ->field('user_id')
            ->equals($this->user()->getId())
            ->map('function() { emit(this.date_search, 1); }')
            ->reduce('function(k, vals) {
        var sum = 0;
        for (var i in vals) {
            sum += vals[i];
        }
        return sum;
    }')->where("function() { return this.date_search >= '" . date("Y-m-d", $lastWeek) . "'; }");


        $query = $qb->getQuery();
        $searchCount = $query->execute();

        $charts[] = array('Date', 'Total');
        foreach ($searchCount as $row) {
            $charts[] = array(date('d M Y', strtotime($row['_id'])), $row['value']);
        }

        return new Response(json_encode($charts));

    }

    /**
     * @Route(
     *   "/set-priority/{index}/{type}/{id}/{pin}/{priority}",
     *   name     = "member_search_history_pin",
     *   options={"expose"=true}
     * )
     *
     *
     */
    public function setPriorityAction($index, $type, $id, $pin, $priority = 0)
    {
        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $history = array(
            "priority" => $priority,
            "set_pin" => $pin
        );


        $doc[] = new \Elastica\Document($id, $history, $type, $index);

        $client->updateDocuments($doc);

        return new Response(1);

    }

}