<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\Search;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\DocumentBundle\Document\ImportDocument;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\AdminBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Util as Util;

/**
 *
 * @Route("/member")
 */
class SearchCompareController extends BaseController
{

    /**
     * @Route(
     *   "/compare/{category}",
     *   name     = "member_compare_detail"
     * )
     *
     *
     */
    public function importerDetailAction($category)
    {

        $entity = $this->repo("JariffMemberBundle:SavedCompany")->findBy(array(
            'category' => $category,
            'is_compare' => 1
        ));

        return $this->render('JariffMemberBundle:Search/Compare:index_'.$category.'.html.twig',array('entity' => $entity));
    }


}