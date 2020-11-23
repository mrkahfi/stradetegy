<?php

namespace Jariff\MemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\MemberBundle\Entity\MemberSetting;
use Jariff\MemberBundle\Entity\Country;
use Jariff\MemberBundle\Form\MemberSettingType;
use Jariff\MemberBundle\Form\CountryType;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\MemberBundle\Form\CountryAddonsFormType;
use Jariff\MemberBundle\Model\Country as CountryModel;
use Jariff\MemberBundle\Form\HistorySubsType;
use Jariff\MemberBundle\Model\CheckBoxSubs as CheckboxSubs;
use Jariff\MemberBundle\Form\NumberOfSearchSubsType;
use Jariff\MemberBundle\Form\NumberOfDownloadsSubsType;
use Jariff\MemberBundle\Form\TBPSubsType;

/**
 * MemberSetting controller.
 *
 * @Route("/member")
 */
class MemberSettingController extends BaseController
{

    /**
     * Lists all MemberSetting entities.
     *
     * @Route("/profile/search-setting", name="membersetting")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffMemberBundle:MemberSetting')->findOneByMember($this->getUser());

        $form = $this->createForm(new MemberSettingType(), $entity);

        return array(
            'entities' => $entity,
            'edit_form' => $form->createView()
            );
    }

    /**
     * Lists all MemberSetting entities.
     *
     * @Route("/member-setting-edit", name="member_setting_edit")
     * @Method("POST")
     * @Template("JariffMemberBundle:MemberSetting:index.html.twig")
     */
    public function editSettingAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffMemberBundle:MemberSetting')->findOneByMember($this->getUser());

        if (!$entity)
            $entity = new MemberSetting();

        $form = $this->createForm(new MemberSettingType(), $entity);

        $form->bind($request);

        $data = $form->getData();

        if ($form->isValid()) {

            $entity->setLanguage($data->getLanguage());
            $entity->setCurrency($data->getCurrency());
            $entity->setWeight($data->getWeight());
            $entity->setDistance($data->getDistance());
            $entity->setMember($this->getUser());

            $this->em()->persist($entity);
            $this->em()->flush();
            return $this->successRedirect('Your data has been successful edit', 'membersetting');
        }

        return array(
            'entities' => $entity,
            'edit_form' => $form->createView()
            );
    }


    /**
     * Lists all MemberSetting entities.
     *
     * @Route("/profile/addons-setting", name="member_addons_setting")
     * @Method("GET")
     * @Template()
     */
    public function addonsSettingAction()
    {
        $active_subscription = $this->user()->getLastSubscription();

        // Start Country
        $countrySubscriptions = unserialize($this->user()->getLastSubscription()->getAddons())["country_subscriptions"];

        $country = new CountryModel();
        $country->country = array();

        $entity = $this->repo("JariffMemberBundle:Country")->findAll();

        foreach ($entity as $row) {
            $countryData[$row->getSlug()] = $row->getName();
        }

        foreach ($countrySubscriptions as $cs) {
            $country->country[] = $cs;
        }

        $formCountry = $this->createForm(new CountryAddonsFormType(), $country, array(
            'action' => '',
            'method' => 'POST',
            'dataCountry' => $countryData
            ));

        //History
        $historyValue = $active_subscription->getHistoryValue();

        $dataHistory = array(1,6,12,18,24,36,60);

        $chData = array();
        foreach ($dataHistory as $key => $value) {
            # code...

            if ($active_subscription->getEverythingPlan()) {
                $chData = array_merge($chData,$dataHistory);
                break;
            }else{
                if($value <= $historyValue)
                    $chData = array_merge($chData,array($value));
            }
            
        }


        $history = new CheckboxSubs();
        $history->setCh($chData);

        $formHistory = $this->createForm(new HistorySubsType(), $history, array(
            'action' => '',
            'method' => 'POST',
            ));

        //Number of searches
        $historyValue = $active_subscription->getSearchValue();

        $dataHistory = array(5,25,50);

        $chData = array();
        foreach ($dataHistory as $key => $value) {
            # code...

            if($value <= $historyValue)
                $chData = array_merge($chData,array($value));

            if ($active_subscription->getEverythingPlan()) {
                $chData = array_merge($chData,$dataHistory,array('unlimited'));
                break;
            }

        }


        $history = new CheckboxSubs();
        $history->setCh($chData);

        $formNumberOfSearches = $this->createForm(new NumberOfSearchSubsType(), $history, array(
            'action' => '',
            'method' => 'POST',
            ));

        //Number of donloads
        $historyValue = $active_subscription->getDownloadValue();

        $dataHistory = array(1000,5000,25000,100000);

        $chData = array();
        foreach ($dataHistory as $key => $value) {
            # code...

            if($value <= $historyValue)
                $chData = array_merge($chData,array($value));

            if ($active_subscription->getEverythingPlan()) {
                $chData = array_merge($chData,$dataHistory,array('unlimited'));
                break;
            }

        }


        $history = new CheckboxSubs();
        $history->setCh($chData);

        $formNumberOfDownloads = $this->createForm(new NumberOfDownloadsSubsType(), $history, array(
            'action' => '',
            'method' => 'POST',
            ));

        //TBP
        $historyValue = $active_subscription->getBigPictureValue();

        $history = new CheckboxSubs();
        $history->setCh(array($historyValue));

        $formTBP = $this->createForm(new TBPSubsType(), $history, array(
            'action' => '',
            'method' => 'POST',
            ));


        return array(

            'edit_form' => $formCountry->createView(),
            'form_history' => $formHistory->createView(),
            'form_number_of_searches' => $formNumberOfSearches->createView(),
            'form_number_of_downloads' => $formNumberOfDownloads->createView(),
            'form_tbp' => $formTBP->createView(),
            );
    }

    /**
     * Lists all MemberSetting entities.
     *
     * @Route("/member-addons-setting-edit", name="member_addons_setting_edit")
     * @Method("GET")
     *
     */
    public function editAddonsSettingAction(Request $request)
    {
        $countrySubscriptions = unserialize($this->user()->getLastSubscription()->getAddons())["country_subscriptions"];

        $country = new CountryModel();
        $country->country = array();

        $entity = $this->repo("JariffMemberBundle:Country")->findAll();

        foreach ($entity as $row) {
            $countryData[$row->getSlug()] = $row->getName();
        }

        foreach ($countrySubscriptions as $cs) {
            $country->country[] = $cs;
        }

        $formCountry = $this->createForm(new CountryAddonsFormType(), $country, array(
            'action' => '',
            'method' => 'POST',
            'dataCountry' => $countryData
            ));

        $formCountry->bind($request);

        $data = $formCountry->getData();
        $countrySubscriptions = unserialize($this->user()->getLastSubscription()->getAddons());

        unset($countrySubscriptions['country_subscriptions']);

        $toSerialize = array_merge($countrySubscriptions,array('country_subscriptions' => $data->getCountry()));
        $ser = serialize($toSerialize);

        $entity = $this->repo("JariffMemberBundle:MemberSubscription")->findOneBy(array('member' => $this->getUser()));
        $entity->setAddons($ser);

        $this->persist($entity);
        $this->flush();


        return $this->redirectUrl('member_addons_setting');

    }

    /**
     * Lists all MemberSetting entities.
     *
     * @Route("/member-setting-glossary-terms", name="member_setting_glossary_terms")
     * @Template("JariffMemberBundle:MemberSetting:glossary.html.twig")
     *
     */
    public function glossaryTermsAction(Request $request)
    {
        return array();
    }

     /**
     * Lists all MemberSetting entities.
     *
     * @Route("/member-setting-scac-codes", name="member_setting_scac_codes")
     * @Template("JariffMemberBundle:MemberSetting:scac.html.twig")
     *
     */
    public function scacTermsAction(Request $request)
    {
        return array();
    }

}
