<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\Search\Shipments;

use Jariff\MemberBundle\Entity\ContactCompany;
use Jariff\MemberBundle\Entity\MemberTodaySearch;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ExportsSearchShipmentsFormType;
use Jariff\ProjectBundle\Twig\StringTwoExtension;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;

use Jariff\AdminBundle\Entity\Inbound;
use Jariff\MemberBundle\Model\BillTypeCode;
use Jariff\MemberBundle\Model\FieldUsCustom;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldBillTypeCodeFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldUsImportFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\SaveSearchShipmentsFormType;

use Jariff\MemberBundle\Entity\SavedSearch;
use Jariff\MemberBundle\Entity\SavedCompany;
use Jariff\MemberBundle\Entity\ExportTools;
use Jariff\MemberBundle\Entity\MemberActivityFeed;
use Jariff\MemberBundle\Model\SearchFieldCollect;
use Jariff\MemberBundle\Model\SearchFieldQ;
use Jariff\MemberBundle\Model\SearchFieldSize;
use Jariff\MemberBundle\Model\SearchFieldCondition;
use Jariff\MemberBundle\Form\SearchGlobal\Field\FieldSizeFormType;
use Jariff\MemberBundle\Model\CustomData;
use Jariff\MemberBundle\Form\SearchGlobal\SearchShipmentsEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomDataFormType;

use Jariff\MemberBundle\Model\DateRange;
use Jariff\MemberBundle\Model\CustomCountryData;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\DateRangeFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\CustomCountryDataFormType;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\ProjectBundle\Util as Util;
use Jariff\MemberBundle\Model\SearchEmbed;
use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;
use Jariff\ProjectBundle\Twig\StringExtension;

use Pagerfanta\View\TwitterBootstrap3View;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ElasticaAdapter;
use Elastica\Index;
use Elastica\Scroll;
use Elastica\Search as Searchings;
use Elastica\Query as QueryEs;
use Elastica\Client as ClientEs;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldConsigneeNameFormType;
use Jariff\MemberBundle\Model\ConsigneeName;

use Jariff\MemberBundle\Model\Country;
use Jariff\MemberBundle\Form\Widget\Field\CountryFormType;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldShipperNameFormType;
use Jariff\MemberBundle\Model\ShipperName;
use FOS\ElasticaBundle\Client;
/**
 *
 * @Route("/member")
 */
class SearchShipmentsUSController extends BaseController
{

    private $s_cache_json;
    private $company_as;

    /**
     * @Route(
     *   "/search-shipments-result/us-imports/{page}",
     *   name     = "member_search_shipments_result_us_imports",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     *
     * @Template("JariffMemberBundle:Search\Shipments:results.html.twig")
     */
    public function resultAction()
    {
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        if (!isset($s_cache->time)) {
            return $this->errorRedirect('Your search is expired, please submit again', 'member_search_shipments');
        }


        $active_subscription = $this->user()->getLastSubscription();

        if (is_null($active_subscription) || !$active_subscription->isActive()) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

        $today = new \DateTime('now');
        if ($this->get('session')->get('timezone')) {
            $tz = new \DateTimeZone($this->get('session')->get('timezone'));
            $today = new \DateTime('now', $tz);
        }

        $expired = $active_subscription->getDateExpired();
        if (!empty($expired)) {
            if ($active_subscription->getDateExpired()->format("Y-m-d H:i:s") < $today->format("Y-m-d H:i:s")) {
                return $this->redirect($this->generateUrl('member_expired_subscription'));
            }
        }

        $historyValue = $active_subscription->getHistoryValue();
        $diff = abs(strtotime($today->format("Y-m-d")) - strtotime($s_cache->date_range->from));

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

        $form = $this->createForm(new SearchShipmentsEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $customData = new CustomData();
        $customData->setType($s_cache->type->custom_data);
        $dateRange = new DateRange();
        $dateRange->setDateFrom($s_cache->date_range->from);
        $dateRange->setDateTo($s_cache->date_range->to);
        $dateRange->setNotValidDate($s_cache->date_range->not_valid_date);
        $dateRange->setMarkedMaster($s_cache->marked_master);

        $customCountryData = new CustomCountryData();
        $customCountryData->setType($s_cache->type->custom_country);


        $fieldUsCustom = new FieldUsCustom();
        if (empty($s_cache->column))
            $fieldUsCustom->field_us_custom = array('actual_arrival_date', 'product_desc',
                'consignee_name', 'consignee_address', 'shipper_name', 'shipper_address',
                'notify_party_name', 'notify_party_address', 'quantity', 'quantity_unit',
                'teu', 'hs_code', 'marks_and_numbers', 'loading_port', 'mode_of_transportation',
                'weight', 'container_id','carrier_sasc_code','bill_of_lading','master_bill_of_lading',
                'place_of_receipt','vessel_name','container_type','voyage'
                );
        else {
            $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
        }

        $formFieldUsCustom = $this->createForm(new FieldUsImportFormType(), $fieldUsCustom, array(
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

        $member_search = $active_subscription;
        $historyValue = $member_search->getHistoryValue();

        if (!$active_subscription->getEverythingPlan()) {
            $cekSearch = $this->repo("JariffMemberBundle:MemberTodaySearch")->findOneBy(array(
                'member' => $this->getUser()->getId(),
                'date_search' => $today
                ));

            if (!empty($cekSearch)) {
                if (($cekSearch->getTotal() + 1) > $active_subscription->getSearchValue()) {
                    return $this->errorRedirect('Sorry, Total number of searches for Total Package today is limited', 'member_search_shipments');
                }
            }

            $maxHistory = $diff / (3600 * 24 * 30);

            if (floor($maxHistory) > $historyValue) {
                return $this->errorRedirect('You dont have any history more than ' . $historyValue . ' month. Please update your subscription.', 'member_search_shipments');
            }
        }

        return array(
            'form' => $form->createView(),
            'formCustom' => $formCustom->createView(),
            'formFieldUsCustom' => $formFieldUsCustom->createView(),
            'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            'column' => $fieldUsCustom->field_us_custom,
            'maxDownload' => $member_search->getDownloadLimit(),
            'urlChanges' => isset($urlChanges) ? $urlChanges : null,
            'formDateRange' => $formDateRange->createView(),
            'formCustomCountry' => $formCustomCountry->createView(),
            'downloadValue' => $active_subscription->getDownloadValue(),
            'downloadLimit' => $active_subscription->getDownloadLimit(),
            'historyValue' => $historyValue,
            'query_search' => $this->s_cache_json,
            'columnName' => array_merge(array('number','detail_company'),$fieldUsCustom->field_us_custom)
            ,
            'columnNameFilter' => array_merge(array('number'),$fieldUsCustom->field_us_custom)
            );
    }

     /**
     * @Route(
     *   "/search-shipments-result-new-time/us-imports",
     *   name     = "member_search_shipments_result_new_time_us_imports",
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
     public function resultNewTimeAction()
     {
         $util = new StringTwig();
         $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

         unset($s_cache->time);

         $today = new \DateTime('now');
         if ($this->get('session')->get('timezone')) {
            $tz = new \DateTimeZone($this->get('session')->get('timezone'));
            $today = new \DateTime('now', $tz);
        }

        $timeIdentity = strtotime($today->format('YmdHis'));

        $s_cache = (object) array_merge((array) $s_cache,array('time' => $timeIdentity));

        $util = new StringTwig();
        $s_cache = $util->encrypted(json_encode($s_cache));

        if (!$this->get('session')->get('checkSubmit')) {

            $this->get('session')->set('checkSubmit', "isubmit");
        }
        

        return $this->redirectUrl('member_search_shipments_result_us_imports',array('s_cache' => $s_cache));
        
        
    }

     /**
     * @Route(
     *   "/search-shipments-result-submit/us-imports",
     *   name     = "member_search_shipments_result_submit_ajax_us_imports",
     *  options={"expose"=true}
     * )
     * @Method("POST")    
     */
     public function resultAjaxAction(Request $request)
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

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || (!$active_subscription->isActive())) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

        if ($active_subscription->getEverythingPlan()) {

            $currentDateCountry = $this->repo("JariffMemberBundle:Country")->findOneBy(array('slug' => $formCustomCountry->getData()->getType() . "-" . $formCustom->getData()->getType()));

            $todayMin = $currentDateCountry->getDateFrom()->format("Y-m-d");
            // $dateRange->setDateFrom($todayMin);
            $today = new \DateTime('now');
            $dateRange->setDateTo($today->format("Y-m-d"));
        }else{
            $historyValue = $active_subscription->getHistoryValue();

            $todayMin = date("Y-m-d", strtotime(date("Y-m-d") . "-$historyValue months"));
            // $dateRange->setDateFrom($todayMin);
            $today = new \DateTime('now');
            $dateRange->setDateTo($today->format("Y-m-d"));
        }

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
                'currentDate' => $todayMin
                );
        }

        $i = 0;
        $j = 0;

        $today = new \DateTime('now');
        if ($this->get('session')->get('timezone')) {
            $tz = new \DateTimeZone($this->get('session')->get('timezone'));
            $today = new \DateTime('now', $tz);
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

        if (!$this->get('session')->get('checkSubmit')) {
            $this->get('session')->set('checkSubmit', "isubmit");
        }



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

        $this->s_cache_json = $s_cache;

        return new Response(json_encode(array(
            'url' => $this->generateUrl('member_search_shipments_result_us_imports_ajax_jsonp',array('s_cache' => $s_cache)),
            'url_change' => $this->generateUrl('member_search_shipments_result_us_imports',array('s_cache' => $this->s_cache_json)),
            "url_save" => $this->generateUrl("member_display_modal_save_shipments",array("slug_country_subscription" => "us-imports","s_cache" => $this->s_cache_json)),
            "url_export" => $this->generateUrl("member_display_modal_exports_shipments",array("collection" => "us-imports","s_cache" => $this->s_cache_json)),
            "url_contact_company" =>$this->generateUrl("member_search_result_contact_company_us_imports",array("company_as" => "consignee_name","s_cache" => $this->s_cache_json))
            )));


        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($s_cache));
        $size = 15;

        $page = $request->get('page');
        $rows = $request->get('rp');

        if (empty($page)) {
            $page = 1;
        }

        if (isset($rows))
            $size = $rows;

        // $this->s_cache_json = $this->getRequest()->get('s_cache');

        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || !$active_subscription->isActive()) {
           $jsonp = array(
            "errors" => true,
            'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:error_on_search.html.twig', 
                array( "title" => '<span class="icon-warning-info"></span> Your subscription',
                    "message" => "You dont have any active subscription. Please update your subscription.",
                    "button" => array(
                        '<a href="'.$this->generateUrl("support_ticket_inbound_form").'" class="btn btn-info"><span class="icon-user"></span> Customer Service</a>'
                        )
                    )
                ),

            );


           return new Response(
            json_encode(
                $jsonp
                )
            );
           return $this->errorRedirect('', 'dashboard_member');
       }


       $fields = array('product_desc',
        'consignee_name',
        'consignee_address',
        'shipper_name',
        'shipper_address',
        'notify_party_names',
        'loading_port',
        'bill_of_lading',
        'vessel_name',
        'master_bill_of_lading',
        'country',
        'marks_numbers',
        'carrier_sasc_code',
        "container_id",
        "unloading_port");


       $queryDSL = $this->queryDSL($s_cache, "actual_arrival_date", "product_desc", $fields, $size, $page);

       $query = new \Elastica\Query();
       $query->setRawQuery($queryDSL);

       $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')
        ));

       $searchable = new Index($client, 'trade-usa-imports-*');

       $adapter = new ElasticaAdapter($searchable, $query);

       $res = new Pagerfanta($adapter);

       $res->setMaxPerPage($size);
       $res->setCurrentPage($page);

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

    $cek = $this->repo("JariffMemberBundle:MemberTodaySearch")->findOneBy(array(
        'member' => $this->getUser()->getId(),
        'date_search' => $today
        ));



    if ($active_subscription->getEverythingPlan()) {
        $history = array(
            "total_row" => $res->getNbResults(),
            "date_search" => $today->format('Y-m-d'),
            "time_search" => $today->format("H:i:s"),
            "timezone" => $today->format("T"),
            "user_id" => $this->getUser()->getId(),
            "query" => $this->s_cache_json,
            "priority" => 0,
            "set_pin" => "unpin"
            );

        $doc[] = new \Elastica\Document('', $history, "user_logs", 'trade-user-logs-' . $today->format("Y"));

        $client->addDocuments($doc);
        $client->getIndex('trade-user-logs-' . $today->format("Y"))->refresh();

        $active_subscription->setTotalSearches($active_subscription->getTotalSearches() + 1);
        $this->em()->persist($active_subscription);
        $this->em()->flush();
    } else {

        if (!empty($cek)) {
            if (($cek->getTotal() + 1) > $active_subscription->getSearchValue()) {
                $jsonp = array(
                    "errors" => true,
                    'message' => "Sorry, Total number of searches for Total Package today is limited",
                    "title" => "Search is Limited",
                    'buttons' => array(
                        array(
                            'cssClass' => 'btn btn-info',
                            'icon' => 'icon-user',
                            'label' => 'Customer Service',
                            'action' => $this->generateUrl("support_ticket_inbound_form")
                            )
                        )

                    );


                return new Response(
                    json_encode(
                        $jsonp
                        )
                    );



                    // return $this->errorRedirect('Sorry, Total number of searches for Total Package today is limited', 'member_search_shipments');
            }
        }

        if (empty($cek)) {
            $newIsert = new MemberTodaySearch();
            $newIsert->setDateSearch($today);
            $newIsert->setMember($this->getUser());


            $newIsert->setTotal($newIsert->getTotal() + 1);
            $this->em()->persist($newIsert);
            $this->em()->flush();
        } else {
            $cek->setTotal($cek->getTotal() + 1);
            $this->em()->persist($cek);
            $this->em()->flush();
        }

        $history = array(
            "total_row" => $res->getNbResults(),
            "time_search" => $today->format("H:i:s"),
            "timezone" => $today->format("T"),
            "user_id" => $this->getUser()->getId(),
            "query" => $this->s_cache_json,
            "priority" => 0,
            "set_pin" => "unpin"
            );

        $doc[] = new \Elastica\Document('', $history, "user_logs", 'trade-user-logs-' . $today->format("Y"));

        $client->addDocuments($doc);
        $client->getIndex('trade-user-logs-' . $today->format("Y"))->refresh();
    }

    $fieldUsCustom = new FieldUsCustom();
    if (empty($s_cache->column))
        $fieldUsCustom->field_us_custom = array('actual_arrival_date', 'product_desc',
            'consignee_name', 'consignee_address', 'shipper_name', 'shipper_address',
            'notify_party_name', 'notify_party_address', 'quantity', 'quantity_unit',
            'teu', 'hs_code', 'marks_and_numbers', 'loading_port', 'mode_of_transportation',
            'weight', 'container_id','carrier_sasc_code','bill_of_lading','master_bill_of_lading',
            'place_of_receipt','vessel_name','container_type','voyage'
            );
    else {
        $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
    }

    $columnMd = array();
    $allRes = array();
    $util2 = new StringExtension();
    $util->setEm($this->em());

    $today = new \DateTime('now');
    if ($this->get('session')->get('timezone')) {
        $tz = new \DateTimeZone($this->get('session')->get('timezone'));
        $today = new \DateTime('now', $tz);
    }
    $dateSubscriptions = $active_subscription->getDateUpdate();
    $dateSubscriptions->modify("+ " . $active_subscription->getMonthValue() . " month");

    $diff = abs(strtotime($today->format("Y-m-d H:i:s")) - $s_cache->time);
    $maxTime = $diff / 3600;

    if ($res->getNbResults() == 0) {

        $inbound = new Inbound();
        $inbound->setDateCreate(new \DateTime('now'));
        $inbound->setEmail($this->getUser()->getUsername());
        $inbound->setDescription($this->s_cache_json);
        $inbound->setMember($this->getUser());
        $inbound->setVisitedPage($this->generateUrl('member_search_shipments_result_us_imports', array('s_cache' => $this->s_cache_json)));

        $this->em()->persist($inbound);
        $this->em()->flush();


        $jsonp = array(
            "errors" => true,
            'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:error_on_search.html.twig', 
                array( "title" => '<span class="icon-warning-sign"></span> Empty Results',
                    "message" => "Sorry, we could not find data the keyword you enter",
                    "button" => array(
                        '<button type="button" class="btn btn-success" data-dismiss="modal"><span class="icon-search"></span> Search Again</button>',
                        '<a href="'.$this->generateUrl("support_ticket_inbound_form").'" class="btn btn-info"><span class="icon-user"></span> Customer Service</a>'
                        )
                    )
                ),

            );


        return new Response(
            json_encode(
                $jsonp
                )
            );

    }

    $urlChanges = $this->generateUrl('member_search_shipments_result_us_imports',array('s_cache' => $this->s_cache_json));
    
    $entity = $this->repo('JariffMemberBundle:MemberSetting')->findOneByMember($this->getUser());

    if ($entity) {
        $weightOri = $entity->getWeight();
    } else {
        $weightOri = 'KG';
    }


    $allRes = array();


    $inumber = ($page == 1) ? 1 : (($page - 1) * $rows) + 1;
    foreach ($res->getCurrentPageResults()->getResults() as $r) {
        $columnMd['number'] = $inumber++;
        $columnMd['detail_company'] = ' <a href="#"
        data-whatever="' . $r->getHit()["_source"]['consignee_name'] . '"
        class="j-modal-company-one-shipments"
        data-toggle="modal" data-target="#myModal" data-backdrop="static"
        data-url="' . $this->generateUrl('company_display_one_shipments', array(
            'index' => $r->getHit()["_index"], 'type' => $r->getHit()["_type"], 'objId' => $r->getHit()["_id"]
            )) . '"><span class="icon-search3"></span></a>';

        foreach ($fieldUsCustom->field_us_custom as $col) {

            if ($col == "product_desc") {

                $columnMd[$col] = preg_replace("/<br\W*?\/>/",", ",$r->getHit()["_source"][$col]);

            } elseif ($col == "weight") {
                $columnMd[$col] = $util->setting_gross_weight($r->getHit()["_source"][$col], $r->getHit()["_source"]['weight_unit'], $this->getUser());
            }  elseif ($col == "actual_arrival_date") {
                $columnMd[$col] = date("m/d/Y",strtotime($r->getHit()["_source"][$col]));
            } elseif ($col == "marks_and_numbers") {
                $columnMd[$col] = $util2->readmore($r->getHit()["_source"][$col]);
            } elseif ($col == "weight_unit") {
                $columnMd[$col] = $weightOri;
            } elseif ($col == "mode_of_transportation") {
                $columnMd[$col] = $util->us_column_code_transportation($r->getHit()["_source"][$col]);
            } else {
                $columnMd[$col] = preg_replace("/<br\W*?\/>/",", ",$r->getHit()["_source"][$col]);
            }

            if(isset($r->getHit()["highlight"][$col][0])){
                $match = $r->getHit()["highlight"];

                $columnMd[$col] = preg_replace("/<br\W*?\/>/",", ",$match[$col][0]);                
            }

            $columnMd[$col] = $util->na($columnMd[$col]);
        }

        $allRes[] = $columnMd;
    }


    $total = $res->getNbResults() / $size;

    $keywords = '<div class="alert alert-info"> Your search of Data for :';

    foreach ($s_cache->search  as $key => $value) {
        if(isset($value->condition)){
            $keywords .= strtoupper($value->condition);
        }

        $keywords .= $util->globalfield($value->collect,$s_cache->type->custom_country.'_'.$s_cache->type->custom_data);

        $keywords .= '<a href="javascript:void(0)" class="j-popover-q label label-danger"
        data-container="body" data-toggle="popover" data-trigger="focus"
        data-placement="bottom">'.$value->q.'</a>';
    }
    $took = $res->getCurrentPageResults()->getResponse()->getData()["took"] / 1000;
    $keywords .= 'generated '.$util->ribuan($res->getNbResults()).' results in '.$took.'
    seconds.
</div>';

$jsonp = array(
    "url_change" => $urlChanges,
    'took' => $res->getCurrentPageResults()->getResponse()->getData()["took"] / 1000,
    "records_ribuan" => $util->ribuan($res->getNbResults()),
    "records" => $res->getNbResults(),
    "page" => $page,
    "total" =>$res->getNbResults(),
    "rows" => $allRes,
    "keywords" => $keywords,
    "url_save" => $this->generateUrl("member_display_modal_save_shipments",array("slug_country_subscription" => "us-imports","s_cache" => $this->s_cache_json)),
    "url_export" => $this->generateUrl("member_display_modal_exports_shipments",array("collection" => "us-imports","s_cache" => $this->s_cache_json)),
    "url_contact_company" =>$this->generateUrl("member_search_result_contact_company_us_imports",array("company_as" => "consignee_name","s_cache" => $this->s_cache_json))
    );

return new Response(
    json_encode(
        $jsonp
        )
    );

}


}

        /**
     * @Route(
     *   "/search-shipments-result-from-big-picture/us-imports/{keyword}/{category}/{page}",
     *   name     = "member_search_shipments_result_us_imports_from_big_picture",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *  options={"expose"=true}
     * )
     *
     * @Template("JariffMemberBundle:Search\Shipments\FromBigPicture:results.html.twig")
     */
        public function resultForBigPictureAction($keyword, $category)
        {

            $util = new StringTwig();
            $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));

            $this->s_cache_json = $this->getRequest()->get('s_cache');

            if (!isset($s_cache->time)) {
                return $this->errorRedirect('Your search is expired, please submit again', 'member_search_shipments');
            }


            $active_subscription = $this->user()->getLastSubscription();

            if (is_null($active_subscription) || !$active_subscription->isActive()) {
                return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
            }

            $today = new \DateTime('now');
            if ($this->get('session')->get('timezone')) {
                $tz = new \DateTimeZone($this->get('session')->get('timezone'));
                $today = new \DateTime('now', $tz);
            }

            $expired = $active_subscription->getDateExpired();
            if (!empty($expired)) {
                if ($active_subscription->getDateExpired()->format("Y-m-d H:i:s") < $today->format("Y-m-d H:i:s")) {
                    return $this->redirect('member_expired_subscription');
                }
            }

            $historyValue = $active_subscription->getHistoryValue();
            $diff = abs(strtotime($today->format("Y-m-d")) - strtotime($s_cache->date_range->from));

            $entity = new SearchEmbed();

        // var_dump($s_cache);
        // die();

            unset($s_cache->search);
            $s_cache->search = array();

            $newSearch = new \stdClass();
            $newSearch->condition = null;
            $newSearch->collect = $category;
            $newSearch->q = $keyword;

            $s_cache->search[] = $newSearch;

        // var_dump($s_cache->search);
        // die();

            foreach ($s_cache->search as $s) {
                $search1 = new SearchFieldCollect();
                $search1->collect = $s->collect;
                $entity->getCollect()->add($search1);

                $searchQ = new SearchFieldQ();
                $searchQ->q = $keyword;
                $entity->getQ()->add($searchQ);

                if (!is_null($s->condition)) {
                    $searchCondition = new SearchFieldCondition();
                    $searchCondition->condition = $s->condition;
                    $entity->getCondition()->add($searchCondition);
                }
            }

            $form = $this->createForm(new SearchShipmentsEmbedFormType(), $entity, array(
                'action' => '',
                'method' => 'POST',
                ));

            $customData = new CustomData();
            $customData->setType($s_cache->type->custom_data);
            $dateRange = new DateRange();
            $dateRange->setDateFrom($s_cache->date_range->from);
            $dateRange->setDateTo($s_cache->date_range->to);
            $dateRange->setNotValidDate($s_cache->date_range->not_valid_date);
            $dateRange->setMarkedMaster($s_cache->marked_master);

            $customCountryData = new CustomCountryData();
            $customCountryData->setType($s_cache->type->custom_country);


            $fieldUsCustom = new FieldUsCustom();
            if (empty($s_cache->column))

            $fieldUsCustom->field_us_custom = array('actual_arrival_date', 'product_desc',
                'consignee_name', 'consignee_address', 'shipper_name', 'shipper_address',
                'notify_party_name', 'notify_party_address', 'quantity', 'quantity_unit',
                'teu', 'hs_code', 'marks_and_numbers', 'loading_port', 'mode_of_transportation',
                'weight', 'container_id','carrier_sasc_code','bill_of_lading','master_bill_of_lading',
                'place_of_receipt','vessel_name','container_type','voyage'
                );
            else {
                $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
            }

            $formFieldUsCustom = $this->createForm(new FieldUsImportFormType(), $fieldUsCustom, array(
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

            $member_search = $active_subscription;
            $historyValue = $member_search->getHistoryValue();

            if (!$active_subscription->getEverythingPlan()) {
                $cekSearch = $this->repo("JariffMemberBundle:MemberTodaySearch")->findOneBy(array(
                    'member' => $this->getUser()->getId(),
                    'date_search' => $today
                    ));

                if (!empty($cekSearch)) {
                    if (($cekSearch->getTotal() + 1) > $active_subscription->getSearchValue()) {
                        return $this->errorRedirect('Sorry, Total number of searches for Total Package today is limited', 'member_search_shipments');
                    }
                }

                $maxHistory = $diff / (3600 * 24 * 30);

                if (floor($maxHistory) > $historyValue) {
                    return $this->errorRedirect('You dont have any history more than ' . $historyValue . ' month. Please update your subscription.', 'member_search_shipments');
                }
            }


        // if ($this->get('session')->get('checkSubmit')) {
        //     if ($active_subscription->getEverythingPlan()) {
        //         $active_subscription->setTotalSearches($active_subscription->getTotalSearches() + 1);
        //         $this->em()->persist($active_subscription);
        //         $this->em()->flush();
        //     } else {
        //         $cekSearch = $this->repo("JariffMemberBundle:MemberTodaySearch")->findOneBy(array(
        //             'member' => $this->getUser()->getId(),
        //             'date_search' => $today
        //             ));

        //         if (empty($cekSearch)) {
        //             $newIsert = new MemberTodaySearch();
        //             $newIsert->setDateSearch($today);
        //             $newIsert->setMember($this->getUser());


        //             $newIsert->setTotal($newIsert->getTotal() + 1);
        //             $this->em()->persist($newIsert);
        //             $this->em()->flush();
        //         } else {
        //             $cekSearch->setTotal($cekSearch->getTotal() + 1);
        //             $this->em()->persist($cekSearch);
        //             $this->em()->flush();
        //         }
        //     }
        // }
            unset($s_cache->country);
            $this->s_cache_json = $util->encrypted(json_encode($s_cache));

        // var_dump(json_encode($s_cache));
        // die();

            return array(
                'form' => $form->createView(),
                'formCustom' => $formCustom->createView(),
                'formFieldUsCustom' => $formFieldUsCustom->createView(),
                'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
                'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
                'column' => $fieldUsCustom->field_us_custom,
                'maxDownload' => $member_search->getDownloadLimit(),
                'urlChanges' => isset($urlChanges) ? $urlChanges : null,
                'formDateRange' => $formDateRange->createView(),
                'formCustomCountry' => $formCustomCountry->createView(),
                'downloadValue' => $active_subscription->getDownloadValue(),
                'downloadLimit' => $active_subscription->getDownloadLimit(),
                'historyValue' => $historyValue,
                'query_search' => $this->s_cache_json,
                's_cache' => $this->s_cache_json,
                'query_search' => $this->s_cache_json,
                'columnName' => array_merge(array('number','detail_company'),$fieldUsCustom->field_us_custom)
                ,
                'columnNameFilter' => array_merge(array('number'),$fieldUsCustom->field_us_custom)
                );

            // 'form' => $form->createView(),
            // 'formCustom' => $formCustom->createView(),
            // 'formFieldUsCustom' => $formFieldUsCustom->createView(),
            // 'countryCheck' => isset($countryChecked) ? $countryChecked : array(),
            // 'checkToChoice' => isset($dataChoices) ? $dataChoices : array(),
            // 'column' => $fieldUsCustom->field_us_custom,
            // 'maxDownload' => $member_search->getDownloadLimit(),
            // 'urlChanges' => isset($urlChanges) ? $urlChanges : null,
            // 'formDateRange' => $formDateRange->createView(),
            // 'formCustomCountry' => $formCustomCountry->createView(),
            // 'downloadValue' => $active_subscription->getDownloadValue(),
            // 'downloadLimit' => $active_subscription->getDownloadLimit(),
            // 'historyValue' => $historyValue,
            // 'query_search' => $this->s_cache_json,
            // 'columnName' => array_merge(array('number','detail_company'),$fieldUsCustom->field_us_custom)
            // ,
            // 'columnNameFilter' => array_merge(array('number'),$fieldUsCustom->field_us_custom)
        }

    /**
     * @Route(
     *   "/company-display-one-shipments/{index}/{type}/{objId}",
     *   name     = "company_display_one_shipments",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function displayOneShipmentsAction($index, $type, $objId)
    {

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));

        $queryDSL = array(
            "query" =>  array(
                "match" => array(
                    "_id" => $objId
                    )
                )
            );

        $query = new \Elastica\Search($client);
        $query->addIndex($index);
        $query->addType($type);
        $ret = $query->search($queryDSL);


        $params['index'] = $index;
        $params['type'] = $type;
        $params['id'] = $objId;


        return  new Response($this->renderView('JariffMemberBundle:Search\Shipments\Add:modal_show_one_shipments.html.twig', array('entity' => $ret->getResults()[0]->getHit()['_source'], 'params' => $params)));
    }

    /**
     * @Route(
     *   "/shipments-search-not-valid-date/{page}",
     *   name     = "member_search_result_not_valid_date"
     * )
     *
     *
     */
    public function notValidDateAction($page = 1)
    {
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        $size = 10;

        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');

        if (!isset($s_cache->time)) {
            return $this->errorRedirect('Your search is expired, please submit again', 'member_search_shipments');
        }


        $active_subscription = $this->user()->getLastSubscription();
        if (is_null($active_subscription) || ($active_subscription->getActive() != 1)) {
            return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        }

        $today = new \DateTime('now');
        if ($this->get('session')->get('timezone')) {
            $tz = new \DateTimeZone($this->get('session')->get('timezone'));
            $today = new \DateTime('now', $tz);
        }

        $historyValue = $active_subscription->getHistoryValue();
        $diff = abs(strtotime($today->format("Y-m-d")) - strtotime($s_cache->date_range->from));

        $maxHistory = $diff / (3600 * 24 * 30);

        if (floor($maxHistory) > $historyValue) {
            return $this->errorRedirect('You dont have any history more than ' . $historyValue . ' month. Please update your subscription.', 'member_search_shipments');
        }

        $fields = array('consignee_name',
            'consignee_address',
            'shipper_name',
            'shipper_address',
            'product_desc',
            'notify_party_names',
            'notify_party_address',
            'bill_of_lading',
            'master_bill_of_lading',
            'container_id',
            'place_of_receipt',
            'vessel_name',
            'carrier_sasc_code');


        $queryDSL = $this->queryDSL($s_cache, "actual_arrival_date", "product_desc", $fields, $size, $page);

        $query = new \Elastica\Query();

        $query->setRawQuery($queryDSL);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')));
        $searchable = new Index($client, 'stradetegy-unvalid-date');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);


        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('member_search_result_not_valid_date', array('page' => $page, 's_cache' => $this->s_cache_json));
        }, $options);

        $fieldUsCustom = new FieldUsCustom();
        if (empty($s_cache->column))
            $fieldUsCustom->field_us_custom = array('actual_arrival_date', 'consignee_name', 'shipper_name',
                'quantity', 'weight', 'product_desc', 'marks_and_numbers', 'bill_of_lading');
        else {
            $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
        }

        $params = array(
            'pagination' => $html,
            'resHybird' => $res->getCurrentPageResults()->getResults(),
            'column' => $fieldUsCustom->field_us_custom,
            );

        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add:not_valid_date.html.twig', $params),
                    's_cache' => ''
                    )
                )
            );

    }

    /**
     * @Route(
     *   "/search-shipments-contact-company/us-imports/{company_as}/{page}",
     *   name     = "member_search_result_contact_company_us_imports"
     * )
     *
     */
    public function contactCompanyAction($company_as, $page = 1)
    {
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        $size = 0;

        $this->s_cache_json = $this->getRequest()->get('s_cache');
        $this->company_as = $company_as;        

        $fields = array('product_desc',
            'consignee_name',
            'consignee_address',
            'shipper_name',
            'shipper_address',
            'notify_party_names',
            'loading_port',
            'bill_of_lading',
            'vessel_name',
            'master_bill_of_lading',
            'country',
            'marks_numbers',
            'carrier_sasc_code',
            "container_id",
            "unloading_port");


        
        $aggs = array('aggs' => array(
            "UIDs" => array(
                "terms" => array(
                    "field" => "slug_".$company_as,
                    "size" => 200,
                    "exclude" => array("na")
                    )
                )
            )
        );        


        $queryDSL = $this->queryDSL($s_cache, "actual_arrival_date", "product_desc", $fields, $size, $page,$aggs);

        

        $query = new \Elastica\Query();

        $query->setRawQuery($queryDSL);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')
            ));

        $searchable = new Index($client, 'trade-usa-imports-*');
        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);



        $fieldUsCustom = new FieldUsCustom();
        if (empty($s_cache->column))
            $fieldUsCustom->field_us_custom = array('actual_arrival_date', 'consignee_name', 'shipper_name',
                'quantity', 'weight', 'product_desc', 'marks_and_numbers', 'bill_of_lading');
        else {
            $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
        }

        $entityChoiceIsSeacrh = $this->repo("JariffMemberBundle:ContactCompany")->findBy(array(
            'member' => $this->getUser()->getId(),
            's_cache' => $this->s_cache_json,
            'process' => 0
            ));

        $checklist = array();
        foreach ($entityChoiceIsSeacrh as $row) {
            $checklist[$row->getSlug()] = 'checked';
        }


        
        $params = array(
            // 'pagination' => $html,
            'resHybird' => $res->getCurrentPageResults()->getAggregations()['UIDs']['buckets'],
            'column' => $fieldUsCustom->field_us_custom,
            'company_as' => $company_as,
            's_cache' => $this->s_cache_json,
            'checklist' => $checklist,
            'entities' => $entityChoiceIsSeacrh

            );

        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add:contact_company.html.twig', $params),
                    's_cache' => '',
                    'totalSelect' => count($entityChoiceIsSeacrh)
                    )
                )
            );

    }

    /**
     * @Route(
     *   "/search-shipments-contact-company-page-table/us-imports/{company_as}/{page}",
     *   name     = "member_search_result_contact_company_page_table_us_imports"
     * )
     *
     */
    public function pageTableCompanyAction($company_as, $page = 1)
    {
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        $size = 10;


        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');
        $this->company_as = $company_as;

        // if (!isset($s_cache->time)) {
        //     return $this->errorRedirect('Your search is expired, please submit again', 'member_search_shipments');
        // }


        // $active_subscription = $this->user()->getLastSubscription();
        // if (is_null($active_subscription) || ($active_subscription->getActive() != 1)) {
        //     return $this->errorRedirect('You dont have any active subscription. Please update your subscription.', 'dashboard_member');
        // }

        // $today = new \DateTime('now');
        // if ($this->get('session')->get('timezone')) {
        //     $tz = new \DateTimeZone($this->get('session')->get('timezone'));
        //     $today = new \DateTime('now', $tz);
        // }

        // $historyValue = $active_subscription->getHistoryValue();
        // $diff = abs(strtotime($today->format("Y-m-d")) - strtotime($s_cache->date_range->from));

        // $maxHistory = $diff / (3600 * 24 * 30);

        // if (floor($maxHistory) > $historyValue) {
        //     return $this->errorRedirect('You dont have any history more than ' . $historyValue . ' month. Please update your subscription.', 'member_search_shipments');
        // }

        $fields = array('product_desc',
            'consignee_name',
            'consignee_address',
            'shipper_name',
            'shipper_address',
            'notify_party_names',
            'last_vist_foreign_port',
            'bill_of_lading',
            'vessel_name',
            'master_bill_of_lading',
            'country',
            'marks_numbers',
            'carrier_sasc_code',
            "container_id",
            "unloading_port");


        $queryDSL = $this->queryDSL($s_cache, "actual_arrival_date", "product_desc", $fields, $size, $page);

        $query = new \Elastica\Query();

        $query->setRawQuery($queryDSL);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')
            ));

        $searchable = new Index($client, 'trade-usa-imports-*');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);


        $res->setMaxPerPage($size);
        $res->setCurrentPage($page);
        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($res, function ($page) {
            return $this->generateUrl('member_search_result_contact_company_us_imports', array('company_as' => $this->company_as, 'page' => $page, 's_cache' => $this->s_cache_json));
        }, $options);

        $fieldUsCustom = new FieldUsCustom();
        if (empty($s_cache->column))
            $fieldUsCustom->field_us_custom = array('actual_arrival_date', 'consignee_name', 'shipper_name',
                'quantity', 'weight', 'product_desc', 'marks_and_numbers', 'bill_of_lading');
        else {
            $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
        }

        $entityChoiceIsSeacrh = $this->repo("JariffMemberBundle:ContactCompany")->findBy(array(
            'member' => $this->getUser()->getId(),
            's_cache' => $this->s_cache_json,
            'process' => 0
            ));

        $checklist = array();
        foreach ($entityChoiceIsSeacrh as $row) {
            $checklist[$row->getSlug()] = 'checked';
        }

        $params = array(
            'pagination' => $html,
            'resHybird' => $res->getCurrentPageResults()->getResults(),
            'column' => $fieldUsCustom->field_us_custom,
            'company_as' => $company_as,
            's_cache' => $this->s_cache_json,
            'checklist' => $checklist,
            'entities' => $entityChoiceIsSeacrh
            );

        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add:table_contact_company.html.twig', $params),
                    's_cache' => '',
                    'totalSelect' => count($entityChoiceIsSeacrh)
                    )
                )
            );

    }

    /**
     * @Route(
     *   "/search-contact-company-us-import",
     *   name     = "member_search_contact_company_us_imports"
     * )
     * @Method("POST")
     *
     */
    public function submitSearchContactCompanyCategoryAction(Request $request, $page = 1)
    {
        $company_as = $request->get('company_as');
        $q_search = $request->get('q_search');


        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
        $size = 10;


        if (isset($s_cache->size))
            $size = $s_cache->size;

        $this->s_cache_json = $this->getRequest()->get('s_cache');
        $this->company_as = $company_as;

        $fields = array('product_desc',
            'consignee_name',
            'consignee_address',
            'shipper_name',
            'shipper_address',
            'notify_party_names',
            'loading_port',
            'bill_of_lading',
            'vessel_name',
            'master_bill_of_lading',
            'country',
            'marks_numbers',
            'carrier_sasc_code',
            "container_id",
            "unloading_port");

        if (!empty($q_search)) {
            $add_filter['search'] = array_merge((array)$s_cache->search, array((object)array(
                'condition' => 'and',
                'collect' => $company_as,
                'q' => $q_search
                )));

            $s_cache = (object)array_merge((array)$s_cache, $add_filter);

            $this->s_cache_json = $util->encrypted(json_encode($s_cache));
        }

        $aggs = array('aggs' => array(
            "UIDs" => array(
                "terms" => array(
                    "field" => "slug_".$company_as,
                    "size" => 200,
                    "exclude" => array("na")
                    )
                )
            )
        );        


        $queryDSL = $this->queryDSL($s_cache, "actual_arrival_date", "product_desc", $fields, $size, $page,$aggs);

        $query = new \Elastica\Query();

        $query->setRawQuery($queryDSL);

        $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')
            ));

        $searchable = new Index($client, 'trade-usa-imports-*');

        $adapter = new ElasticaAdapter($searchable, $query);
        $res = new Pagerfanta($adapter);        

        $fieldUsCustom = new FieldUsCustom();
        if (empty($s_cache->column))
            $fieldUsCustom->field_us_custom = array('actual_arrival_date', 'consignee_name', 'shipper_name',
                'quantity', 'weight', 'product_desc', 'marks_and_numbers', 'bill_of_lading');
        else {
            $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
        }

        $entityChoiceIsSeacrh = $this->repo("JariffMemberBundle:ContactCompany")->findBy(array(
            'member' => $this->getUser()->getId(),
            's_cache' => $this->s_cache_json,
            'process' => 0
            ));

        $checklist = array();
        foreach ($entityChoiceIsSeacrh as $row) {
            $checklist[$row->getSlug()] = 'checked';
        }

        $params = array(
            'resHybird' => $res->getCurrentPageResults()->getAggregations()['UIDs']['buckets'],
            'column' => $fieldUsCustom->field_us_custom,
            'company_as' => $company_as,
            's_cache' => $this->s_cache_json,
            'checklist' => $checklist,
            'entities' => $entityChoiceIsSeacrh,

            );

        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add:table_contact_company.html.twig', $params),
                    's_cache' => '',
                    'totalSelect' => count($entityChoiceIsSeacrh)
                    )
                )
            );
    }

    /**
     * @Route(
     *   "/search-add-contact-company-us-import",
     *   name     = "member_search_add_contact_company_us_imports"
     * )
     *
     *
     */
    public function submitAddContactCompanyCategoryAction(Request $request)
    {
        $company_as = $request->get('company_as');
        $s_cache = $request->get('s_cache');
        $slug = $request->get('slug');
        $company_name = $request->get('company_name');
        $country_type = $request->get('country_type');


        $contactCompany = new ContactCompany();
        $contactCompany->setMember($this->getUser());
        $contactCompany->setCompanyAs($company_as);
        $contactCompany->setCompanyName($company_name);
        $contactCompany->setCountryType($country_type);
        $contactCompany->setSlug($slug);
        $contactCompany->setSCache($s_cache);

        $this->em()->persist($contactCompany);
        $this->em()->flush();

        $entities = $this->repo("JariffMemberBundle:ContactCompany")->findBy(array(
            'member' => $this->getUser()->getId(),
            's_cache' => $s_cache,
            'process' => 0
            ));

        $params = array(
            'entities' => $entities,
            's_cache' => $s_cache,

            );


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add:table_selected_company.html.twig', $params),
                    's_cache' => '',
                    'totalSelect' => count($entities)
                    )

                ));


    }

    /**
     * @Route(
     *   "/search-delete-contact-company-us-import",
     *   name     = "member_search_delete_contact_company_us_imports"
     * )
     *
     *
     */
    public function deleteAddContactCompanyCategoryAction(Request $request)
    {

        $slug = $request->get('slug');

        $s_cache = $request->get('s_cache');

        $entity = $this->repo("JariffMemberBundle:ContactCompany")->findOneBy(array(
            'member' => $this->getUser()->getId(),
            'slug' => $slug
            ));


        if ($entity) {
            $this->em()->remove($entity);
            $this->em()->flush();
        }


        $entities = $this->repo("JariffMemberBundle:ContactCompany")->findBy(array(
            'member' => $this->getUser()->getId(),
            's_cache' => $s_cache,
            'process' => 0
            ));

        $params = array(
            'entities' => $entities,
            's_cache' => $s_cache,

            );


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add:table_selected_company.html.twig', $params),
                    's_cache' => '',
                    'totalSelect' => count($entities)
                    )

                ));


    }

    /**
     * @Route(
     *   "/search-submit-selected-company-us-import",
     *   name     = "member_search_submit_selected_company_us_imports"
     * )
     *
     *
     */
    public function submitSelectedContactCompanyCategoryAction(Request $request)
    {

        $s_cache = $request->get('s_cache');


        $entities = $this->repo("JariffMemberBundle:ContactCompany")->findBy(array(
            'member' => $this->getUser()->getId(),
            's_cache' => $s_cache,
            'process' => 0,

            ));

        foreach ($entities as $row) {
            $row->setProcess(1);
            $this->em()->persist($row);
            $this->em()->flush();
        }


        $params = array(
            'entities' => $entities,
            's_cache' => $s_cache,
            );


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add:table_selected_company.html.twig', $params),
                    's_cache' => ''
                    )

                ));


    }

    /**
     * @Route(
     *   "/search-submit-contac-history-company-us-import",
     *   name     = "member_search_submit_contact_history_company_us_imports"
     * )
     *
     *
     */
    public function contactHistoryCompanyCategoryAction(Request $request)
    {

        $s_cache = $request->get('s_cache');
        $company_as = $request->get('company_as');

        if (empty($company_as))
            $company_as = 'consignee_name';

        $entities = $this->repo("JariffMemberBundle:ContactCompany")->findBy(array(
            'member' => $this->getUser()->getId(),
            'process' => 1,
            'company_as' => $company_as,
            'country_type' => 'us-imports',
            ), array('request_at' => 'DESC'));

        $params = array(
            'entitiesContactHistory' => $entities,
            's_cache' => $s_cache,
            );


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add:table_contact_history.html.twig', $params),
                    's_cache' => ''
                    )

                ));


    }

    /**
     * @Route(
     *   "/search-shipments-result-ajax/us-imports/jsonp",
     *   name     = "member_search_shipments_result_us_imports_ajax_jsonp",
     *  options={"expose"=true}
     * )
     *
     */
    public function resultAjaxJsonpAction(Request $request)
    {

        // if (strtolower(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) !== 'xmlhttprequest') {
        //     // I'm AJAX!
        //     die('Access denied');
        // }



        $active_subscription = $this->user()->getLastSubscription();

        if (is_null($active_subscription) || !$active_subscription->isActive()) 
        {
           $jsonp = array(
            "errors" => true,
            'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add\Form:error_on_search.html.twig', 
                array( "title" => '<span class="icon-warning-info"></span> Your subscription',
                    "message" => "You dont have any active subscription. Please update your subscription.",
                    "button" => array(
                        '<a href="'.$this->generateUrl("support_ticket_inbound_form").'" class="btn btn-info"><span class="icon-user"></span> Customer Service</a>'
                        )
                    )
                ),

            );


           return new Response(
            json_encode(
                $jsonp
                )
            );
           return $this->errorRedirect('', 'dashboard_member');
       }

       $util = new StringTwig();
       $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));
       $size = 15;

       $page = $request->get('page');
       $rows = $request->get('rp');

       

       if(empty($page)) $page = 1;

       if(isset($rows)) $size = $rows;

       $this->s_cache_json = $this->getRequest()->get('s_cache');        

       $today = new \DateTime('now');
       if ($this->get('session')->get('timezone')) 
       {
        $tz = new \DateTimeZone($this->get('session')->get('timezone'));
        $today = new \DateTime('now', $tz);
    }
    
    $dateSubscriptions = $active_subscription->getDateUpdate();
    $dateSubscriptions->modify("+ " . $active_subscription->getMonthValue() . " month");

    $diff = abs(strtotime($today->format("Y-m-d H:i:s")) - $s_cache->time);
    $maxTime = $diff / 3600;        

    if (!isset($s_cache->time)) {
        $jsonp = array(
            "errors" => true,
            'message' => "Your URL is denied, please submit again",
            "title" => "Error search",
            'buttons' => array(
                array(
                    'label' => 'New Search',
                    'action' => $this->generateUrl("member_search_shipments")
                    ),
                array(
                    'cssClass' => 'btn btn-info',
                    'icon' => 'icon-user',
                    'label' => 'Customer Service',
                    'action' => $this->generateUrl("support_ticket_inbound_form")
                    )
                )

            );


        return new Response(
            json_encode(
                $jsonp
                )
            );     
    }

    

    $fields = array('product_desc',
        'consignee_name',
        'consignee_address',
        'shipper_name',
        'shipper_address',
        'notify_party_names',
        'loading_port',
        'bill_of_lading',
        'vessel_name',
        'master_bill_of_lading',
        'country',
        'marks_numbers',
        'carrier_sasc_code',
        "container_id",
        "unloading_port");

    $queryDSL = $this->queryDSL($s_cache, "actual_arrival_date", "product_desc", $fields, $size, $page);

    $query = new \Elastica\Query();
    $query->setRawQuery($queryDSL);

    $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')
        ));

    $searchable = new Index($client, 'trade-usa-imports-*');

    $adapter = new ElasticaAdapter($searchable, $query);

    $res = new Pagerfanta($adapter);

    $res->setMaxPerPage($size);
    $res->setCurrentPage($page);

    $entity = new SearchEmbed();

    if ($maxTime > 1) {
     $jsonp = array(
        "errors" => true,
        'message' => "Your URL is expired, please submit again",
        "title" => "Error search",
        'buttons' => array(
            array(
                'label' => 'New Search',
                'action' => $this->generateUrl("member_search_shipments")
                ),
            array(
                'cssClass' => 'btn btn-info',
                'icon' => 'icon-user',
                'label' => 'Customer Service',
                'action' => $this->generateUrl("support_ticket_inbound_form")
                )
            )

        );


     return new Response(
        json_encode(
            $jsonp
            )
        );  
 }

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

$fieldUsCustom = new FieldUsCustom();
if (empty($s_cache->column))
    $fieldUsCustom->field_us_custom = array('actual_arrival_date', 'product_desc',
        'consignee_name', 'consignee_address', 'shipper_name', 'shipper_address',
        'notify_party_name', 'notify_party_address', 'quantity', 'quantity_unit',
        'teu', 'hs_code', 'marks_and_numbers', 'loading_port', 'mode_of_transportation',
        'weight', 'container_id','carrier_sasc_code','bill_of_lading','master_bill_of_lading',
        'place_of_receipt','vessel_name','container_type','voyage'
        );
else {
    $fieldUsCustom->field_us_custom = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
}

$columnMd = array();
$allRes = array();
$util2 = new StringExtension();
$util->setEm($this->em());   

if ($res->getNbResults() == 0) {

    $inbound = new Inbound();
    $inbound->setDateCreate(new \DateTime('now'));
    $inbound->setEmail($this->getUser()->getUsername());
    $inbound->setDescription($this->s_cache_json);
    $inbound->setMember($this->getUser());
    $inbound->setVisitedPage($this->generateUrl('member_search_shipments_result_us_imports', array('s_cache' => $this->s_cache_json)));

    $this->em()->persist($inbound);
    $this->em()->flush();

    $jsonp = array(
        "errors" => true,
        'message' => "Sorry, we could not find data the keyword you enter",
        "title" => "Empty Results",
        'buttons' => array(
            array(
                'label' => 'New Search',
                'action' => $this->generateUrl("member_search_shipments")
                ),
            array(
                'cssClass' => 'btn btn-info',
                'icon' => 'icon-user',
                'label' => 'Customer Service',
                'action' => $this->generateUrl("support_ticket_inbound_form")
                )
            )

        );


    return new Response(
        json_encode(
            $jsonp
            )
        );
}

if ($this->get('session')->get('checkSubmit')) {

    $cek = $this->repo("JariffMemberBundle:MemberTodaySearch")->findOneBy(array(
        'member' => $this->getUser()->getId(),
        'date_search' => $today
        ));

    if ($active_subscription->getEverythingPlan()) {
        $active_subscription->setTotalSearches($active_subscription->getTotalSearches() + 1);
        $this->em()->persist($active_subscription);
        $this->em()->flush();
    } else {

        if (!empty($cek)) {
            if (($cek->getTotal() + 1) > $active_subscription->getSearchValue()) {
                $jsonp = array(
                    "errors" => true,
                    'message' => "Sorry, Total number of searches for Total Package today is limited",
                    "title" => "Search is Limited",
                    'buttons' => array(
                        array(
                            'cssClass' => 'btn btn-info',
                            'icon' => 'icon-user',
                            'label' => 'Customer Service',
                            'action' => $this->generateUrl("support_ticket_inbound_form")
                            )
                        )

                    );


                return new Response(
                    json_encode(
                        $jsonp
                        )
                    );

                

                    // return $this->errorRedirect('Sorry, Total number of searches for Total Package today is limited', 'member_search_shipments');
            }
        }

        if (empty($cek)) {
            $newIsert = new MemberTodaySearch();
            $newIsert->setDateSearch($today);
            $newIsert->setMember($this->getUser());


            $newIsert->setTotal($newIsert->getTotal() + 1);
            $this->em()->persist($newIsert);
            $this->em()->flush();
        } else {
            $cek->setTotal($cek->getTotal() + 1);
            $this->em()->persist($cek);
            $this->em()->flush();
        }
    }

    $history = array(
        "total_row" => $res->getNbResults(),
        "date_search" => $today->format("Y-m-d"),
        "time_search" => $today->format("H:i:s"),
        "timezone" => $today->format("T"),
        "user_id" => $this->getUser()->getId(),
        "query" => $this->s_cache_json,
        "priority" => 0,
        "set_pin" => "unpin"
        );

    $doc[] = new \Elastica\Document('', $history, "user_logs", 'trade-user-logs-' . $today->format("Y"));

    $client->addDocuments($doc);
    $client->getIndex('trade-user-logs-' . $today->format("Y"))->refresh();
    $this->get('session')->remove('checkSubmit');
    
}



$urlChanges = null;


$entity = $this->repo('JariffMemberBundle:MemberSetting')->findOneByMember($this->getUser());

if ($entity) {
    $weightOri = $entity->getWeight();
} else {
    $weightOri = 'KG';
}


$allRes = array();


$inumber = ($page == 1) ? 1 : (($page - 1) * $rows) + 1;
foreach ($res->getCurrentPageResults()->getResults() as $r) {
    $columnMd['number'] = $inumber++;
    $columnMd['detail_company'] = ' <a href="#"
    data-whatever="' . $r->getHit()["_source"]['consignee_name'] . '"
    class="j-modal-company-one-shipments"
    data-toggle="modal" data-target="#myModal" data-backdrop="static"
    data-url="' . $this->generateUrl('company_display_one_shipments', array(
        'index' => $r->getHit()["_index"], 'type' => $r->getHit()["_type"], 'objId' => $r->getHit()["_id"]
        )) . '"><span class="icon-search3"></span></a>';

    foreach ($fieldUsCustom->field_us_custom as $col) {

        if ($col == "product_desc") {

            $columnMd[$col] = preg_replace("/<br\W*?\/>/",", ",$r->getHit()["_source"][$col]);

        } elseif ($col == "weight") {
            $columnMd[$col] = $util->setting_gross_weight($r->getHit()["_source"][$col], $r->getHit()["_source"]['weight_unit'], $this->getUser());
        }  elseif ($col == "actual_arrival_date") {
            $columnMd[$col] = date("m/d/Y",strtotime($r->getHit()["_source"][$col]));
        } elseif ($col == "marks_and_numbers") {
            $columnMd[$col] = $util2->readmore($r->getHit()["_source"][$col]);
        } elseif ($col == "weight_unit") {
            $columnMd[$col] = $weightOri;
        } elseif ($col == "mode_of_transportation") {
            $columnMd[$col] = $util->us_column_code_transportation($r->getHit()["_source"][$col]);
        } else {
            $columnMd[$col] = preg_replace("/<br\W*?\/>/",", ",$r->getHit()["_source"][$col]);
        }

        if(isset($r->getHit()["highlight"][$col][0])){
            $match = $r->getHit()["highlight"];

            $columnMd[$col] = preg_replace("/<br\W*?\/>/",", ",$match[$col][0]);                
        }

        $columnMd[$col] = $util->na($columnMd[$col]);
    }

    $allRes[] = $columnMd;
}


$total = $res->getNbResults() / $size;

$keywords = '<div class="alert alert-info"> Your search of Data for :';

foreach ($s_cache->search  as $key => $value) {
    if(isset($value->condition)){
        $keywords .= strtoupper($value->condition);
    }

    $keywords .= $util->globalfield($value->collect,$s_cache->type->custom_country.'_'.$s_cache->type->custom_data);

    $keywords .= ' <a href="javascript:void(0)" class="j-popover-q label label-danger"
    data-container="body" data-toggle="popover" data-trigger="focus"
    data-placement="bottom">'.$value->q.'</a>';
}
$took = $res->getCurrentPageResults()->getResponse()->getData()["took"] / 1000;
$keywords .= 'generated '.$util->ribuan($res->getNbResults()).' results in '.$took.'
seconds.
</div>';

$jsonp = array(
    "url_change" => $urlChanges,
    'took' => $res->getCurrentPageResults()->getResponse()->getData()["took"] / 1000,
    "records_ribuan" => $util->ribuan($res->getNbResults()),
    "records" => $res->getNbResults(),
    "page" => $page,
    "total" =>$res->getNbResults(),
    "rows" => $allRes,
    "keywords" => $keywords
    );

return new Response(
    json_encode(
        $jsonp
        )
    );
}

    /**
     * @Route(
     *   "/save-bol-shipments-us-import",
     *   name     = "member_save_bol_us_imports"
     * )
     *
     *
     */
    public function saveBOLShipmentsAction(Request $request)
    {

        $slug = $request->get('objId'); // diganti id elasticsearch

        $name_company = $request->get('name_company');

        $entity = $this->repo("JariffMemberBundle:SavedCompany")->findOneBy(array(
            'member' => $this->getUser()->getId(),
            'slug_company' => $slug
            ));


        if ($entity) {
            return new Response(
                json_encode(
                    array(
                        'success' => true,
                        'message' => "You have been saving this BOL",

                        )

                    ));
        }

        $entity = new SavedCompany();
        $entity->setMember($this->getUser());
        $entity->setCategory("imports");
        $entity->setSlugCompany($slug);
        $entity->setCountryOrigin("us");
        $entity->setNameCompany($name_company);
        $entity->setIsCompare(3);


        $this->em()->persist($entity);
        $this->em()->flush();


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'message' => "Success save",

                    )

                ));


    }

}

