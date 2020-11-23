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
use Jariff\DocumentBundle\Document\KeywordDocument;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\AdminBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Util as Util;

use Pagerfanta\View\TwitterBootstrapView;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineODMMongoDBAdapter;
use Pagerfanta\Adapter\MandangoAdapter;

/**
 *
 * @Route("/member")
 */
class DownloadHistoryController extends BaseController
{

    /**
     * @Route(
     *   "/download-history/{page}",
     *   name     = "member_search_download",
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

        $query = $this->repo('JariffMemberBundle:ExportTools')->findBy(array(
            'member' => $this->getUser()->getId()
        ),
            array(
                "request_at" => "DESC"
            )

        );



        return $this->render('JariffMemberBundle:Download:index.html.twig', array(
            'data' => $this->paginate($query,$page,10),

        ));
    }

    /**
     * @Route(
     *   "/download-history-shipments/{page}",
     *   name     = "member_search_download_shipments",
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
    public function shipmentsAction($page = 1)
    {

//        $searchHistory = $this->repoMongo("JariffDocumentBundle:SearchHistory")->findAllOrderedByDate($this->user()->getUsername());
//        $searchHistory = $this->repoMongo("JariffDocumentBundle:SearchHistory")->createQuery();

//        $adapter = new ArrayAdapter($searchHistory);
//        $adapter = new MandangoAdapter($searchHistory);
//        $adapter = new DoctrineODMMongoDBAdapter($searchHistory);
//        $pagerfanta = new Pagerfanta($adapter);
//
//        $view = new TwitterBootstrapView();
//        $options = array('proximity' => 3);
//        $html = $view->render($pagerfanta, function ($page) {
//            return $this->generateUrl('member_search_filter_ajax', array('page' => $page));
//        }, $options);

        $qb = $this->dm()->createQueryBuilder('JariffDocumentBundle:ExportsSearchShipments')
            ->field('user_id')
            ->equals($this->user()->getId())
            ->sort('date_create', 'DESC');

//        $query = $qb->getQuery();
//        $searchHistory = $query->execute();

        $query = $qb->getQuery();

        return $this->render('JariffMemberBundle:Download:index.html.twig', array('data' => $this->paginate($query,$page,10)));
    }

    /**
     * @Route(
     *   "/download-history-global/{page}",
     *   name     = "member_search_download_global",
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
    public function globalAction($page = 1)
    {

//        $searchHistory = $this->repoMongo("JariffDocumentBundle:SearchHistory")->findAllOrderedByDate($this->user()->getUsername());
//        $searchHistory = $this->repoMongo("JariffDocumentBundle:SearchHistory")->createQuery();

//        $adapter = new ArrayAdapter($searchHistory);
//        $adapter = new MandangoAdapter($searchHistory);
//        $adapter = new DoctrineODMMongoDBAdapter($searchHistory);
//        $pagerfanta = new Pagerfanta($adapter);
//
//        $view = new TwitterBootstrapView();
//        $options = array('proximity' => 3);
//        $html = $view->render($pagerfanta, function ($page) {
//            return $this->generateUrl('member_search_filter_ajax', array('page' => $page));
//        }, $options);

        $qb = $this->dm()->createQueryBuilder('JariffDocumentBundle:ExportsSearchShipments')
            ->field('user_id')
            ->equals($this->user()->getId())
            ->sort('date_create', 'DESC');

//        $query = $qb->getQuery();
//        $searchHistory = $query->execute();

        $query = $qb->getQuery();

        return $this->render('JariffMemberBundle:Download:index.html.twig', array('data' => $this->paginate($query,$page,10)));
    }


}