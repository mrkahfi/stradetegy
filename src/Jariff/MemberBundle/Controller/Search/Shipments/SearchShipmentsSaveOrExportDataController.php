<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\Search\Shipments;

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ExportsSearchShipmentsFormType;
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

use Pagerfanta\View\TwitterBootstrap3View;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ElasticaAdapter;
use Elastica\Index;
use Elastica\Query as QueryEs;
use Elastica\Client as ClientEs;

/**
 *
 * @Route("/member")
 */
class SearchShipmentsSaveOrExportDataController extends BaseController
{

    /**
     * @Route(
     *   "/member-display-modal-save-shipments/{slug_country_subscription}",
     *   name     = "member_display_modal_save_shipments",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function displayMemberSaveShipmentsAction($slug_country_subscription)
    {

        $entity = new SavedSearch();
        $entity->setSlugCountrySubscription($slug_country_subscription);

        $form = $this->createForm(new SaveSearchShipmentsFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));


        $params = array(
            'formSaveShipments' => $form->createView(),

            's_cache' => $this->getRequest()->get('s_cache')
            );

        return new Response(
             $this->renderView('JariffMemberBundle:Search\Shipments\Add:modal_form_save.html.twig', $params)
                
            );
    }

    /**
     * @Route(
     *   "/save-shipments-submit",
     *   name     = "member_display_modal_save_shipments_submit"
     * )
     * @Method("POST")
     *
     */
    public function submitSaveSearchShipmentsAction(Request $request)
    {
        $entity = new SavedSearch();

        $form = $this->createForm(new SaveSearchShipmentsFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $form->handleRequest($request);


        $s_cache = $this->getRequest()->get('s_cache');


        if ($form->isValid()) {

            $entity->setMember($this->getUser());
            $entity->setQuery($s_cache);

            $data = $form->getData();


            $activityFeed = new MemberActivityFeed();
            $activityFeed->setMember($this->getUser());
            $activity = "Your saved
            <a href='" . $this->generateUrl("member_search_save_search") . "'>" . $data->getNameOfSearch() . "</a>";
            $activityFeed->setDescription($activity);
            $activityFeed->setUrls($this->generateUrl("member_search_save_search"));

            $this->persist($activityFeed);
            $this->flush();

            if ($data->getIsAlert()) {
                $entity->setIsAlert(1);

                $activityFeed = new MemberActivityFeed();
                $activityFeed->setMember($this->getUser());
                $activityFeed->setDescription("You created email alert for 
                    <a href='" . $this->generateUrl("member_alerts_save_search") . "'>" . $data->getNameOfSearch() . "</a>");
                $activityFeed->setUrls($this->generateUrl("member_search_save_search"));

                $this->persist($activityFeed);
                $this->flush();
            }

            $this->persist($entity);
            $this->flush();

            $message = "Success Save Search Shipments";
        } else {
            $message = $form->getErrorsAsString();
        }


        return new Response(
            "<div style='text-align:center;padding:10px'><h5>$message</h5><br/>" .
                    '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'
                    . "</div>"
                    
            );

    }

    /**
     * @Route(
     *   "/member-display-modal-exports-shipments/{collection}",
     *   name     = "member_display_modal_exports_shipments",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function displayMemberExportsShipmentsAction($collection)
    {

        $entity = new ExportTools();
        $entity->setEmail($this->getUser()->getEmail());
        $entity->setCollection($collection);
        $entity->setMaxDownload(1);
        $entity->setFileType('xls');

        $form = $this->createForm(new ExportsSearchShipmentsFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $active_subscription = $this->user()->getLastSubscription();


        if ($active_subscription->getEverythingPlan()) {
            $message = "You have performed " . $active_subscription->getDownloadLimit() . " downloads this billing period";
        } else {
            $message = "You have used " . $active_subscription->getDownloadLimit() . " of " . $active_subscription->getDownloadValue() . " downloads";
        }
        $params = array(
            'formExportsShipments' => $form->createView(),
            's_cache' => $this->getRequest()->get('s_cache'),
            'downloadLimit' => $active_subscription->getDownloadLimit(),
            'downloadValue' => $active_subscription->getDownloadValue(),
            'message' => $message
            );


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Shipments\Add:modal_form_exports.html.twig', $params),
                    's_cache' => ''
                    )
                )
            );
    }

    /**
     * @Route(
     *   "/exports-shipments-submit",
     *   name     = "member_display_modal_exports_shipments_submit"
     * )
     * @Method("POST")
     *
     */
    public function submitExportsSearchShipmentsAction(Request $request)
    {
        $entity = new ExportTools();

        $form = $this->createForm(new ExportsSearchShipmentsFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));

        $form->handleRequest($request);


        $s_cache = $this->getRequest()->get('s_cache');
        $collection = $this->getRequest()->get('collection'); 

        if ($form->isValid()) {


            if ($form->getData()->getDownloadFrom() >= $form->getData()->getDownloadTo()) {
                return new Response(
                    json_encode(
                        array(
                            'success' => 'false',
                            'message' => 'from more than to or cant be same'
                            )
                        )
                    );
            }


            $active_subscription = $this->user()->getLastSubscription();
            $downloadLimit = $active_subscription->getDownloadValue() - $active_subscription->getDownloadLimit();
            $totalDownloadRequest = $form->getData()->getDownloadTo() - ($form->getData()->getDownloadFrom() - 1);


            if ($active_subscription->getEverythingPlan() == 0) {

                if ($form->getData()->getMaxDownload()) {
                    if ($downloadLimit >= 40000) {
                        $entity->setDownloadFrom(1);
                        $entity->setDownloadTo(40000);

                        $totalDownload = $active_subscription->getDownloadLimit() + 40000;
                        $active_subscription->setDownload($totalDownload);
                        $active_subscription->setDownloadLimit($totalDownload);

                        $totalDownloadRequest = 40000;
                    } else {
                        $entity->setDownloadFrom(1);
                        $entity->setDownloadTo($downloadLimit);

                        $totalDownload = $active_subscription->getDownloadLimit() + $totalDownloadRequest;
                        $active_subscription->setDownload($totalDownload);
                        $active_subscription->setDownloadLimit($totalDownload);
                    }

                }

                if ($downloadLimit < $totalDownloadRequest) {
                    return new Response(
                        json_encode(
                            array(
                                'success' => true,
                                'message' => 'Error max download, You have ' . $downloadLimit . ' limit'
                                )
                            )
                        );
                }


                
            } else {
                if ($form->getData()->getMaxDownload()) {
                    $entity->setDownloadFrom(1);
                    $entity->setDownloadTo(40000);

                    $totalDownload = $active_subscription->getDownloadLimit() + 40000;
                    $active_subscription->setDownloadLimit($totalDownload);
                }else{
                    $totalDownload = $active_subscription->getDownloadLimit() + $totalDownloadRequest;
                    $active_subscription->setDownloadLimit($totalDownload);
                }                
            }


            $this->persist($active_subscription);
            $this->flush();


            $entity->setCollection($collection);
            $entity->setMember($this->getUser());
            $entity->setQuery($s_cache);
            $entity->setFileName($form->getData()->getFileName() . '-' . date("ymdhis"));

            $this->persist($entity);
            $this->flush();

            $success = true;
            $message = "Success save, request still on que";

            $activityFeed = new MemberActivityFeed();
            $activityFeed->setMember($this->getUser());
            $activityFeed->setDescription("Your exported search
                <a href='" . $this->generateUrl("member_search_download") . "'>" . $form->getData()->getFileName() . "</a>");
            $activityFeed->setUrls($this->generateUrl("member_search_download"));

            $this->persist($activityFeed);
            $this->flush();


        } else {
            $success = false;
            $message = $form->getErrorsAsString();
        }


        return new Response(
            json_encode(
                array(
                    'success' => $success,
                    'message' => $message,
                    'download_now' => $form->getData()->getSendMail(),
                    'file_name' => $entity->getFileName()
                    )
                )
            );

    }

    /**
     * @Route(
     *   "/exports-shipments-download-now-interval/{file_name}",
     *   name     = "member_display_modal_exports_shipments_download_now_interval",
     *  options={"expose"=true}
     * )
     * 
     *
     */
    public function submitExportsSearchShipmentsDownloadNowAction($file_name,Request $request)
    {
        $cek = $this->repo("JariffMemberBundle:ExportTools")->findOneBy(array('file_name' => $file_name));

        if (!$cek) {
            return new Response(
                json_encode(
                    array(
                        'success' => 0,
                        'message' => "Sorry, we couldn't found your request",

                        )
                    )
                );
        }else{
            if($cek->getProcess() == 0){
                return new Response(
                    json_encode(
                        array(
                            'success' => 1,
                            'message' => "Your download is successful. Open downloaded file from your browser or click the following link to open: <a href='".$request->getScheme() . '://' . $request->getHttpHost()."/convert/".$file_name.".".$cek->getFileType()."'>".$file_name."</a>",
                            'link' => $request->getScheme() . '://' . $request->getHttpHost()."/convert/".$file_name.".".$cek->getFileType()
                            )
                        )
                    );
            }

        }

        return new Response(
                    json_encode(
                        array(
                            'codes' => 100,
                            'process' => $cek->getProcentage()
                            )
                        )
                    );
    }

    /**
     * @Route(
     *   "/member-display-modal-save-company/{slug_company}/{category}/{name_index}/{country_origin}/{name_company}",
     *   name     = "member_save_company",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function saveCompanyAction($slug_company, $category, $name_index, $country_origin, $name_company)
    {

        $detail = $this->getRequest()->get('detail');

        $cek = $this->repo("JariffMemberBundle:SavedCompany")->findOneBy(array('member' => $this->getUser(), 'category' => $category, 'slug_company' => $slug_company));

        if (!$cek) {
            $entity = new SavedCompany();
            $entity->setMember($this->getUser());
            $entity->setSlugCompany($slug_company);
            $entity->setCategory($category);
            $entity->setNameIndex($name_index);
            $entity->setIsCompare(0);
            $entity->setNameCompany($name_company);
            $entity->setCountryOrigin($country_origin);
            $entity->setDetail($detail);


            $this->persist($entity);
            $this->flush();

            $mess = 'Success Save Company';
        } else {
            $mess = 'Company has been saved';
        }

        $param = array(
            'entityBuyer' => $this->repo("JariffMemberBundle:SavedCompany")->findBy(array('member' => $this->getUser(), 'category' => 'buyers')),
            'entitySupplier' => $this->repo("JariffMemberBundle:SavedCompany")->findBy(array('member' => $this->getUser(), 'category' => 'suppliers')),
            'active_on' => $category,
            'mess' => $mess
            );


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Compare:list_save.html.twig', $param),
                    )
                )
            );
    }

    /**
     * @Route(
     *   "/member-display-modal-delete-company/{slug_company}/{category}/{name_index}/{country_origin}/{name_company}",
     *   name     = "member_delete_company",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function deleteCompanyAction($slug_company, $category, $name_index, $country_origin, $name_company)
    {

        $cek = $this->repo("JariffMemberBundle:SavedCompany")->findOneBy(array('member' => $this->getUser(), 'category' => $category, 'slug_company' => $slug_company));

        if ($cek) {
            $this->em()->remove($cek);
            $this->flush();

            $mess = 'Success Delete Company';
        } else {
            $mess = 'No data company';
        }

        $param = array(
            'entityBuyer' => $this->repo("JariffMemberBundle:SavedCompany")->findBy(array('member' => $this->getUser(), 'category' => 'buyers')),
            'entitySupplier' => $this->repo("JariffMemberBundle:SavedCompany")->findBy(array('member' => $this->getUser(), 'category' => 'suppliers')),
            'active_on' => $category,
            'mess' => $mess
            );


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Compare:list_save.html.twig', $param),
                    )
                )
            );
    }

    /**
     * @Route(
     *   "/member-display-modal-compare-company/{slug_company}/{category}/{name_index}/{country_origin}/{name_company}",
     *   name     = "member_compare_company",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function compareCompanyAction($slug_company, $category, $name_index, $country_origin, $name_company)
    {
        $detail = $this->getRequest()->get('detail');
        $cek = $this->repo("JariffMemberBundle:SavedCompany")->findOneBy(array('member' => $this->getUser(), 'category' => $category, 'slug_company' => $slug_company));

        if (!$cek) {
            $entity = new SavedCompany();
            $entity->setMember($this->getUser());
            $entity->setSlugCompany($slug_company);
            $entity->setCategory($category);
            $entity->setNameIndex($name_index);
            $entity->setIsCompare(1);
            $entity->setNameCompany($name_company);
            $entity->setCountryOrigin($country_origin);
            $entity->setDetail($detail);

            $this->persist($entity);
            $this->flush();

            $mess = 'Success Save list to compare';
        } else {
            $cek->setIsCompare(1);

            $this->persist($cek);
            $this->flush();
            $mess = 'Success Save list to compare';
        }

        $param = array(
            'entityBuyer' => $this->repo("JariffMemberBundle:SavedCompany")->findBy(array('member' => $this->getUser(), 'category' => 'buyers')),
            'entitySupplier' => $this->repo("JariffMemberBundle:SavedCompany")->findBy(array('member' => $this->getUser(), 'category' => 'suppliers')),
            'active_on' => $category,
            'mess' => $mess
            );


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Compare:list_save.html.twig', $param),
                    )
                )
            );
    }

    /**
     * @Route(
     *   "/member-display-modal-delete-compare-company/{slug_company}/{category}/{name_index}/{country_origin}/{name_company}",
     *   name     = "member_delete_compare_company",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function deleteCompareCompanyAction($slug_company, $category, $name_index, $country_origin, $name_company)
    {

        $cek = $this->repo("JariffMemberBundle:SavedCompany")->findOneBy(array('member' => $this->getUser(), 'category' => $category, 'slug_company' => $slug_company));

        if ($cek) {
            $cek->setIsCompare(0);
            $this->persist($cek);
            $this->flush();

            $mess = 'Success Delete Company';
        } else {
            $mess = 'No data company';
        }


        $param = array(
            'entityBuyer' => $this->repo("JariffMemberBundle:SavedCompany")->findBy(array('member' => $this->getUser(), 'category' => 'buyers')),
            'entitySupplier' => $this->repo("JariffMemberBundle:SavedCompany")->findBy(array('member' => $this->getUser(), 'category' => 'suppliers')),
            'active_on' => $category,
            'mess' => $mess
            );


        return new Response(
            json_encode(
                array(
                    'success' => true,
                    'html_string' => $this->renderView('JariffMemberBundle:Search\Compare:list_save.html.twig', $param),
                    )
                )
            );
    }
}

