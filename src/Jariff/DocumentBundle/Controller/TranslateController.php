<?php

namespace Jariff\DocumentBundle\Controller;

use Doctrine\ORM\Query\Expr\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Jariff\ProjectBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Jariff\AdminBundle\Form\SearchFormType;
use Jariff\DocumentBundle\Document\ImportDocument;

class TranslateController extends BaseController
{
    /**
     * @Route(
     *   "/translate/{id}",
     *   name     = "translate",
     *   defaults = {
     *     "id"  : "1"
     *   },
     *   requirements = {
     *     "id"  : "\d+"
     *   }
     * )
     */
    public function getAction($id = 1)
    {
        $server = $this->get('service_container')->getParameter('mongo_server');
        $db     = $this->get('service_container')->getParameter('mongo_database_name');
        $mongo  = new \MongoClient($server);
        $db     = $mongo->selectDB($db);
        $import = $db->import_document;
        $data   = $import->find( 
                        array('ig_id' => array('$regex' => new \MongoRegex("/.*$id$/")))
                        , array('_id' => true, 'product_description' => true)
                    )->limit( 5 );
        echo "
            <html>
                <title>Product Info</title>
                <body>
                    <form method='POST' action='".$this->generateUrl('translate_post')."'>
                        <input type='submit'><br/>";
        foreach ($data as $value) {
            echo "
                        <textarea name='product_description[]' rows='5' cols='150'>".
                            $value['_id'].' '.$value['product_description'].
                        "</textarea>";
        }
        echo "      </form>
                </body>
            </html>";
        die();
    }

    /**
     * Edits an existing Invoice entity.
     *
     * @Route(
     *   "/translate-post/{id}",
     *   name     = "translate_post",
     *   defaults = {
     *     "id"  : "1"
     *   },
     *   requirements = {
     *     "id"  : "\d+"
     *   }
     * )
     * @Method("POST")
     * @Template("JariffMemberBundle:Invoice:edit.html.twig")
     */
    public function postAction(Request $request, $id)
    {
        $server = $this->get('service_container')->getParameter('mongo_server');
        $db     = $this->get('service_container')->getParameter('mongo_database_name');
        $mongo  = new \MongoClient($server);
        $db     = $mongo->selectDB($db);
        $import = $db->import_document;

        foreach ($request->request->get('product_description') as $data) {
            preg_match('/([a-f0-9]{24}) (.*)/', $data, $matches);
            $id                  = $matches[1];
            $product_description = $matches[2];

            $data   = $import->findOne(array('_id' => new \MongoId($id)));

            var_dump($data);
            $data['product_description_es'] = $product_description;
            var_dump($data);
            $import->save($data);
        }
        die();
    }
}
