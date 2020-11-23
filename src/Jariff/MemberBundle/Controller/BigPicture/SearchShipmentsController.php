<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\BigPicture;

use Elastica\Type\Mapping;
use Jariff\DocumentBundle\Document\KeywordHistory;
use Jariff\DocumentBundle\Document\SearchHistory;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CAExports\SearchShipmentsCAExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CAImports\SearchShipmentsCAImportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CLExports\SearchShipmentsCLExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CLImports\SearchShipmentsCLImportsEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CRExports\SearchShipmentsCRExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CRImports\SearchShipmentsCRImportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ECExports\SearchShipmentsECExportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ECImports\SearchShipmentsECImportEmbedFormType;
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

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ARImports\SearchShipmentsArImportsEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ARExports\SearchShipmentsArExportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\BRExports\SearchShipmentsBrExportEmbedFormType;

use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ElasticaAdapter;
use Elastica\Index;
use Elastica\Query as QueryEs;
use Elastica\Client as ClientEs;
use Symfony\Component\Validator\Constraints\Date;


/**
 * @Route("/")
 */
class SearchShipmentsController extends BaseController
{

    /**
     * @Route(
     *   "/member/search-the-big-picture",
     *   name     = "member_search_the_big_picture"
     * )
     *
     * @Template("JariffMemberBundle:Search\BigPicture:index.html.twig")
     */
    public function indexAction()
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

        $customCountryData->setType('us');
        $customData->setType('imports');


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

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || (!$active_subscription->isActive())) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

        if ($active_subscription->getEverythingPlan()) {

            $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => 'us-imports'));

            $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
            $dateRange->setDateFrom($todayMin);
            $today = new \DateTime('now');
            $dateRange->setDateTo($today->format("Y-m-d"));
        }else{
            $historyValue = $active_subscription->getHistoryValue();

            $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
            $dateRange->setDateFrom($todayMin);
            $today = new \DateTime('now');
            $dateRange->setDateTo($today->format("Y-m-d"));
        }



        $formDateRange = $this->createForm(new DateRangeFormType(), $dateRange, array(
            'action' => '',
            'method' => 'POST',
        ));


        return array(
            'form' => $form->createView(),
            'formDateRange' => $formDateRange->createView(),
            'formCustom' => $formCustom->createView(),
            'formCustomCountry' => $formCustomCountry->createView(),
            'currentDate' => $todayMin
        );
    }

    /**
     * @Route(
     *   "/member/search-the-big-picture-submit",
     *   name     = "member_search_the_big_picture_submit"
     * )
     * @Method("POST")
     * @Template("JariffMemberBundle:Search\BigPicture:index.html.twig")
     */
    public function submitAction(Request $request)
    {
        $entity = new SearchEmbed();
        $customData = new CustomData();
        $dateRange = new DateRange();
        $customCountryData = new CustomCountryData();



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


        $formCustom->handleRequest($request);
        $formCustomCountry->handleRequest($request);
        $formDateRange->handleRequest($request);
        $form = $this->createForm(new SearchShipmentsEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        if ($formCustom->isValid() and $formCustomCountry->isValid() and $formDateRange->isValid()) {

           $form = $this->formCollect($formCustomCountry->getData()->getType(),$formCustom->getData()->getType(),$entity);


            $form->handleRequest($request);

            if($form->isValid()){

            }else{
                return array(
                    'form' => $form->createView(),
                    'formCustom' => $formCustom->createView(),
                    'formCustomCountry' => $formCustomCountry->createView(),
                    'formDateRange' => $formDateRange->createView(),
                );
            }

            $i = 0;
            $j = 0;

            $today = new \DateTime('now');
            if ($this->get('session')->get('timezone')) {
                $tz = new \DateTimeZone($this->get('session')->get('timezone'));
                $today = new \DateTime('now', $tz);
            }

            if (!$this->get('session')->get('checkSubmit')) {
                $this->get('session')->set('checkSubmit', "isubmit");
            }

            $timeIdentity = strtotime($today->format('YmdHis'));

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
                        array(
                            'condition' => null,
                            'collect' => $key->collect,
                            'q' => $q[$j]
                        );
                } else {
                    $cache['search'][] =
                        array(
                            'condition' => $condition[$i],
                            'collect' => $key->collect,
                            'q' => $q[$j]
                        );
                    $i++;
                }

                $j++;

            }

            $util = new StringTwig();



            $s_cache = $util->encrypted(json_encode(array_merge($cache,
                        array('time' => $timeIdentity),
                        array('type' => array(
                            'custom_country' => $formCustomCountry->getData()->getType(),
                            'custom_data' => $formCustom->getData()->getType(),
                        )
                        ),
                        array('date_range' =>
                            array(
                                'from' => $formDateRange->getData()->getDateFrom(),
                                'to' => $formDateRange->getData()->getDateTo(),
                                'not_valid_date' => $formDateRange->getData()->getNotValidDate(),
                            )

                        ),
                        array("marked_master" => $formDateRange->getData()->getMarkedMaster() )
                    )
                )
            );

//            $keyWordHistory = new KeywordHistory();
//            $keyWordHistory->setUserId($this->getUser()->getId());
//            $keyWordHistory->setKeyword($s_cache);
//            $keyWordHistory->setIdentity($today->format('Y-m-d'));
//            $keyWordHistory->setSearchOn('shipments-search');
//            $this->dm()->persist($keyWordHistory);
//            $this->dm()->flush();


            return $this->redirectUrl('member_search_big_picture_buyers_res' . $formCustomCountry->getData()->getType() . "_" . $formCustom->getData()->getType(), array(
                's_cache' => $s_cache
            ));

        }

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || ($active_subscription->getActive() != 1)) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

        if ($active_subscription->getEverythingPlan()) {

            $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => 'us-imports'));

            $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");

        }else{
            $historyValue = $active_subscription->getHistoryValue();

            $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));

        }


        return array(
            'form' => $form->createView(),
            'formCustom' => $formCustom->createView(),
            'formCustomCountry' => $formCustomCountry->createView(),
            'formDateRange' => $formDateRange->createView(),
            'currentDate' => $todayMin
        );
    }

    /**
     * @Route(
     *   "/member/search-the-big-picture/value-subscribe",
     *   name     = "member_value_subscribe"
     * )
     *
     *
     */
    public function countShipmentsAction()
    {

        $active_subscription = $this->user()->getLastSubscription();

        $today = new \DateTime('now');
        if ($this->get('session')->get('timezone')) {
            $tz = new \DateTimeZone($this->get('session')->get('timezone'));
            $today = new \DateTime('now', $tz);
        }




        $expired = $active_subscription->getDateExpired();
        if(!empty($expired)){
            if ($active_subscription->getDateExpired()->format("Y-m-d H:i:s") < $today->format("Y-m-d H:i:s")) {
                die("Please update subscriptions");
            }
        }

        $active_subscription = $this->user()->getLastSubscription();

        if($active_subscription->getEverythingPlan()){
            $data = array(
                'rawdata' => 'You have performed
                <st class="j-count-search-today">'.$active_subscription->getTotalSearches().'</st>
                searches this billing period<br/>
                You have performed
                <st class="j-download-limit">'.$active_subscription->getDownloadLimit().'</st>
                downloads this billing period',
                'limit' => 0
            );
        }else{

//            if($res->getNbResults() <= $active_subscription->getSearchValue()){
            $cek = $this->repo("JariffMemberBundle:MemberTodaySearch")->findOneBy(array(
                'member' => $this->getUser()->getId(),
                'date_search' => $today
            ));

           $total = (!empty($cek)) ? $cek->getTotal() : 0;


                $data = array(
                    'rawdata' => 'You have used
                <st class="j-count-search-today">'.$total .'</st>
                of
                <st class="j-count-search-value">'.$active_subscription->getSearchValue().'</st>
                searches<br/>
                You have used
                <st class="j-download-limit">'.$active_subscription->getDownloadLimit().'</st>
                of
                <st class="j-download-value">'. $active_subscription->getDownloadValue().'</st>
                downloads',
                    'limit' => 0

                );
        }

        return new Response(json_encode($data));
    }

    /**
     * @Route(
     *   "/member/expired-subscription",
     *   name     = "member_expired_subscription"
     * )
     *
     * @Template("JariffMemberBundle:Search\Message:expired.html.twig")
     */
    public function expiredAction()
    {

        return array();
    }

    /**
     * @Route(
     *   "/member/render-company-display-one-the-big-picture/{folder}/{index}/{type}/{objId}",
     *   name     = "company_render_display_one_the_big_picture",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function renderDisplayOneShipmentsAction($folder,$index, $type, $objId)
    {

        $client = new \Elasticsearch\Client(array("hosts" => array("52.27.146.43:9200")));


        $params['index'] = $index;
        $params['type'] = $type;
        $params['id'] = $objId;
        $ret = $client->getSource($params);
        $template = str_replace("/","","JariffMemberBundle:Search\/".$folder."\Add:modal_show_one_the_big_picture_save_or_print.html.twig");

        $html = $this->renderView($template, array(
            'entity' => $ret
        ));
//
//        return $html;



        if(isset($ret['consignee_name'])){
            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $ret['consignee_name'] . '.pdf"'
                )
            );
        }else{

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $type.'-'.strtotime('now') . '.pdf"'
                )
            );
        }

    }


    function formCollect($country, $type,$entity){
        if($country == "ar" and $type == "imports"){
            return $this->createForm(new SearchShipmentsArImportsEmbedFormType(), $entity, array(
                'action' => '',
                'method' => 'POST',
            ));
        }elseif($country == "ar" and $type == "exports"){
            return $this->createForm(new SearchShipmentsArExportEmbedFormType(), $entity, array(
                'action' => '',
                'method' => 'POST',
            ));
        }elseif($country == "br" and ($type == "exports" or $type == "imports" )){
            return $this->createForm(new SearchShipmentsBrExportEmbedFormType(), $entity, array(
                'action' => '',
                'method' => 'POST',
            ));
        }elseif($country == "ca" and $type == "exports"){
            return $this->createForm(new SearchShipmentsCAExportsController(), $entity, array(
                'action' => '',
                'method' => 'POST',
            ));
        }elseif($country == "ca" and $type == "imports"){
            return $this->createForm(new SearchShipmentsCAImportEmbedFormType(), $entity, array(
                'action' => '',
                'method' => 'POST',
            ));
        }elseif($country == "cl" and $type == "exports"){
            return $this->createForm(new SearchShipmentsCLExportEmbedFormType(), $entity, array(
                'action' => '',
                'method' => 'POST',
            ));
        }elseif($country == "cl" and $type == "imports"){
            return $this->createForm(new SearchShipmentsCLImportsEmbedFormType(), $entity, array(
                'action' => '',
                'method' => 'POST',
            ));
        }elseif($country == "cr" and $type == "exports"){
            return $this->createForm(new SearchShipmentsCRExportEmbedFormType(), $entity, array(
                'action' => '',
                'method' => 'POST',
            ));
        }elseif($country == "cr" and $type == "imports"){
            return $this->createForm(new SearchShipmentsCRImportEmbedFormType(), $entity, array(
                'action' => '',
                'method' => 'POST',
            ));
        }elseif($country == "ec" and $type == "exports"){
            return $this->createForm(new SearchShipmentsECExportEmbedFormType(), $entity, array(
                'action' => '',
                'method' => 'POST',
            ));
        }elseif($country == "ec" and $type == "imports"){
            return $this->createForm(new SearchShipmentsECImportEmbedFormType(), $entity, array(
                'action' => '',
                'method' => 'POST',
            ));
        }else{
            return $this->createForm(new SearchShipmentsEmbedFormType(), $entity, array(
                'action' => '',
                'method' => 'POST',
            ));
        }
    }
}