<?php

namespace Jariff\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\DocumentBundle\Document\ImportDocument;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\ProjectBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Form\SearchGlobalFormType;
use Jariff\ProjectBundle\Util\Util;




class CompaniesController extends BaseController
{

    private $slugs = array();

    /**
     * Lists all Company entities.
     *
     * @Route("/company/form/{page}", 
     * name="companies_form",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *   options={"expose"=true})
     *
     *
     */
    public function indexAction($page)
    {
        $companies = self::findCompany($collection, $page);

        return $this->render('JariffProjectBundle:Default:companies.html.twig', array('companies' => $companies, 'slugs' => $this->slugs));
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/company/json/{page}", 
     * name="companies_json",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *   options={"expose"=true})
     *
     *
     */
    public function indexJsonAction($page)
    {
        $collection = $db->all_data;
        $companies = self::findCompany($collection, $page, 5);

        return $this->render('JariffProjectBundle:Default:companies.html.twig', array('companies' => $companies, 'slugs' => $this->slugs));
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/company/list/{page}", 
     * name="companies_list",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   },
     *   options={"expose"=true})
     */
    public function listAction($page)
    {
        $company = self::findCompany($collection);

        return $this->render('JariffProjectBundle:Default:company_list.html.twig', array('company' => $company));
    }

    private function findCompany($collection, $page, $limit) {

        $cursor = $collection->find()->skip($page)->limit($limit);
        $companies = array();
        foreach ($cursor as $document) {
            $company = $document["value"]["company_name"];
            if (strlen($company) > 3) {
                $companies[] = str_replace("-", " ", str_replace("--", " ", $company));
                $this->slugs[] = $document["value"]["slug_company"];
            }
        }

        return $companies;
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/company_info/add/{slug}", 
     * name="add_company_info",
     * options={"expose"=true})
     * @Method("POST")
     *
     */
    public function addCompanyInfoAction(Request $request, $slug)
    {
        $website = $request->request->get('website', 'n/a');
        $phone = $request->request->get('phone', 'n/a');

        // echo $website."\n<br>".$phone;
        // die();

        $companies = self::findCompany($collection, $page, 1);
        
        $item = $collection->findOne(array("value.slug_company" => $slug));
        $value = $item['value'];
        $value['website'] = $website;
        $value['phone'] = $phone;
        $newdata = array('$set' => array("value" => $value));
        $return = $collection->update(array("value.slug_company" => $slug), $newdata);
        if ($return->err == null) {
            $response = $companies;
            $return=json_encode($companies);
            return new Response($return, 200, array('Content-Type'=>'application/json'));
        } else {
            $return=json_encode(array());
            return new Response($return, 400, array('Content-Type'=>'application/json'));

        }

    }
}
