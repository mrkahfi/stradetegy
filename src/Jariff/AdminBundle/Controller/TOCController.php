<?php

namespace Jariff\AdminBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class TOCController extends BaseController
{
    /**
     * @Route("/admin/toc", name="dashboard_admin")
     * @Template("JariffAdminBundle:TOC:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
