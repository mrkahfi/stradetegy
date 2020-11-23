<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 11/27/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Controller\ExportTools;

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
class ExportToolsController extends BaseController
{

    /**
     * @Route(
     *   "/export-tools",
     *   name     = "member_export_tools"
     * )
     * @Method("POST")
     *
     */
    public function exportToolsAction(Request $request)
    {
        $file_format = $request->get('file_format');
        $file_category = $request->get('file_category');
        $file_name = $request->get('file_name');
        $file_name_empty = $request->get('file_name_empty');

        if (empty($file_name))
            $file_name = $file_name_empty;

        $file_country = $request->get('check_country_import_modal');

        $notify = $request->get('notify');
        $email = $request->get('email');

        $category = array();

        $totalDownload = 0;
        foreach ($file_category as $cat) {
            $category[$cat] = array(
                'all' => ($request->get('file_result_' . $cat)) ? $request->get('file_total_' . $cat) : 0,
                'start' => (!$request->get('file_result_' . $cat)) ? $request->get('file_start_' . $cat) : 0,
                'end' => (!$request->get('file_result_' . $cat)) ? $request->get('file_end_' . $cat) : 0,
                'column' => $request->get('column_' . $cat),
                'country' => $file_country
            );

            if ($request->get('file_end_' . $cat) < $request->get('file_start_' . $cat))
                return new Response(json_encode(array('success' => false,'message' => 'Total download in ' . $cat . ' start cant better than up.')));

            $totalDownload += ($request->get('file_result_' . $cat)) ? $request->get('file_total_' . $cat) : $request->get('file_end_' . $cat) - $request->get('file_start_' . $cat);

        }


        $member_search = $this->repo('JariffMemberBundle:MemberSubscription')->findOneBy(array(
            'member' => $this->getUser()->getId(),
            'active' => true
        ));

        if (!$member_search) {
            return new Response(json_encode(array('success' => false,'message' => "Currently there is no active subscription")));
        }

        $qb = $this->dm()->createQueryBuilder('JariffDocumentBundle:SearchHistory')
            ->field('user_id')
            ->equals($this->user()->getId())
            ->sort('id', 'DESC')
            ->limit(1);

        $query = $qb->getQuery();

        $lastQuery = null;
        foreach ($query->execute() as $row) {
            $lastQuery = $row->getQuery();
            $member_search_history = $this->repoMongo('JariffDocumentBundle:SearchHistory')->findoneBy(array(
                'id' => $row->getId()
            ));

            $member_search_history->setIsDownload(1);
            $this->dm()->persist($member_search_history);

            if (($member_search->getDownloadValue() - $member_search->getDownload()) > $totalDownload) {
                $member_search->setDownload($member_search->getDownload() + $totalDownload);
            } else {
                return new Response(json_encode(array('success' => false,'message' => "Sorry your download limit, please download under ".($member_search->getDownloadValue() - $member_search->getDownload()))));
            }


            $this->em()->persist($member_search);
        }
        $this->dm()->flush();

        $exportTools = new ExportTools();

        $exportTools->setMember($this->getUser());
        $exportTools->setFormat($file_format);
        $exportTools->setCategory(json_encode($category));
        $exportTools->setFileName($file_name);
        $exportTools->setEmail($email);
        $exportTools->setIsExecute(0);
        $exportTools->setQuery($lastQuery);

        if (!empty($notify)) {
            $exportTools->setIsAlert(1);
        } else {
            $exportTools->setIsAlert(0);
        }

        $this->em()->persist($exportTools);
        $this->em()->flush();


        return new Response(json_encode(array('success' => true,'message' => 'Export query successful request')));


    }

    /**
     * @Route(
     *   "/export-download-list",
     *   name     = "member_export_download_list",
     *  options={"expose"=true}
     * )
     *
     *
     */
    function downloadListActions()
    {
        $exportDownloadQueque = $this->repo('JariffMemberBundle:ExportTools')->findAll();

        return $this->render('JariffMemberBundle:Search/Result:download_list.html.twig', array('data' => $exportDownloadQueque));
    }

    /**
     * @Route(
     *   "/export-download",
     *   name     = "member_export_download"
     * )
     *
     *
     */
    function downloadExcelActions()
    {

        $exportDownloadQueque = $this->repo('JariffMemberBundle:ExportTools')->findOneBy(array('is_execute' => 0));

        $queryDecode = json_decode($exportDownloadQueque->getQuery(), 1);
        $categoryDecode = json_decode($exportDownloadQueque->getCategory(), 1);

        $q = $queryDecode['q'];
        $collect = $queryDecode['collect'];
        $condition = $queryDecode['condition'];


        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Stradetegy")
            ->setLastModifiedBy("Stradetegy")
            ->setTitle("Search Global Download");

        $sheet = 0;
        foreach ($categoryDecode as $key => $category) {

            // definisi untuk mendapatkan product
            $filter = array();

            $queryFirst = array(
                "query_string" => array(
                    'query' => $q[0]
                )
            );

            // definisi digunakan untuk pencarian dengan company_as tertentu
            $filter['bool']['must'][] = array(
                "query" => array(
                    "nested" => array(
                        "path" => (preg_match('/value.companyjoin/i', $collect[0])) ? "value.companyjoin" : "value",
                        "query" => array(
                            "query_string" => array(

                                "default_field" => 'value.company_as',
                                "query" => ($key == 'product') ? 'exporter' : $key

                            )
                        )
                    )
                )
            );

            // start memasukan query
            $i = 0;
            if ($collect[0] != 'all') {
                $filter['bool']['must'][] = array(
                    "query" => array(
                        "nested" => array(
                            "path" => (preg_match('/value.companyjoin/i', $collect[0])) ? "value.companyjoin" : "value",
                            "query" => array(
                                "query_string" => array(

                                    "default_field" => $this->nameOfField($collect[0]),
                                    "query" => $q[0]

                                )
                            )
                        )
                    )
                );


                $i = 1;
            }

            if (!empty($condition)) {
                foreach ($condition as $cond) {
                    if ($cond == 'and') {
                        if ($collect[$i] == 'all') {
                            $filter['bool']['must'][] = array(
                                'query' => array(
                                    "query_string" => array(
                                        'query' => $q[$i]
                                    )
                                )
                            );
                        } else {

                            if ($this->nameOfField($collect[$i]) == 'value.company_name' or $this->nameOfField($collect[$i]) == 'value.company_address' or $collect[$i] == "value.country") {
                                $filter['bool']['must'][] = array(
                                    "query" => array(
                                        "nested" => array(
                                            "path" => "value",
                                            "query" => array(
                                                "query_string" => array(
                                                    "default_field" => $this->nameOfField($collect[$i]),
                                                    "query" => $q[$i]
                                                )
                                            )
                                        )
                                    )
                                );
                            } else {
                                $filter['bool']['must'][] = array(
                                    "query" => array(
                                        "nested" => array(
                                            "path" => "value.companyjoin",
                                            "query" => array(
                                                "query_string" => array(
                                                    "default_field" => $this->nameOfField($collect[$i]),
                                                    "query" => $q[$i]
                                                )
                                            )
                                        )
                                    )
                                );
                            }


                        }
                    }

                    if ($cond == 'or') {
                        if ($collect[$i] == 'all') {
                            $filter['bool']['should'][] = array(
                                'query' => array(
                                    "query_string" => array(
                                        'query' => $q[$i]
                                    )
                                )
                            );
                        } else {

                            if ($this->nameOfField($collect[$i]) == 'value.company_name' or $this->nameOfField($collect[$i]) == 'value.company_address' or $collect[$i] == "value.country") {
                                $filter['bool']['should'][] = array(
                                    "query" => array(
                                        "nested" => array(
                                            "path" => "value",
                                            "query" => array(
                                                "query_string" => array(
                                                    "default_field" => $this->nameOfField($collect[$i]),
                                                    "query" => $q[$i]
                                                )
                                            )
                                        )
                                    )
                                );
                            } else {
                                $filter['bool']['should'][] = array(
                                    "query" => array(
                                        "nested" => array(
                                            "path" => "value.companyjoin",
                                            "query" => array(
                                                "query_string" => array(
                                                    "default_field" => $this->nameOfField($collect[$i]),
                                                    "query" => $q[$i]
                                                )
                                            )
                                        )
                                    )
                                );
                            }


                        }
                    }

                    if ($cond == 'not') {
                        if ($collect[$i] == 'all') {
                            $filter['bool']['must_not'][] = array(
                                'query' => array(
                                    "query_string" => array(
                                        'query' => $q[$i]
                                    )
                                )
                            );
                        } else {

                            if ($this->nameOfField($collect[$i]) == 'value.company_name' or $this->nameOfField($collect[$i]) == 'value.company_address' or $collect[$i] == "value.country") {
                                $filter['bool']['must_not'][] = array(
                                    "query" => array(
                                        "nested" => array(
                                            "path" => "value",
                                            "query" => array(
                                                "query_string" => array(
                                                    "default_field" => $this->nameOfField($collect[$i]),
                                                    "query" => $q[$i]
                                                )
                                            )
                                        )
                                    )
                                );
                            } else {

                                $filter['bool']['must_not'][] = array(
                                    "query" => array(
                                        "nested" => array(
                                            "path" => "value.companyjoin",
                                            "query" => array(
                                                "query_string" => array(
                                                    "default_field" => $this->nameOfField($collect[$i]),
                                                    "query" => $q[$i]
                                                )
                                            )
                                        )
                                    )
                                );
                            }
                        }
                    }

                    $i++;
                }

            }


            // digunakan untuk memfilter dari keyword yang telah ditentukan
            $filterKeyword = $this->repo('JariffAdminBundle:FilterKeywords')->findAll();

            foreach ($filterKeyword as $keys) {

                if (!isset($keyword))
                    $keyword = $keys->getKeyword();
                else
                    $keyword .= ' or ' . $keys->getKeyword();
            }


            $filter['bool']['must_not'][] = array(
                'query' => array(
                    "query_string" => array(
                        'query' => $keyword
                    )
                )
            );


            // menambahkan elemen strong untuk keyword di product
            $highlight = array(
                "highlight" => array(
                    "number_of_fragments" => 3,
                    "fragment_size" => 150,
                    "tag_schema" => "styled",
                    "fields" => array(
                        "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                        "value.companyjoin.product_description" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    )
                )
            );

            $toPacket = 'fos_elastica.finder.jariff.all_data';

            $finder = $this->container->get($toPacket);
            $query = new \Elastica\Query();
            $facet = new \Elastica\Facet\Terms('tags');
            $facetCategory = new \Elastica\Facet\Terms('category');


            $facetCategory->setField("value.company_as");
            $facetCategory->setSize(2);
            $facetCategory->setOrder("reverse_term");
            $facetCategory->setNested("value");

            $facet->setField("value.slug_country_ori");
            $facet->setSize(10);
            $facet->setNested("value");

            $queryDSL = array_merge(array(
                    "query" => array_merge(array(
                            "filtered" => array_merge(array(
                                    "query" => $queryFirst
                                ), (!empty($filter)) ? array("filter" => $filter) : array()
                            )
                        )
                    )
                )
                , $highlight,
                array('from' => ($category['all'] > 0) ? 0 : $category['start'],
                    'size' => ($category['all'] > 0) ? ($key == 'product') ? $categoryDecode['exporter']['all'] : $category['all'] : $category['end'])
            );


            $query->setRawQuery($queryDSL);
            $query->addFacet($facet);
            $query->addFacet($facetCategory);


            $res = $finder->findPaginated($query);


            $title = ucfirst($key);


            $cell = $phpExcelObject->createSheet($sheet)->setTitle($title);

            $columnRemove = array();

            // category importer
            if ($key == 'importer') {

                $colNameImporter = array(
                    "Importer Name" => "A1",
                    "Importer Address" => "B1",
                    "Total All Shipment" => "C1",
                    "Last Imports - From" => "D1",
                    "Last Imports - Country" => "E1",
                    "Last Imports - Contents" => "F1",
                    "Last Imports - Weight(Kg)" => "G1",
                    "From Port" => "H1",
                    "Via Port" => "I1",
                    "Last Imports - Marks" => "J1",
                );

                $j = 0;


                foreach ($colNameImporter as $key => $col) {

                    if (in_array($key, $category['column'])) {
                        $cell->setCellValue($col, $key);
                    } else {
                        $columnRemove[] = $col;
                    }

                    $j++;
                }

                $loop = 2;

                $resHybird = $finder->findHybrid($query);

                $extention = new StringExtension();


                foreach ($res as $row) {

                    $totalCompanyJoin = count($row->getValue()->getCompanyJoin());

                    $cell->setCellValue('A' . $loop, $row->getValue()->getCompanyName());
                    $cell->setCellValue('B' . $loop, $row->getValue()->getCompanyAddress());
                    $cell->setCellValue('C' . $loop, $row->getValue()->getShipment());
                    $cell->setCellValue('D' . $loop, $row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getCompanyJoinName());
                    $cell->setCellValue('E' . $loop, $extention->twolettercodeslug($row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getSlugCountryOri())['country']);
                    $cell->setCellValue('F' . $loop, $row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getProductDescription());
                    $cell->setCellValue('G' . $loop, $row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getWeight());
                    $cell->setCellValue('H' . $loop, $row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getUsPort());
                    $cell->setCellValue('I' . $loop, $row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getForeignPort());
                    $cell->setCellValue('J' . $loop, $row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getOtherInfo());

                    $loop++;
                }

            }

            // category exporter
            if ($key == 'exporter') {
                $colNameImporter = array(
                    "Exporter Name" => "A1",
                    "Exporter Address" => "B1",
                    "Total All Shipment" => "C1",
                    "Last Exports - From" => "D1",
                    "Last Exports - Country" => "E1",
                    "Last Exports - Contents" => "F1",
                    "Last Exports - Weight(Kg)" => "G1",
                    "From Port" => "H1",
                    "Via Port" => "I1",
                    "Last Exports - Marks" => "J1",
                );

                $j = 0;


                foreach ($colNameImporter as $key => $col) {

                    if (in_array($key, $category['column'])) {
                        $cell->setCellValue($col, $key);
                    } else {
                        $columnRemove[] = $col;
                    }

                    $j++;
                }

                $loop = 2;

                $resHybird = $finder->findHybrid($query);


                $extention = new StringExtension();


                foreach ($res as $row) {

                    $totalCompanyJoin = count($row->getValue()->getCompanyJoin());

                    $cell->setCellValue('A' . $loop, $row->getValue()->getCompanyName());
                    $cell->setCellValue('B' . $loop, $row->getValue()->getCompanyAddress());
                    $cell->setCellValue('C' . $loop, $row->getValue()->getShipment());
                    $cell->setCellValue('D' . $loop, $row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getCompanyJoinName());
                    $cell->setCellValue('E' . $loop, $extention->twolettercodeslug($row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getSlugCountryOri())['country']);
                    $cell->setCellValue('F' . $loop, $row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getProductDescription());
                    $cell->setCellValue('G' . $loop, $row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getWeight());
                    $cell->setCellValue('H' . $loop, $row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getUsPort());
                    $cell->setCellValue('I' . $loop, $row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getForeignPort());
                    $cell->setCellValue('J' . $loop, $row->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getOtherInfo());

                    $loop++;
                }

            }

            // category product
            if ($key == 'product') {
                $colNameImporter = array(
                    "Product Description" => "A1",
                    "Arrival Date" => "B1",
                    "Shipper" => "C1",
                    "Consignee" => "D1",
                    "Quantity" => "E1",
                    "Weight (Kg)" => "F1",
                    "Marks & Numbers" => "G1",
                    "Free 1" => "H1",
                    "Free 2" => "I1",
                    "Free 3" => "J1",
                );

                $j = 0;


                foreach ($colNameImporter as $key => $col) {

                    if (in_array($key, $category['column'])) {
                        $cell->setCellValue($col, $key);
                    } else {
                        $columnRemove[] = $col;
                    }

                    $j++;
                }

                $loop = 2;

                $resHybird = $finder->findHybrid($query);


                foreach ($resHybird as $row) {

                    if (!empty($row->getResult()->getHit()["highlight"])) {
                        $totalCompanyJoin = count($row->getTransformed()->getValue()->getCompanyJoin());
                        $str = $row->getResult()->getHit()["highlight"]['value.companyjoin.product_description'][0];

                        preg_match_all('/<strong>(.*?)<\/strong>/s', $str, $matches);

                        $splitText = preg_split('/<strong>/s', $str, 0);

                        $objRichText = new \PHPExcel_RichText();

                        foreach ($splitText as $sp) {
                            if (!empty($sp)) {
                                $sp = preg_replace(array('/<\/strong>/'), '', $sp);

                                if (preg_match("/" . $matches[1][0] . "/", $sp)) {

                                    $objBold = $objRichText->createTextRun($matches[1][0]);
                                    $objBold->getFont()->setBold(true);

                                    $nextText = preg_replace("/" . $matches[1][0] . "/", '', $sp);

                                    $objRichText->createText($nextText);
                                } else {
                                    $objRichText->createText($sp);
                                }
                            }

                        }

                        $cell->setCellValue('A' . $loop, $objRichText);;
                        $cell->setCellValue('B' . $loop, $row->getTransformed()->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getArrivalDate()->format("M d Y"));
                        $cell->setCellValue('C' . $loop, $row->getTransformed()->getValue()->getCompanyName());
                        $cell->setCellValue('D' . $loop, $row->getTransformed()->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getCompanyJoinName());
                        $cell->setCellValue('E' . $loop, $row->getTransformed()->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getQuantity());
                        $cell->setCellValue('F' . $loop, $row->getTransformed()->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getWeight());
                        $cell->setCellValue('G' . $loop, $row->getTransformed()->getValue()->getCompanyJoin()[$totalCompanyJoin - 1]->getOtherInfo());

                        $loop++;
                    }


                }


            }


            // Set active sheet index to the first sheet, so Excel opens this as the first sheet

            $phpExcelObject->setActiveSheetIndex($sheet);

            foreach ($columnRemove as $imRemove) {
                $coloumnRegex = preg_replace('/[0-9]/', '', $imRemove);
                $phpExcelObject->getActiveSheet()->getColumnDimension($coloumnRegex)->setVisible(false);
            }

            $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(25);
            $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth(25);
            $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setWidth(25);
            $phpExcelObject->getActiveSheet()->getColumnDimension('H')->setWidth(25);
            $phpExcelObject->getActiveSheet()->getColumnDimension('I')->setWidth(25);
            $phpExcelObject->getActiveSheet()->getColumnDimension('J')->setWidth(20);

            $phpExcelObject->getActiveSheet()->getStyle('A1:J1')->applyFromArray(
                array('font' => array('bold' => true, 'size' => 10, 'name' => 'Arial')));

            $phpExcelObject->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
            $phpExcelObject->getActiveSheet()->getDefaultStyle()->applyFromArray(array('font' => array('size' => 10, 'name' => 'Arial')));
            $phpExcelObject->getActiveSheet()->getStyle('A1:J1')->getFill()->applyFromArray(
                array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array('rgb' => "33bee5")
                ));

            $sheet++;
        }

        $phpExcelObject->setActiveSheetIndex(0);
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');

        $writer->save(realpath(__DIR__ . '/../../../../../') . "/web/convert/" . $exportDownloadQueque->getFileName() . '.xls');

        echo filesize(realpath(__DIR__ . '/../../../../../') . "/web/convert/" . $exportDownloadQueque->getFileName() . '.xls') / 1000;
        die();
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=' . $exportDownloadQueque->getFileName() . '.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

//        $response->setContent(readfile($exportDownloadQueque->getFileName() . '.xls'));

        file_put_contents(realpath(__DIR__ . '/../../../../../') . "/web/convert/" . $exportDownloadQueque->getFileName() . '.xls', $response, LOCK_EX);

        die();
//        return $response;
    }

    public function nameOfField($collect)
    {
        if ($collect == 'importer_name' or $collect == 'exporter_name') {
            return "value.company_name";
        }

        if ($collect == 'importer_address' or $collect == 'exporter_adress') {
            return "value.company_address";
        }

        return $collect;
    }
}