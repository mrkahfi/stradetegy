<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
    
    public function conn()
    {
        return $this->container->get('database_connection');
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
    public function errorRedirect($message, $route = 'dashboard_admin', $option = array())
    {
        $this->get('session')->getFlashBag()->add('error', $message);
        if (!empty($option)) {
            return $this->redirect($this->generateUrl($route, $option));
        } else {
            return $this->redirect($this->generateUrl($route));
        }
    }

    public function redirectUrl($route = 'dashboard_admin', $option = array())
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
    public function successRedirect($message, $route = 'dashboard_admin', $option = array())
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



    // /**
    //  * Shortcut to return json data
    //  * @return  request
    //  */
    // public function json($data)
    // {
        
    //     $response = new Response();
    //     $response->headers->set('Content-Type', 'application/json');
    //     $response->setContent(json_encode($data));
    //     return $response;
    // }

    /**
     * Shortcut to return js data
     * @return  request
     */
    public function js($template, $data = array())
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/javascript');
        $response->setContent($this->renderView($template, $data));
        return $response;
    }
}