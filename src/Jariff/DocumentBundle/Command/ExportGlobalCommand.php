<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\DocumentBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use PDO;
use Jariff\ProjectBundle\Util as Util;
use Jariff\ProjectBundle\Twig\StringTwoExtension as StringTwig;

use Jariff\ProjectBundle\Twig\StringExtension as StringExtension;

class ExportGlobalCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('jariff:cron:export-global')
            ->setDescription('Mengeksekusi file export global')
            ->setHelp('<<<EOF
            <info>jariff:cron:export</info>
            Command digunakan untuk menyimpan data pencarian

            Syntax :
            php app/console jariff:cron:export-global
            EOF'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //semua cron file dengan command seperti ini harus ada file lock nya,
        //untuk menghindari duplikasi proses
        //nama file nya 
        // 'lock-'.str_replace(':', '-', 'nama command di definisi fungsi configure di atas').'.txt'
        $fp = fopen(getcwd() . '/lock/jariff-export-global.lock', 'w');

        if (flock($fp, LOCK_EX | LOCK_NB)) {

            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            echo "mulai\n";
            $exportDownloadQueque = $dm->getRepository('JariffDocumentBundle:ExportsSearchGlobal')->findOneBy(array('process' => 1));

            if (!$exportDownloadQueque)
                die('empty');

            $util = new StringTwig();
            $s_cache = json_decode($util->decrypted($exportDownloadQueque->getKeyword()));


            $phpExcelObject = $this->getContainer()->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()->setCreator("Stradetegy")
                ->setLastModifiedBy("Stradetegy")
                ->setTitle("Search Global Download");

            if (preg_match('/all/i', $s_cache->search[0]->collect)) {
                $queryFirst = array(
                    "query_string" => array(
                        'query' => $s_cache->search[0]->q
                    )
                );
            } else {

                $queryFirst = array(
                    "query_string" => array(
                        'query' => $s_cache->search[0]->q
                    )
                );

                $filter['bool']['must'][] = array(

                    "query" => array(
                        "query_string" => array(

                            "default_field" => $s_cache->search[0]->collect,
                            "query" => $s_cache->search[0]->q

                        )
                    )

                );
            }


            $filterKeyword = $em->getRepository('JariffAdminBundle:FilterKeywords')->findAll();

            foreach ($filterKeyword as $key) {

                if (!isset($keywords))
                    $keywords = $key->getKeyword();
                else
                    $keywords .= ' or ' . $key->getKeyword();
            }


            $filter['bool']['must_not'][] = array(
                'query' => array(
                    "query_string" => array(
                        'query' => $keywords
                    )
                )
            );

            $i = 0;


            foreach ($s_cache->search as $s) {
                if ($i > 0) {
                    if (preg_match('/all/i', $s->collect)) {
                        if (preg_match('/and/i', $s->condition)) {
                            $filter['bool']['must'][] = array(
                                'query' => array(
                                    "query_string" => array(
                                        'query' => $s->q
                                    )
                                )
                            );
                        }

                        if (preg_match('/or/i', $s->condition)) {
                            $filter['bool']['should'][] = array(
                                'query' => array(
                                    "query_string" => array(
                                        'query' => $s[$i]->q
                                    )
                                )
                            );
                        }

                        if (preg_match('/not/i', $s->condition)) {
                            $filter['bool']['must_not'][] = array(
                                'query' => array(
                                    "query_string" => array(
                                        'query' => $s->q
                                    )
                                )
                            );
                        }
                    } else {


                        if (preg_match('/and/i', $s->condition)) {
                            $filter['bool']['must'][] = array(
                                "query" => array(
                                    "query_string" => array(

                                        "default_field" => $s->collect,
                                        "query" => $s->q

                                    )
                                )
                            );
                        }

                        if (preg_match('/or/i', $s->condition)) {
                            $filter['bool']['should'][] = array(
                                "query" => array(
                                    "query_string" => array(

                                        "default_field" => $s->collect,
                                        "query" => $s->q

                                    )
                                )
                            );
                        }

                        if (preg_match('/not/i', $s->condition)) {
                            $filter['bool']['must_not'][] = array(
                                "query" => array(
                                    "query_string" => array(

                                        "default_field" => $s->collect,
                                        "query" => $s->q

                                    )
                                )
                            );
                        }
                    }
                }
                $i++;
            }


            $countryForms = array();
            if (isset($s_cache->country)) {
                $qCountry = '';
                $i = 0;
                foreach ($s_cache->country as $country) {
                    $qCountry = $qCountry . $country;

                    if (count($s_cache->country) > 0 and $i < count($s_cache->country)) {
                        $qCountry = $qCountry . ' or ';
                    }

                    $countryForms[] = $country;

                    $i++;
                }


                $filter['bool']['must'][] = array(
                    "query" => array(
                        "query_string" => array(

                            "default_field" => 'slug_country',
                            "query" => $qCountry

                        )
                    )
                );
            }

            if (isset($s_cache->category)) {
                if (count($s_cache->category) >= 1 and count($s_cache->category) <= 2) {
                    $keyCategory = '';
                    foreach ($s_cache->category as $cat) {
                        if (empty($keyCategory)) {
                            $keyCategory = $cat;
                        } else {
                            $keyCategory = $keyCategory . ' or ' . $cat;
                        }
                    }

                    $filter['bool']['must'][] = array(
                        "query" => array(
                            "query_string" => array(

                                "default_field" => 'company_as',
                                "query" => $keyCategory

                            )
                        )
                    );
                }

            }


            $highlight = array(
                "highlight" => array(
                    "number_of_fragments" => 3,
                    "fragment_size" => 150,
                    "tag_schema" => "styled",
                    "fields" => array(
                        "_all" => array("pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                        "product_desc" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                    )
                )
            );

            $toPacket = 'fos_elastica.finder.jariff.company_detail';

            $finder = $this->getContainer()->get($toPacket);
            $query = new \Elastica\Query();
            $countryAggs = new \Elastica\Aggregation\Terms('country');

            $countryAggs->setField('slug_country');
            $countryAggs->setSize(10);

            $queryDSL = array_merge(array(
                    "query" => array_merge(array(
                            "filtered" => array_merge(array(
                                    "query" => $queryFirst
                                ), (!empty($filter)) ? array("filter" => $filter) : array(),
                                array(
                                    "_cache" => true
                                )
                            )
                        )
                    )
                )
                , $highlight,
                array('from' => $exportDownloadQueque->getDownloadFrom())
            );


            $query->setRawQuery($queryDSL);
            $query->addAggregation($countryAggs);

            $res = $finder->findPaginated($query);

            $resHybird = $finder->findHybrid($query, $exportDownloadQueque->getDownloadTo());


            if (empty($s_cache->column))
                $column = array('actual_arrival_date', 'consignee_name', 'shipper_name',
                    'quantity', 'weight', 'product_description', 'marks_numbers','company_as');
            else {
                $column = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
            }

            $columns = array();
            $cell = $phpExcelObject->createSheet(0)->setTitle('Global');
            $i = 0;
            foreach ($column as $col) {
                $columns[$util->us_column_view($col)] = $util->rowXls($i) . '1';
                $cell->setCellValue($util->rowXls($i) . '1', $util->us_column_view($col));
                $i++;
            }


            $loop = 2;
            $util->setEm($em);
            foreach ($resHybird as $r) {
                $i = 0;
                foreach ($column as $col) {

                    if($r->getTransformed()->getCompanyAs() == 'product'){
                        if($col == 'consignee_name')
                            $col = 'consignee_name_pr';
                        if($col == 'consignee_address')
                            $col = 'consignee_address_pr';
                        if($col == 'shipper_name')
                            $col = 'shipper_name_pr';
                        if($col == 'shipper_address')
                            $col = 'shipper_address_pr';
                    }

                    $cell->setCellValue($util->rowXls($i) . $loop, $util->us_column_get($r->getTransformed(), $col, $exportDownloadQueque->getUserId(),true));
                    $i++;
                }
                $loop++;
            }

            $phpExcelObject->setActiveSheetIndex(0);

            $i = 0;
            foreach ($column as $col) {
                $phpExcelObject->getActiveSheet()->getColumnDimension($util->rowXls($i))->setWidth(30);
                $i++;
            }

            $phpExcelObject->getActiveSheet()->getStyle($util->rowXls(0) . '1:' . $util->rowXls(count($column) - 1) . '1')->applyFromArray(
                array('font' => array('bold' => true, 'size' => 10, 'name' => 'Arial')));

            $phpExcelObject->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
            $phpExcelObject->getActiveSheet()->getDefaultStyle()->applyFromArray(array('font' => array('size' => 10, 'name' => 'Arial')));
            $phpExcelObject->getActiveSheet()->getStyle($util->rowXls(0) . '1:' . $util->rowXls(count($column) - 1) . '1')->getFill()->applyFromArray(
                array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array('rgb' => "33bee5")
                ));


            // create the writer
            $writer = $this->getContainer()->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');

            $writer->save(realpath(__DIR__ . '/../../../../') . "/web/convert/" . $exportDownloadQueque->getFileName() . '.' . $exportDownloadQueque->getFileType());

            $exportDownloadQueque->setProcess(0);
            $dm->getManager()->persist($exportDownloadQueque);
            $dm->getManager()->flush();

            echo "finished\n";


            flock($fp, LOCK_UN);
        } else {
            $output->writeln('Program pengantri untuk aktifasi sedang berjalan, terasa aneh ? coba cek log nya.');
        }
    }
}
