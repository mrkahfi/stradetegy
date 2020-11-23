<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\Search;

use Jariff\DocumentBundle\Document\KeywordHistory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\DocumentBundle\Document\ImportDocument;
use Jariff\DocumentBundle\Document\KeywordDocument;
use Jariff\DocumentBundle\Document\CompanyDetail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\AdminBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Util as Util;

/**
 * @Route("/")
 */
class SearchDetailController extends BaseController
{

    // /**
    //  * @Route(
    //  *   "/member/importer/{slug}",
    //  *   name     = "member_importer_detail"
    //  * )
    //  */
    // public function importerDetailAction($slug)
    // {
    //     $identify = $this->session()->get('set_identity');

    //     $keywordHistory = $this->repoMongo("JariffDocumentBundle:KeywordHistory")->findOneBy(array('user_id' => $this->user()->getId()));

    //     if (!empty($keywordHistory)) {
    //     $keywordHistory->setSlug($slug);
    //     $this->dm()->persist($keywordHistory);
    //     $this->dm()->flush(); }

    //     $data = $this->repoMongo("JariffDocumentBundle:AllData")->findOneBy(array('value.slug_company' => $slug));

    //     return $this->render('JariffMemberBundle:Search/Detail:importer_detail.html.twig', array('data' => $data));
    // }


    // /**
    //  * @Route(
    //  *   "/member/importer/{slug}",
    //  *   name     = "member_importer_detail"
    //  * )
    //  */
    // public function importerDetailAction($slug)
    // {

    //     $dm = $this->container->get('doctrine_mongodb');

    //     $companies = $this->dm()
    //     ->createQueryBuilder('JariffDocumentBundle:CompanyDetail')
    //     ->sort('totalShipment', 'desc')
    //     ->field('slug_consignee_name')->equals($slug)
    //     ->field('shipper_name')->notEqual('N/A')
    //     ->field('shipper_name')->notEqual('')
    //     // ->field('company_as')->notEqual('product')
    //     ->getQuery()->execute();

    //     $shipperCount = 0;

    //     foreach ($companies as $company) {
    //         $shipperCount = $company->getTotalShipment() + $shipperCount;
    //     }

    //     $count = $companies->count();

    //     $company = null;
    //     var_dump(count($companies));
    //     foreach ($companies as $data) {
    //         $company = $data;
    //         break;
    //     }
    //     var_dump($slug);
    //     die();

    //     if ($company != null) {
    //         return $this->render('JariffMemberBundle:Search/Detail:importer_detail.html.twig', 
    //             array(
    //                 'data' => $company,
    //                 'companies' => $companies,
    //                 'shipperCount' => $shipperCount
    //                 ));
    //     } else {
    //         return $this->render('JariffMemberBundle:Search/Detail:importer_detail.html.twig',
    //             array('data' => new CompanyDetail(),
    //                 'companies' => array(),
    //                 // 'shipperCount' => 0
    //                 ));
    //     }
    // }

    /**
     * @Route(
     *   "/detail/{slug}",
     *   name     = "member_importer_detail_"
     * )
     */
    public function detailAction($slug)
    {
        $identify = $this->session()->get('set_identity');

        $keywordHistory = $this->repoMongo("JariffDocumentBundle:KeywordHistory")->findOneBy(array('identity' => (String)$identify));
//

        if (!empty($keywordHistory)) {
//        $keywordHistory = new KeywordHistory();
            $keywordHistory->setSlug($slug);
            $this->dm()->persist($keywordHistory);
            $this->dm()->flush();
        }

        $data = $this->repoMongo("JariffDocumentBundle:AllData")->findOneBy(array('value.slug_company' => $slug));


        return $this->render('JariffMemberBundle:Search/Detail:importer_detail.html.twig', array('data' => $data));
    }

    /**
     * @Route(
     *   "/keyword-history-total/{slug}",
     *   name     = "member_keyword_history_total"
     * )
     *
     *
     */
    public function total($slug)
    {
        $qb = $this->dm()->createQueryBuilder('JariffDocumentBundle:KeywordHistory')
            ->field('slug')
            ->equals($slug)
            ->map('function() { emit(this.keyword, 1); }')
            ->reduce('function(k, vals) {
        var sum = 0;
        for (var i in vals) {
            sum += vals[i];
        }
        return sum;
    }')->sort('{ "value" :1}');


        $query = $qb->getQuery();
        $searchCount = $query->execute();


        $charts[] = array('Element', 'Precentage');
        foreach ($searchCount as $row) {
            $charts[] = array($row['_id'], $row['value']);
        }

        return new Response(json_encode($charts));

    }

    /**
     * @Route(
     *   "/importer-show/{slug}",
     *   name     = "member_importer_detail_show"
     * )
     *
     *
     */
    public function importerDetailShowAction($slug)
    {
        $identify = $this->session()->get('set_identity');

        $keywordHistory = $this->repoMongo("JariffDocumentBundle:KeywordHistory")->findOneBy(array('identity' => $identify));
        if (!empty($keywordHistory)) {
            $keywordHistory->setSlug($slug);
            $this->dm()->persist($keywordHistory);
            $this->dm()->flush();
        }

        $data = $this->repoMongo("JariffDocumentBundle:AllData")->findOneBy(array('value.slug_company' => $slug));

        return $this->render('JariffMemberBundle:Search/Detail:importer_detail_view.html.twig', array('data' => $data));
    }

    /**
     * @Route(
     *   "/member/exporter/{slug}",
     *   name     = "member_exporter_detail_"
     * )
     *
     *
     */
    public function exporterDetailAction($slug)
    {

        $identify = $this->session()->get('set_identity');

        $keywordHistory = $this->repoMongo("JariffDocumentBundle:KeywordHistory")->findOneBy(array('identity' => $identify));
        if (!empty($keywordHistory)) {
            $keywordHistory->setSlug($slug);
            $this->dm()->persist($keywordHistory);
            $this->dm()->flush();
        }

        $data = $this->repoMongo("JariffDocumentBundle:AllData")->findOneBy(array('value.slug_company' => $slug));

        return $this->render('JariffMemberBundle:Search/Detail:exporter_detail.html.twig', array('data' => $data));
    }

    /**
     * @Route(
     *   "/importer/print-pdf/{slug}",
     *   name     = "member_importer_print_pdf"
     * )
     *
     *
     */
    public function printPdfAction($slug)
    {
        $data = $this->repoMongo("JariffDocumentBundle:AllData")->findOneBy(array('value.slug_company' => $slug));

        return $this->render('JariffMemberBundle:Search/Detail:importer_detail_pdf.html.twig', array('data' => $data));
    }


    /**
     * @Route(
     *   "/importer/pdf/{slug}",
     *   name     = "member_importer_print_pdf_"
     * )
     *
     *
     */
    public function pdfAction($slug)
    {

        $pageUrl = $this->generateUrl('member_importer_print_pdf', array('slug' => $slug), true); // use absolute path!

        if(!file_exists(realpath(__DIR__ . '/../../../../../') . "/web/pdf/" . $slug . '.pdf')){
            $this->get('knp_snappy.pdf')->generate($pageUrl, realpath(__DIR__ . '/../../../../../') . "/web/pdf/" . $slug . '.pdf');

        }

        return new Response(1);
    }

}