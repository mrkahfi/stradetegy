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
class SearchAlertsController extends BaseController
{

    /**
     * @Route(
     *   "/search-alerts/{page}",
     *   name     = "member_search_alerts",
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

        $qb = $this->dm()->createQueryBuilder('JariffDocumentBundle:AlertsSaveGlobalSearch')
            ->field('user_id')
            ->equals($this->user()->getId())
            ->sort('date_create', 'DESC');

        $query = $qb->getQuery();

        return $this->render('JariffMemberBundle:Alerts:index.html.twig', array('data' => $this->paginate($query, $page, 10)));
    }

    /**
     * @Route(
     *   "/my-saved-search/{page}",
     *   name     = "member_search_save_search",
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
    public function saveListAction($page = 1)
    {

        $query = $this->repo('JariffMemberBundle:SavedSearch')->findBy(array(
            'member' => $this->getUser()->getId()
        ),array('date_search' => 'DESC'));


        return $this->render('JariffMemberBundle:Alerts:my_save_search.html.twig', array(
            'data' => $this->paginate($query, $page, 10),

        ));
    }

    /**
     * @Route(
     *   "/my-alerts-search/{page}",
     *   name     = "member_alerts_save_search",
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
    public function saveAlertListAction($page = 1)
    {

        $query = $this->repo('JariffMemberBundle:SavedSearch')->findBy(array(
            'member' => $this->getUser()->getId(),
            "is_alert" => 1
        ));


        return $this->render('JariffMemberBundle:Alerts:my_save_search.html.twig', array(
            'data' => $this->paginate($query, $page, 10),

        ));
    }

    /**
     * @Route(
     *   "/my-favourites/{page}",
     *   name     = "member_favourites",
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
    public function saveCompanyListAction($page = 1)
    {

        $query = $this->repo('JariffMemberBundle:SavedCompany')->findBy(array(
            'member' => $this->getUser()->getId(),'category' => 'buyers'
        ));

        $querySup = $this->repo('JariffMemberBundle:SavedCompany')->findBy(array(
            'member' => $this->getUser()->getId(),'category' => 'suppliers'
        ));


        return $this->render('JariffMemberBundle:Alerts:favorites.html.twig', array(
            'entityBuyer' => $this->paginate($query, $page, 50),'active_on' => 'buyers',
            'entitySupplier' => $this->paginate($querySup, $page, 50),
        ));
    }

}