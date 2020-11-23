<?php

namespace Jariff\DocumentBundle\Controller;

use Doctrine\ORM\Query\Expr\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Jariff\ProjectBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

use Jariff\AdminBundle\Form\SearchFormType;
use Jariff\DocumentBundle\Document\ImportDocument;

class DefaultController extends BaseController
{
    /**
     * @Route(
     *   "/searching/{page}",
     *   name     = "searching",
     *   defaults = {
     *     "page"  : "1"
     *   },
     *   requirements = {
     *     "page"  : "\d+"
     *   }
     * )
     * @Method("GET")
     */
    public function indexAction($page = 1)
    {

        $data = $this->get('request')->get('search_form');

        $result = null;
        $review = isset($data['review']) ? $data['review'] : 10;


        if (!empty($data)) {
            $finder = $this->container->get('fos_elastica.finder.site.docu');

            if ($data['collect'] != 'all') {
                $boolQuery = new \Elastica\Query\Bool();
                $filterQuery = new \Elastica\Query\Range();
                $fieldQuery = new \Elastica\Query\Text();
                $query = new \Elastica\Query();


                $fieldQuery->setFieldQuery($data['collect'], $data['q']);


                $boolQuery->addMust($fieldQuery);

                $res = $finder->createPaginatorAdapter($boolQuery);

            } else {
                $res = $finder->createPaginatorAdapter($data['q']);
            }


            $result = $this->paginate($res, $page, ($review <= 100) ? $data['review'] : 10);


        }


        $searchDocument = new ImportDocument();


        $form = $this->createForm(new SearchFormType(), $searchDocument,
            array(
                'q' => isset($data['q']) ? $data['q'] : null,
                'collect' => isset($data['collect']) ? $data['collect'] : null,
                'rev' => ($review <= 100) ? $data['review'] : 10
            )
        );

        return $this->render('JariffDocumentBundle:Search:index.html.twig',
            array(
                'form' => $form->createView(),
                'result' => $result,
                'total' => empty($result) ? null : $result->getTotalItemCount(),
                'review' => ($review <= 100) ? $data['review'] : 10
            ));

    }

    /**
     * @Route("/searching-ajax", name="ajax_search")
     * @Template("JariffDocumentBundle:Search:result.html.twig")
     * @Method("GET")
     */
    public function resultAction($page = 1)
    {
        $data = $this->get('request')->get('search_form');

        $result = null;

//        var_dump($data['collect']);die();
        $review = isset($data['review']) ? $data['review'] : 10;

        if (!empty($data)) {
            $finder = $this->container->get('fos_elastica.finder.site.docu');

            if ($data['collect'] != 'all') {
                $boolQuery = new \Elastica\Query\Bool();
                $filterQuery = new \Elastica\Query\Range();
                $fieldQuery = new \Elastica\Query\Text();

                $fieldQuery->setFieldQuery($data['collect'], $data['q']);


//                $filterQuery->addField('arrival_date',array('from' => '2012-08-01','to' => '2012-08-02' ));
                $boolQuery->addMust($fieldQuery);
//                $boolQuery->addMustNot($filterQuery);


                $res = $finder->createPaginatorAdapter($boolQuery);
            } else {
                $res = $finder->createPaginatorAdapter($data['q']);
            }


            $result = $this->paginate($res, $page, ($review <= 100) ? $data['review'] : 10);

            $result->setUsedRoute('searching');

            return array(
                'result' => $result,
                'total' => empty($result) ? null : $result->getTotalItemCount(),
                'review' => ($review <= 100) ? $data['review'] : 10
            );
        }


    }

    /**
     * @Route("/count-data", name="count_data")
     */
    public function countDataAction()
    {
        $count = $this->dm()->createQueryBuilder('JariffDocumentBundle:ImportDocument')
            ->getQuery()->execute()->count();

        return new Response($count);

    }

    /**
     * @Route("/test/{searchTerm}", name="auto_test")
     */
    public function findlName($searchTerm)
    {

//        $test = $this->dm()->create();




        $qb = $this->dm()->find();

        var_dump($qb);



        die();

    }

    /**
     * @Route("/autocomplete/{searchTerm}", name="auto")
     */
    public function findByPartialName($searchTerm)
    {
        $finder = $this->container->get('fos_elastica.finder.site.docu');

        $client = $this->get('fos_elastica.client');
        $search = new \Elastica\Search($client);


        $data = array("suggest" =>

        array("spelling" => array(
            'text' => $searchTerm,
            'term' => array(
                'size' => 5,
                'field' => "_all"
            )
        )
        )
        );
        $data_string = json_encode($data);

        $ch = curl_init('http://localhost:9200/_search?search_type=count');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);

        $res = json_decode($result);

//        var_dump($res->suggest->spelling);

        foreach ($res->suggest->spelling as $r) {
            foreach ($r as $rChild) {
                if (is_array($rChild)) {
                    foreach ($rChild as $row) {
                        echo $row->text;
                    }
                }
            }
        }

        $postType = $this->get('fos_elastica.finder.site.docu');


        die();


        $builder = new \Elastica\Query\Builder();
        $nameQuery = new \Elastica\Query\Text();

        $pspell_config = pspell_config_create("en");
        pspell_config_personal($pspell_config, "/home/user/public_html/custom.pws");
        pspell_config_repl($pspell_config, "/home/user/public_html/custom.repl");
        $pspell_link = pspell_new_config($pspell_config);

        $words = preg_split("/\s+/", strtolower($searchTerm));

        $ii = count($words);

        global $spellchecked;
        $spellchecked = "";
        $erroneous = "no";

        $spelling = array();
        $wordToFalse;
        $k = true;

        for ($i = 0; $i < $ii; $i++) {

            if (pspell_check($pspell_link, $words[$i])) {

                $res = $finder->find($words[$i]);

                if (count($res) > 0) {

                    if (count($spelling) == 0) {
                        $spellchecked .= $words[$i] . " ";
                    } else {
                        $j = 0;
                        foreach ($spelling as $r) {
                            $spelling[$j] = $r . $words[$i];
                            $j++;
                        }
                    }


                }


            } else {

                $wordToFalse[] = $i;
                $erroneous = "yes";
                $suggestions = pspell_suggest($pspell_link, $words[$i]);


                foreach ($suggestions as $r) {

                    $res = $finder->find($r);

                    if (count($res) > 0) {
                        if ($i == 0) {
                            $spelling[] = $spellchecked . "<b>" . $r . "</b> ";
                        } else {
                            $j = 0;
                            foreach ($spelling as $res) {

                                $spelling[$j] = $res . "<b>" . $r . "</b> ";
                                $j++;
                            }
                        }
                    }


                }
                $k = false;
            }


        }

        var_dump($spelling);
//        var_dump($wordToFalse);
        if ($erroneous == "yes") {
            echo "Did you mean: <i>" . $spellchecked . "?";


//            foreach ($suggestions as $row) {
//                echo $row . "<br/>";
//            }

//            for ($i = 0; $i < $ii; $i++) {
//
//                foreach ($suggestionss[$i] as $row) {
//                    foreach($row as $r){
//                        $nameQuery->setFieldQuery('consignee_name', $r);
//                        $find = $finder->find($nameQuery);
//
//                        if(count($find) > 0){
//                            echo $r." = ".count($find)."<br/>";
//                        }
//
//                    }
//                }
//            }
//            $nameQuery->setFieldQuery('consignee_name', $searchTerm);
//            foreach($suggestionss as $row){
//                echo "<br/>".$row;
//            }
        } else {
            echo $spellchecked . " is a valid word/phrase";
        }

        die();

        $finder = $this->container->get('fos_elastica.finder.site.docu');

        $builder = new \Elastica\Query\Builder();


        $nameQuery = new \Elastica\Query\Text();
        $nameQuery2 = new \Elastica\Query\Terms();

        $nameQuery->setFieldQuery('consignee_name', $searchTerm);


//        $nameQuery->setFieldParam('consignee_name', 'index_analyzer', 'standard');
//        $nameQuery->setFieldParam('consignee_name', 'search_analyzer', 'standard');
//
//        $nameQuery->setFieldParam('consignee_name', 'preserve_position_increments', 'false');
//        $nameQuery->setFieldParam('consignee_name', 'preserve_separators', 'false');


        $find = $finder->find($nameQuery);

        var_dump($find);
        die();
    }

}
