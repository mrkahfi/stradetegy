<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\ARImports\SearchShipmentsArImportsEmbedFormType;
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
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\JNPTImports\SearchShipmentsJNPTImportEmbedFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\MXImports\SearchShipmentsMXImportEmbedFormType;
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

use Jariff\MemberBundle\Form\SearchGlobal\Shipments\USExports\SearchShipmentsUSExportEmbedFormType;

use Jariff\MemberBundle\Form\SearchGlobal\SearchShipmentsEmbedFormType;
/**
 * ardianys@gmail.com
 */
class BaseController extends Controller
{
    public function session()
    {
        return $this->container->get('session');
    }

    /**
     * Shortcut to return the Doctrine entity manager
     * @return  entity manager
     */
    public function em()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param string $entity nama entity FQDN AcmeBundle:Entity
     * Shortcut to return the Doctrine Repository
     * @return  Repository
     */
    public function repo($entity)
    {
        return $this->em()->getRepository($entity);
    }
    public function persist($entity)
    {
        return $this->em()->persist($entity);
    }
    public function flush()
    {
        return $this->em()->flush();
    }

    /**
     * Shortcut untuk mendapatkan logged in user
     * @return  JariffProjectBundle\Entity\User
     */
    public function user()
    {
        return $this->getUser();
    }

    /**
     * shortcut untuk mendapatkan service security.context
     * @return Object
     */
    public function sc()
    {
        return $this->container->get('security.context');
    }

    /**
     * Shortcut to cek user priviliges
     * apabila superadmin, maka cukup di cek apakah bisa mengakses role super admin
     *
     * @return  Repository
     */
    public function isGrantSuperAdmin()
    {
        return $this->sc()->isGranted('ROLE_SUPER_ADMIN');
    }

    /**
     * apakah user memiliki hak akses sebagai top admin,
     * perlu dibedakan dengan isTopAdmin, sebab super admin memiliki hak akses top admin
     * @return boolean
     */
    public function isGrantTopAdmin()
    {
        return $this->sc()->isGranted('ROLE_TOP_ADMIN');
    }

    /**
     * apakah user memiliki role sebagai top admin,
     * perlu dibedakan dengan isGranTopAdmin, sebab super admin memiliki hak akses top admin
     * padahal dia bukan merupakan top admin, bahkan jabatannya super admin
     * @return boolean
     */
    public function isTopAdmin()
    {
        return $this->user()->hasRole('ROLE_TOP_ADMIN') ? true : false;
    }

    /**
     * apakah user memiliki hak akses sebagai admin biasa,
     * perlu dibedakan dengan isAdmin, sebab top admin dan super admin memiliki hak akses admin
     * @return boolean
     */
    public function isGrantAdmin()
    {
        return $this->sc()->isGranted('ROLE_ADMIN');
    }

    /**
     * apakah user memiliki role sebagai top admin,
     * perlu dibedakan dengan isGranTopAdmin, sebab super admin memiliki hak akses top admin
     * padahal dia bukan merupakan top admin, bahkan jabatannya super admin
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->user()->hasRole('ROLE_ADMIN') ? true : false;
    }

    /**
     * apakah user memiliki role sebagai top admin,
     * perlu dibedakan dengan isGranTopAdmin, sebab super admin memiliki hak akses top admin
     * padahal dia bukan merupakan top admin, bahkan jabatannya super admin
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->user()->hasRole('ROLE_SUPER_ADMIN') ? true : false;
    }

    /**
     * apakah user memiliki hak akses sebagai Employee biasa,
     * perlu dibedakan dengan isEmployee, sebab admin memiliki hak akses Employee juga
     * fungsi ini diperlukan untuk membatasi akses dari user yg belum jadi employee kalau mungkin ada
     * @return boolean
     */
    public function isGrantEmployee()
    {
        return $this->sc()->isGranted('ROLE_USER');
    }

    /**
     * apakah user memiliki role sebagai Employee,
     * perlu dibedakan dengan isGrantEmployee, sebab admin memiliki hak akses Employee juga
     * padahal dia bukan merupakan Employee
     * @return boolean
     */
    public function isEmployee()
    {
        return $this->user()->hasRole('ROLE_EMPLOYEE') ? true : false;
    }

    /**
     * shortcut untuk menampilkan success flash message
     * @param  string $message success message
     */
    public function success($message)
    {
        $this->get('session')->getFlashBag()->add('success', $message);
    }

    /**
     * shortcut untuk menampilkan error flash message
     * @param  string $message error message
     */
    public function error($message)
    {
        $this->get('session')->getFlashBag()->add('error', $message);
    }

    /**
     * shortcut untuk menampilkan error flash message
     * @param  string $message error message
     */
    public function warning($message)
    {
        $this->get('session')->getFlashBag()->add('warning', $message);
    }

    /**
     * tambah pesan error di flash bag, kemudian redirect
     * @param  string $message pesan error
     * @param  string $route   redirect url
     * @return Response object
     */
    public function errorRedirect($message, $route = 'dashboard', $option = array())
    {
        $this->get('session')->getFlashBag()->add('error', $message);
        if (!empty($option)) {
            return $this->redirect($this->generateUrl($route, $option));
        } else {
            return $this->redirect($this->generateUrl($route));
        }
    }

    public function redirectUrl($route = 'dashboard', $option = array())
    {
        if (!empty($option)) {
            return $this->redirect($this->generateUrl($route, $option));
        } else {
            return $this->redirect($this->generateUrl($route));
        }
    }

    /*
     * tambah pesan success di flash bag, kemudian redirect
     */
    /**
     * satu fungsi untuk sekalian menambahkan pesan ke flash bag,
     * sekaligus redirect ke url tertentu
     * @param  string $message pesan yg ditampilkan ke user
     * @param  string $route   route name url yg baru
     * @param  array  $option  array untuk menggenerate route apabila ada
     * @return Response
     */
    public function successRedirect($message, $route = 'dashboard', $option = array())
    {
        $this->get('session')->getFlashBag()->add('success', $message);
        if (!empty($option)) {
            return $this->redirect($this->generateUrl($route, $option));
        } else {
            return $this->redirect($this->generateUrl($route));
        }
    }

    /**
     * pagination biar sama semua
     * @param  Doctrine Query  $query  object doctrine query builder, bukan result
     * @param  integer $amount jumlah data pada tiap halaman
     * @return KNP_Pagination
     */
    public function paginate($query, $page, $amount = 100)
    {
        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $page,
            $amount
            );


        return $pagination;
    }


    /**
     *
     * search  nya
     * akan diexecute jika ada data yang dicari.
     *
     * $adding digunakan untuk penambahan query, misal status = 0 maka tambahkan
     * dengan aturan i.status = 0, hmmm, untuk ini aman, karena data bersifat default bukan get maupun post.
     *
     */
    public function search($class, $filter = array(), $join = null,$adding = null)
    {
        $sq                 = $this->get('request')->get('search_form');
        $search['entities'] = null;
        $search['form']     = array(
            'class'  => $class,
            'filter' => $filter,
            'q'      => isset($sq['q'])         ? $sq['q']      : '',
            'fields' => isset($sq['fields'])    ? $sq['fields'] : ''
            );


        if (!empty($sq)) {

            $field = $sq['fields'];
            $q = $sq['q'];

            $search['entities'] = $this->repo('JariffAdminBundle:Admin')->findSearchResult($class, $field, $q, $join, $adding);

            $check = $search['entities']->getResult();
            if (!empty($check))
                $this->success('Pencarian dengan kata kunci <b><u>' . $q . '</u></b> di temukan, dengan total pencarian sebanyak <b>' . count($check) . '</b> data.');
            else
                $this->error('Pencarian dengan kata kunci <b><u>' . $q . '</u></b> tidak ditemukan.');
        }

        return $search;
    }

    public function checkRouter($name){
        $router = $this->container->get('router')->getRouteCollection()->get($name);

        if(!$router)
            return false;

        return true;
    }

    /**
     * Shortcut to return json data
     * @return  request
     */
    public function json($data)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     *
     * perlu dibedakan dengan isGrantEmployee, sebab admin memiliki hak akses Employee juga
     * addons default country_subscriptions
     * @return boolean
     */
    public function iamSubcriptionCountry($country_type)
    {
        return in_array($country_type,unserialize($this->user()->getLastSubscription()->getAddons())["country_subscriptions"]);
    }

    public function test()
    {
        return "ok";
    }

    /**
     *
     * Karena sebagian besar query sama dan digunakan di seluruh negara
     *
     *
     */
    public function queryDSL($s_cache,$string_date,$product_name,$fields = array(),$size = 10,$page = 1,$additional = array())
    {


        if (preg_match('/all/i', $s_cache->search[0]->collect)) {

            $product_name = "*";

            $queryFirst['must'][] = array(                
                "query_string" => array(
                    "query" => $s_cache->search[0]->q
                    )                    
                );
        } else {

           $product_name = $s_cache->search[0]->collect;


           $queryFirst['must'][] = array(

            "match" => array(
                $s_cache->search[0]->collect => $s_cache->search[0]->q
                )

            );
       }

       if(isset($additional['em'])){
        $filterKeyword = $additional['em']->getRepository('JariffAdminBundle:FilterKeywords')->findAll();
    }else{
        $filterKeyword = $this->em()->getRepository('JariffAdminBundle:FilterKeywords')->findAll();
    }
    

    foreach ($filterKeyword as $key) {

        if (!isset($keywords))
            $keywords = $key->getKeyword();
        else
            $keywords .= ' or ' . $key->getKeyword();
    }

    


    $queryFirst['must_not'][] = array(
        'match' => array(
            "product_desc" => $keywords

            )
        );

    $i = 0;


    foreach ($s_cache->search as $s) {
        if ($i > 0) {
            if (preg_match('/all/i', $s->collect)) {
                if (preg_match('/and/i', $s->condition)) {
                    $queryFirst['must'][] = array(
                        "query_string" => array(
                            "query" => $s_cache->search[0]->q
                            )                    
                        );
                }

                if (preg_match('/or/i', $s->condition)) {
                    $queryFirst['should'][] = array(
                        "query_string" => array(
                            "query" => $s_cache->search[0]->q
                            )                    
                        );
                }

                if (preg_match('/not/i', $s->condition)) {
                    $queryFirst['must_not'][] =array(
                        "query_string" => array(
                            "query" => $s_cache->search[0]->q
                            )
                        );
                }
            } else {


                if (preg_match('/and/i', $s->condition)) {
                    $queryFirst['must'][] = array(
                        'match' => array(

                            $s_cache->search[$i]->collect => $s_cache->search[$i]->q

                            )
                        );
                }

                if (preg_match('/or/i', $s->condition)) {
                    $queryFirst['should'][] = array(
                        'match' => array(
                         $s_cache->search[$i]->collect => $s_cache->search[$i]->q
                         )
                        );
                }

                if (preg_match('/not/i', $s->condition)) {
                    $queryFirst['must_not'][] =array(
                        'match' => array(
                         $s_cache->search[$i]->collect => $s_cache->search[$i]->q
                         )
                        );
                }
            }
            
        }
        $i++;
    }

        // if (isset($s_cache->country)) {
        //     $qCountry = $s_cache->country[0];
        //     $i = 0;
        //     foreach ($s_cache->country as $country) {
        //         if ($i > 0) {
        //             $qCountry .= ' or ' . $country;
        //         }

        //         $i++;
        //     }


        //     $filter['bool']['must'][] = array(
        //         "query" => array(
        //             "query_string" => array(

        //                 "default_field" => 'slug_country_ori_shipper',
        //                 "query" => $qCountry

        //             )
        //         )
        //     );
        // }

        // if (!empty($s_cache->marked_master)) {
        //     $qMarked = $s_cache->marked_master[0];
        //     $i = 0;
        //     foreach ($s_cache->marked_master as $bol) {
        //         if ($i > 0) {
        //             $qMarked .= ' or ' . $bol;
        //         }

        //         $i++;
        //     }


        //     $filter['bool']['must'][] = array(
        //         "query" => array(
        //             "query_string" => array(

        //                 "default_field" => 'bill_type_code',
        //                 "query" => $qMarked

        //             )
        //         )
        //     );
        // }

    if (isset($s_cache->bill_type_code)) {
        $filter['bool']['must'][] = array(
            "query" => array(
                "multi_match" => array(
                    "query" => $s_cache->bill_type_code,
                    "type" => "phrase",
                    "fields" => array('bill_type_code')
                    )
                )
            );
    }

    if (isset($s_cache->top_importer)) {
        $filter['bool']['must'][] = array(
            "query" => array(
                "multi_match" => array(
                    "query" => $s_cache->top_importer,
                    "type" => "phrase",
                    "fields" => array('slug_consignee_name')
                    )
                )
            );
    }

    if (isset($s_cache->top_exporter)) {
        $filter['bool']['must'][] = array(
            "query" => array(
                "multi_match" => array(
                    "query" => $s_cache->top_exporter,
                    "type" => "phrase",
                    "fields" => array('slug_shipper_name')
                    )
                )
            );
    }

    $filter[] = array(
        "range" => array(
            $string_date => array(
                "gte" => $s_cache->date_range->from
                )
            )
        );

    $highlight = array(
        "highlight" => array(
            "number_of_fragments" => 3,
            "fragment_size" => 150,
            "fields" => array(
                $product_name => array(
                    "pre_tags" => array("<strong>"), 
                    "post_tags" => array("</strong>")),
                ),                
            "require_field_match" => false
            )
        );

    $queryDSL = array_merge(array(
        "query" => array_merge(array(
            "bool" => array_merge( $queryFirst
                , (!empty($filter)) ? array("filter" => $filter) : array()
                            // array(
                            //     "_cache" => true
                            // )
                )
            )
        )
        ),
    $highlight,

    array('from' => (($page - 1) * $size)),
    array('sort' => array($string_date => array("order" => "desc"))),
    (!isset($additional['em']) and !empty($additional)) ? $additional : array() 
    );

    return $queryDSL;


}

function formCollect($country, $type,$entity){
    if($country == "ar" and $type == "imports"){
        return $this->createForm(new SearchShipmentsArImportsEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "us" and $type == "exports"){
        return $this->createForm(new SearchShipmentsUSExportEmbedFormType(), $entity, array(
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
        return $this->createForm(new SearchShipmentsCAExportEmbedFormType(), $entity, array(
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
    }elseif($country == "co" and $type == "exports"){
        return $this->createForm(new SearchShipmentsCOExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "co" and $type == "imports"){
        return $this->createForm(new SearchShipmentsCOImportEmbedFormType(), $entity, array(
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
    }elseif($country == "jnpt" and $type == "imports"){
        return $this->createForm(new SearchShipmentsJNPTImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "mx" and ($type == "exports" or $type == "imports")){
        return $this->createForm(new SearchShipmentsMXImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "pa" and $type == "exports"){
        return $this->createForm(new SearchShipmentsPAExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "pa" and $type == "imports"){
        return $this->createForm(new SearchShipmentsPAImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "pe" and $type == "exports"){
        return $this->createForm(new SearchShipmentsPEExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "pe" and $type == "imports"){
        return $this->createForm(new SearchShipmentsPEImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "py" and $type == "exports"){
        return $this->createForm(new SearchShipmentsPYExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "py" and $type == "imports"){
        return $this->createForm(new SearchShipmentsPYImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "uy" and $type == "exports"){
        return $this->createForm(new SearchShipmentsUYExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "uy" and $type == "imports"){
        return $this->createForm(new SearchShipmentsUYImportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "ve" and $type == "exports"){
        return $this->createForm(new SearchShipmentsVEExportEmbedFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
            ));
    }elseif($country == "ve" and $type == "imports"){
        return $this->createForm(new SearchShipmentsVEImportEmbedFormType(), $entity, array(
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