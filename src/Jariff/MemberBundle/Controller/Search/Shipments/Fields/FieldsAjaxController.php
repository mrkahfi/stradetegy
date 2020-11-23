<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\Search\Shipments\Fields;

use Elastica\Type\Mapping;
use Jariff\DocumentBundle\Document\KeywordHistory;
use Jariff\DocumentBundle\Document\SearchHistory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\MemberBundle\Model\SearchEmbed;
use Jariff\ProjectBundle\Util as Util;

use Jariff\MemberBundle\Model\SearchFieldCollect;
use Jariff\MemberBundle\Model\SearchFieldQ;
use Jariff\MemberBundle\Model\CustomData;
use Jariff\MemberBundle\Model\DateRange;
use Jariff\MemberBundle\Model\CustomCountryData;
use Jariff\MemberBundle\Form\SearchGlobal\SearchShipmentsEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomDataFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\DateRangeFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomCountryDataFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\USExports\SearchShipmentsUSExportEmbedFormType;


use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ARImports\SearchShipmentsArImportsEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ARExports\SearchShipmentsArExportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\BRExports\SearchShipmentsBrExportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CAExports\SearchShipmentsCAExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CAImports\SearchShipmentsCAImportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CLExports\SearchShipmentsCLExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CLImports\SearchShipmentsCLImportsEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\COExports\SearchShipmentsCOExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\COImports\SearchShipmentsCOImportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CRExports\SearchShipmentsCRExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CRImports\SearchShipmentsCRImportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ECExports\SearchShipmentsECExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ECImports\SearchShipmentsECImportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\PAExports\SearchShipmentsPAExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\PAImports\SearchShipmentsPAImportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\PEExports\SearchShipmentsPEExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\PEImports\SearchShipmentsPEImportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\PYExports\SearchShipmentsPYExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\PYImports\SearchShipmentsPYImportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\UYExports\SearchShipmentsUYExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\UYImports\SearchShipmentsUYImportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\VEExports\SearchShipmentsVEExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\VEImports\SearchShipmentsVEImportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\JNPTImports\SearchShipmentsJNPTImportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\MXImports\SearchShipmentsMXImportEmbedFormType;


use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;

/**
 * @Route("/")
 */
class FieldsAjaxController extends BaseController
{


    function submitTypeAsFieldAction(Request $request)
    {

        $customData = new CustomData();
        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom->handleRequest($request);
        $countryType = $formCustom->getData()->getType();

        $country = array(
            'us' => 'USA',
            'ar' => 'Argentina',
            'br' => 'Brazil',
            'ca' => 'Canada',
            'cl' => 'Chile',
            'co' => 'Colombia',
            'cr' => 'Costa Rica',
            'ec' => 'Ecuador',
            'jnpt' => 'India',
            'mx' => 'Mexico',
            'pa' => 'Panama',
            'pe' => 'Peru',
            'py' => 'Paraguay',
            'uy' => 'Uruguay',
            've' => 'Venezuela',
            );

        $params = array();
        $selected = null;
        foreach($country as $c => $key){
            if(!$this->iamSubcriptionCountry($c.'-'.$countryType)){
                $params = array_merge($params,array($c => 'disabled'));
            }else{
                if(empty($selected))
                    $selected = $c;
            }
        }

        return array(
            'success' => true,
            'country_list' => $params,
            // 'selected' => $selected
            );
    }

    /**
     * @Route(
     *   "/member/search-shipments-updating-field",
     *   name     = "member_search_shipments_updating_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function updatingFieldAction(Request $request)
    {
        return new Response(
            json_encode(
             $this->submitTypeAsFieldAction($request)
             )
            );
    }

    /**
     * @Route(
     *   "/member/search-shipments-us-imports-field",
     *   name     = "member_search_shipments_us_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitUsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = 'product_desc';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);



        $form = $this->createForm(new SearchShipmentsEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));

        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


         $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

         $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
         $dateRange->setDateFrom($todayMin);
         $today = new \DateTime('now');
         $dateRange->setDateTo($today->format("Y-m-d"));

         $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


     }



     if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-us-exports-field",
     *   name     = "member_search_shipments_us_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     */
    public function submitUsExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = 'item_description';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsUSExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $customData->setType('imports');
        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $customCountryData->setType('us');
        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


         $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

         $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
         $dateRange->setDateFrom($todayMin);
         $today = new \DateTime('now');
         $dateRange->setDateTo($today->format("Y-m-d"));

         $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


     }



     if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-ar-imports-field",
     *   name     = "member_search_shipments_ar_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitArImportsFieldAction(Request $request)
    {
        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = 'product';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsArImportsEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


         $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

         $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
         $dateRange->setDateFrom($todayMin);
         $today = new \DateTime('now');
         $dateRange->setDateTo($today->format("Y-m-d"));

         $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


     }



     if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-ar-exports-field",
     *   name     = "member_search_shipments_ar_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitArExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = 'product';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsArExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-br-exports-field",
     *   name     = "member_search_shipments_br_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitBrExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = 'product';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsBrExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-br-imports-field",
     *   name     = "member_search_shipments_br_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitBrImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = 'product';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsBrExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-ca-exports-field",
     *   name     = "member_search_shipments_ca_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitCaExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = 'hs_code';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsCAExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-ca-imports-field",
     *   name     = "member_search_shipments_ca_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitCaImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = 'hs_code_description';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsCAImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-cl-exports-field",
     *   name     = "member_search_shipments_cl_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitClExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = 'product';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsCLExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-cl-imports-field",
     *   name     = "member_search_shipments_cl_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitClImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsCLImportsEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-co-exports-field",
     *   name     = "member_search_shipments_co_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitCoExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsCOExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-co-imports-field",
     *   name     = "member_search_shipments_co_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitCoImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsCOImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-cr-exports-field",
     *   name     = "member_search_shipments_cr_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitCrExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsCRExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-cr-imports-field",
     *   name     = "member_search_shipments_cr_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitCrImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsCRImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-ec-exports-field",
     *   name     = "member_search_shipments_ec_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitEcExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsECExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-ec-imports-field",
     *   name     = "member_search_shipments_ec_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitEcImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsECImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-jnpt-exports-field",
     *   name     = "member_search_shipments_jnpt_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     */
    public function submitJnptExportsFieldAction(Request $request)
    {
        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $customData->setType('imports');
        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $customCountryData->setType('us');
        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));


        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $formDateRange->handleRequest($request);
        $active_subscription = $this->user()->getLastSubscription();
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    

    $params = array_merge(array(
        'message' => 'Sorry, JNPT Exports no data',
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array(
                'success' => false,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:no_data.html.twig', $params),
                'html_callback' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params)
                )
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-jnpt-imports-field",
     *   name     = "member_search_shipments_jnpt_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitJnptImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsJNPTImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-mx-imports-field",
     *   name     = "member_search_shipments_mx_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitMxImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsMXImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-mx-exports-field",
     *   name     = "member_search_shipments_mx_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitMxExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsMXImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-pa-exports-field",
     *   name     = "member_search_shipments_pa_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitPaExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsPAExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-pa-imports-field",
     *   name     = "member_search_shipments_pa_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitPaImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsPAImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-pe-exports-field",
     *   name     = "member_search_shipments_pe_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitPeExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsPEExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-pe-imports-field",
     *   name     = "member_search_shipments_pe_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitPeImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsPEImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-py-exports-field",
     *   name     = "member_search_shipments_py_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitPyExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsPYExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-py-imports-field",
     *   name     = "member_search_shipments_py_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitPyImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsPYImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-uy-exports-field",
     *   name     = "member_search_shipments_uy_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitUyExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsUYExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-uy-imports-field",
     *   name     = "member_search_shipments_uy_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitUyImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsUYImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-ve-exports-field",
     *   name     = "member_search_shipments_ve_exports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitVeExportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsVEExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

    /**
     * @Route(
     *   "/member/search-shipments-ve-imports-field",
     *   name     = "member_search_shipments_ve_imports_field",
     *   options={"expose"=true}
     * )
     * @Method("POST")
     *
     *
     */
    public function submitVeImportsFieldAction(Request $request)
    {

        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();

        $search1 = new SearchFieldCollect();
        $search1->collect = '';
        $entity->getCollect()->add($search1);

        $searchQ = new SearchFieldQ();
        $searchQ->q = '';
        $entity->getQ()->add($searchQ);

        $form = $this->createForm(new SearchShipmentsVEImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
            'action' => '',
            'method' => 'POST',
            ));

        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


        $active_subscription = $this->user()->getLastSubscription();
        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $historyValue = $active_subscription->getHistoryValue();

        $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
        if ($active_subscription->getEverythingPlan()) {


           $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

           $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
           $dateRange->setDateFrom($todayMin);
           $today = new \DateTime('now');
           $dateRange->setDateTo($today->format("Y-m-d"));

           $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
            ));


       }



       if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
        return $this->defaultError($request);
    }

    $params = array_merge(array(
        'form' => $form->createView(),
        'formDateRange' => $formDateRange->createView(),
        'formCustom' => $formCustom->createView(),
        'formCustomCountry' => $formCustomCountry->createView(),
        'currentDate' => $todayMin
        ),$this->submitTypeAsFieldAction($request));

    return new Response(
        json_encode(
            array_merge(array(
                'success' => true,
                'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $params),
                's_cache' => ''
                ),$this->submitTypeAsFieldAction($request))
            )
        );
}

public function defaultError($request)
{
    $entity = new SearchEmbed();
    $customData = new CustomData();
    $dateRange = new DateRange();
    $customCountryData = new CustomCountryData();

    $search1 = new SearchFieldCollect();
    $search1->collect = '';
    $entity->getCollect()->add($search1);

    $searchQ = new SearchFieldQ();
    $searchQ->q = '';
    $entity->getQ()->add($searchQ);

    $form = $this->createForm(new SearchShipmentsEmbedFormType(), $entity, array(
        'action' => '',
        'method' => 'POST',
        ));

    $customData->setType('imports');
    $formCustom = $this->createForm(new CustomDataFormType(), $customData, array(
        'action' => '',
        'method' => 'POST',
        ));

    $customCountryData->setType('us');
    $formCustomCountry = $this->createForm(new CustomCountryDataFormType(), $customCountryData, array(
        'action' => '',
        'method' => 'POST',
        ));

    $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
        'action' => '',
        'method' => 'POST',
        ));

    
    $active_subscription = $this->user()->getLastSubscription();
    $formCustom->handleRequest($request);
    $formCustomCountry->handleRequest($request);
    $formDateRange->handleRequest($request);
    $historyValue = $active_subscription->getHistoryValue();

    $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
    if ($active_subscription->getEverythingPlan()) {


       $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

       $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
       $dateRange->setDateFrom($todayMin);
       $today = new \DateTime('now');
       $dateRange->setDateTo($today->format("Y-m-d"));

       $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
        'action' => '',
        'method' => 'POST',
        ));


   }

   if (!$this->iamSubcriptionCountry($formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType())) {
    return $this->defaultError($request);
}

$params = array_merge(array(
    'form' => $form->createView(),
    'formDateRange' => $formDateRange->createView(),
    'formCustom' => $formCustom->createView(),
    'formCustomCountry' => $formCustomCountry->createView(),
    'currentDate' => $todayMin
    ),$this->submitTypeAsFieldAction($request));

$params = array(
    'message' => 'Sorry, Country Type not subscription'
    );
return new Response(
    json_encode(
        array(
            'success' => false,
            'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:no_data.html.twig', $params),
            'html_callback' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:us_field.html.twig', $paramsCallBack)
            )
        )
    );
}
}