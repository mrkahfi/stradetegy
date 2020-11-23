<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\ProjectBundle\Controller\Search;

use Jariff\MemberBundle\Form\Widget\CategoryEmbedFormType;
use Jariff\MemberBundle\Model\Country;
use Jariff\MemberBundle\Model\SearchEmbed;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\ProjectBundle\Util as Util;
use Jariff\MemberBundle\Model\Category;

use Jariff\MemberBundle\Form\Widget\Field\CategoryFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;

/**
 *
 * @Route("/")
 */
class WidgetGlobalSearchController extends BaseController
{

    /**
     * @Route(
     *   "/widget/category/{keyword}",
     *   name     = "widget_category"
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo\Widget:category.html.twig")
     */
    public function indexAction($keyword)
    {
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));


        $category2 = new Category();
        if (empty($s_cache->category))
            $category2->category = array('importer', 'exporter', 'product');
        else{
            $category2->category = (is_array($s_cache->category)) ? $s_cache->category : array($s_cache->category);
        }

        $formCategory = $this->createForm(new CategoryFormType(), $category2, array(
            'action' => '',
            'method' => 'POST',
        ));

        return array(
            "formCategory" => $formCategory->createView(),
            'keyword' => $keyword
        );
    }

    /**
     * @Route(
     *   "/widget/category/submit/{keyword}",
     *   name     = "widget_category_submit"
     * )
     *
     * @Method("POST")
     */
    public function widgetSubmitAction(Request $request, $keyword)
    {
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));


        $entity = new Category();
        $form = $this->createForm(new CategoryFormType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $form->handleRequest($request);


        $data = $form->getData();

        $cats = '';
        $i = 1;
        $catArray = array();
        foreach ($data->getCategory() as $cat) {
            $cats = $cats . $cat;
            if (count($data->getCategory()) > 1 and $i < count($data->getCategory())) {
                $cats = $cats . ',';
            }
            $i++;
            $catArray[] = $cat;
        }

        unset($s_cache->category);

        $s_cache = array_merge((array)$s_cache, array('category' => $catArray));
        $jsonS_cache = json_encode($s_cache);

        $url = null;
        if (count($data->getCategory()) == 1) {
            $url = $this->generateUrl('demo_global_search_show_' . $cats, array('keyword' => $keyword, 's_cache' => $util->encrypted($jsonS_cache)));
        }

        if (count($data->getCategory()) == 3) {
            if (isset($s_cache['search'][0])) {
                if ($s_cache['search'][0]->collect == 'all') {
                    $url = $this->generateUrl('demo_global_search_show_all', array('keyword' => $keyword, 's_cache' => $util->encrypted($jsonS_cache)));
                }
            } else {
                $url = $this->generateUrl('demo_global_search_show_all', array('keyword' => $keyword, 's_cache' => $util->encrypted($jsonS_cache)));
            }

        }

        if (count($data->getCategory()) == 2) {

            $url = $this->generateUrl('demo_global_search_show_category', array('keyword' => $keyword, 's_cache' => $util->encrypted($jsonS_cache)));

        }


        return new Response(json_encode(array(
            'success' => empty($url) ? 0 : 1,
            'urls' => $url
        )));
    }

    /**
     * @Route(
     *   "/widget/country/{keyword}",
     *   name     = "widget_country"
     * )
     *
     * @Template("JariffProjectBundle:Search\Demo\Widget:category.html.twig")
     */
    public function countryAction($keyword)
    {
        $util = new StringTwig();
        $s_cache = json_decode($util->decrypted($this->getRequest()->get('s_cache')));


        $category2 = new Country();
        if (empty($s_cache->category))
            $category2->category = array('importer', 'exporter', 'product');
        else
            $category2->category = $s_cache->category;

        $formCategory = $this->createForm(new CategoryFormType(), $category2, array(
            'action' => '',
            'method' => 'POST',
        ));

        return array(
            "formCategory" => $formCategory->createView(),
            'keyword' => $keyword
        );
    }
}

