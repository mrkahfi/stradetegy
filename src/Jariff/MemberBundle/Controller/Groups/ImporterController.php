<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\Groups;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\DocumentBundle\Document\ImportDocument;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\MemberBundle\Entity\ExportTools;
use Jariff\ProjectBundle\Util as Util;
use Jariff\ProjectBundle\Twig\StringExtension as StringExtension;

/**
 *
 * @Route("/member")
 */
class ImporterController extends BaseController
{

    /**
     * @Route(
     *   "/group-importer",
     *   name     = "member_group_importer"
     * )
     *
     *
     */
    public function importerAction()
    {

      return $this->render('JariffMemberBundle:Search/Groups:importer.html.twig');
    }

    /**
     * @Route(
     *   "/group-importer/get-data",
     *   name     = "member_group_importer_ajax"
     * )
     *
     *
     */
    public function importerAjaxAction()
    {

        $data = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findBy(array('user_id' => $this->getUser()->getId(), 'category' => 'importer'));

        return $this->render('JariffMemberBundle:Search/CacheChoice:importer.html.twig', array('check' => $data, 'countHistory' => 0));
    }


}