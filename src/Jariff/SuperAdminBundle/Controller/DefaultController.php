<?php

namespace Jariff\SuperAdminBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends BaseController
{
    /**
     * @Route("/super-admin/", name="dashboard_super_admin")
     * @Template("JariffSuperAdminBundle:Default:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
