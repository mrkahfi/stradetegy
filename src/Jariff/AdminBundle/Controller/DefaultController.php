<?php

namespace Jariff\AdminBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends BaseController
{
    /**
     * @Route("/admin/", name="dashboard_admin")
     * @Template("JariffAdminBundle:Default:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
