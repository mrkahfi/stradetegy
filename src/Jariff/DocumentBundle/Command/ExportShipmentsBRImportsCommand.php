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
use Elastica\Index;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ElasticaAdapter;
use Elastica\Query as QueryEs;

class ExportShipmentsBRImportsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('jariff:cron:export-shipments-br-imports')
            ->setDescription('Mengeksekusi file export shipments BR IM')
            ->setHelp('<<<EOF
            <info>jariff:cron:export</info>
            Command digunakan untuk menyimpan data pencarian

            Syntax :
            php app/console jariff:cron:export-shipments-br-imports
            EOF'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //semua cron file dengan command seperti ini harus ada file lock nya,
        //untuk menghindari duplikasi proses
        //nama file nya 
        // 'lock-'.str_replace(':', '-', 'nama command di definisi fungsi configure di atas').'.txt'
        $fp = fopen(getcwd() . '/lock/jariff-export-br-imports.lock', 'w');

        if (flock($fp, LOCK_EX | LOCK_NB)) {

            $em = $this->getContainer()->get('doctrine.orm.entity_manager');

            echo "mulai\n";
            $exportDownloadQueque = $em->getRepository('JariffMemberBundle:ExportTools')->findOneBy(array('process' => 1,'collection' => 'br-imports'));


            if (!$exportDownloadQueque)
                die('empty');

            // $exportDownloadQueque->setProcess(0);
            // $em->persist($exportDownloadQueque);
            // $em->flush();

            $util = new StringTwig();
            $util2 = new StringExtension();
            $s_cache = json_decode($util->decrypted($exportDownloadQueque->getQuery()));

            $phpExcelObject = $this->getContainer()->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()->setCreator("Stradetegy")
            ->setLastModifiedBy("Stradetegy")
            ->setTitle("Shipments Data BR Imports");

            $fields = array('country','product', 'quantity');

            if (preg_match('/all/i', $s_cache->search[0]->collect)) {
                $queryFirst = array(
                    "multi_match" => array(
                        'query' => $s_cache->search[0]->q,
                        "type" => "phrase",
                        "fields" => $fields
                        )
                    );
            } else {

                $queryFirst = array(
                    "multi_match" => array(
                        'query' => $s_cache->search[0]->q,
                        "type" => "phrase",
                        "fields" => array($s_cache->search[0]->collect)
                        )
                    );
            }


            $filterKeyword = $em->getRepository('JariffAdminBundle:FilterKeywords')->findAll();

            $keywords = '';
            foreach ($filterKeyword as $key) {

                if (!isset($keywords))
                    $keywords = $key->getKeyword();
                else
                    $keywords .= ' or ' . $key->getKeyword();
            }

            if ($keywords != '') {
                $filter['bool']['must_not'][] = array(
                    'query' => array(
                        "query_string" => array(
                            'query' => $keywords
                            )
                        )
                    );
            }


            $i = 0;


            foreach ($s_cache->search as $s) {
                if ($i > 0) {
                    if (preg_match('/all/i', $s->collect)) {
                        if (preg_match('/and/i', $s->condition)) {
                            $filter['bool']['must'][] = array(
                                'query' => array(
                                    "multi_match" => array(
                                        "query" => $s_cache->search[$i]->q,
                                        "type" => "phrase",
                                        "fields" => array($s_cache->search[$i]->collect)
                                        )
                                    )
                                );
                        }

                        if (preg_match('/or/i', $s->condition)) {
                            $filter['bool']['should'][] = array(
                                'query' => array(
                                    "multi_match" => array(
                                        "query" => $s_cache->search[$i]->q,
                                        "type" => "phrase",
                                        "fields" => array($s_cache->search[$i]->collect)
                                        )
                                    )
                                );
                        }

                        if (preg_match('/not/i', $s->condition)) {
                            $filter['bool']['must_not'][] = array(
                                'query' => array(
                                    "multi_match" => array(
                                        "query" => $s_cache->search[$i]->q,
                                        "type" => "phrase",
                                        "fields" =>array($s_cache->search[$i]->collect)
                                        )
                                    )
                                );
                        }
                    } else {


                        if (preg_match('/and/i', $s->condition)) {
                            $filter['bool']['must'][] = array(
                                "query" => array(
                                    "multi_match" => array(
                                        "query" => $s_cache->search[$i]->q,
                                        "type" => "phrase",
                                        "fields" => array($s_cache->search[$i]->collect)
                                        )
                                    )
                                );
                        }

                        if (preg_match('/or/i', $s->condition)) {
                            $filter['bool']['should'][] = array(
                                "query" => array(
                                    "multi_match" => array(
                                        "query" => $s_cache->search[$i]->q,
                                        "type" => "phrase",
                                        "fields" => array($s_cache->search[$i]->collect)
                                        )
                                    )
                                );
                        }

                        if (preg_match('/not/i', $s->condition)) {
                            $filter['bool']['must_not'][] = array(
                                "query" => array(
                                    "multi_match" => array(
                                        "query" => $s_cache->search[$i]->q,
                                        "type" => "phrase",
                                        "fields" => array($s_cache->search[$i]->collect)
                                        )
                                    )
                                );
                        }
                    }
                }
                $i++;
            }

            $filter['bool']['must'][] = array(
                "range" => array(
                    "date" => array(

                        "from" => $s_cache->date_range->from,
                        "to" => $s_cache->date_range->to

                        )
                    )
                );

            $util->setEm($em);

            $query = new \Elastica\Query();

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
                ),
//            $highlight,
            array('from' => $exportDownloadQueque->getDownloadFrom() - 1),
            array('sort' => array('date' => array("order" => "desc")))
            );


            $query->setRawQuery($queryDSL);

            $client = new \Elastica\Client(array("host" => $this->getContainer()->getParameter('es_server')));
            $searchable = new Index($client, 'stradetegy-im-br');

            $adapter = new ElasticaAdapter($searchable, $query);
            $res = new Pagerfanta($adapter);

            $size = $exportDownloadQueque->getDownloadTo() - ($exportDownloadQueque->getDownloadFrom() - 1);
            $res->setMaxPerPage($size);

            if (empty($s_cache->column))
                $column = array('date', 'customs', 'via',
            'country', 'nomen','product','fob', 'quantity', 'measure', 'net');
            else {
                $column = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
            }

            $columns = array();
            $cell = $phpExcelObject->createSheet(0)->setTitle('Shipments BR Imports');
            $i = 0;
            foreach ($column as $col) {
                $columns[$util->br_column_view($col)] = $util->rowXls($i) . '1';
                $cell->setCellValue($util->rowXls($i) . '1', $util->br_column_view($col));
                $i++;
            }

            $entityWeight = $em->getRepository('JariffMemberBundle:MemberSetting')->findOneByMember($exportDownloadQueque->getMember());

            if ($entityWeight) {
                $weightOri = $entityWeight->getWeight();
            } else {
                $weightOri = 'KG';
            }


            $loop = 2;
            $resHybird = $res->getCurrentPageResults()->getResults();
            foreach ($resHybird as $r) {
                $i = 0;
                foreach ($column as $col) {
                    $cell->setCellValue($util->rowXls($i) . $loop, $r->getHit()["_source"][$col]);

                    if ($col == "product") {
                        $clear = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($r->getHit()["_source"][$col]))))));
                        $cell->setCellValue($util->rowXls($i) . $loop, $clear);
                    } elseif ($col == "weight") {
                        $cell->setCellValue($util->rowXls($i) . $loop, $util->setting_gross_weight($r->getHit()["_source"][$col], $r->getHit()["_source"]['weight_unit'], $exportDownloadQueque->getMember()));
                    } elseif ($col == "date") {
                        $cell->setCellValue($util->rowXls($i) . $loop, date("m/d/Y",strtotime($r->getHit()["_source"][$col])));
                    } elseif ($col == "marks_and_numbers") {
                        $clear = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($r->getHit()["_source"][$col]))))));

                        $cell->setCellValue($util->rowXls($i) . $loop,$clear);
                    } elseif ($col == "weight_unit") {
                        $cell->setCellValue($util->rowXls($i) . $loop,$weightOri);
                    } elseif ($col == "mode_of_transportation") {
                        $cell->setCellValue($util->rowXls($i) . $loop,$util->us_column_code_transportation($r->getHit()["_source"][$col]));
                    } else {
                        $cell->setCellValue($util->rowXls($i) . $loop,$r->getHit()["_source"][$col]);
                    }
                    $i++;
                }
                $loop++;
            }

            $phpExcelObject->setActiveSheetIndex(0);

            $i = 0;
            foreach ($column as $col) {
                $phpExcelObject->getActiveSheet()->getColumnDimension($util->rowXls($i))->setAutoSize(true);
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



            $emailSend = $exportDownloadQueque->getEmail();


            $filenameexplode = explode("-", $exportDownloadQueque->getFileName());


            if($exportDownloadQueque->getSendMail()){
                $templating = $this->getContainer()->get('templating');
                $mailer     = $this->getContainer()->get('mailer');
                $message = \Swift_Message::newInstance()
                ->setSubject('Your sTRADEtegy file download for '. $filenameexplode[0] .' is ready')
                ->setFrom('no-reply@stradetegy.com')
                ->setTo($emailSend)
//                    ->setCc('sales@stradetegy.com')
//                    ->setBcc('ardianys@outlook.com')
                ->setBody($templating->render('JariffMemberBundle:Email:success_export.txt.twig', array('entity' => $exportDownloadQueque)))
                ->addPart($templating->render('JariffMemberBundle:Email:success_export.html.twig', array('entity' => $exportDownloadQueque)), 'text/html')
                ;
                $mailer->send($message);
            }

            $exportDownloadQueque->setProcess(0);
            $em->persist($exportDownloadQueque);
            $em->flush();


            echo "finished\n";


            flock($fp, LOCK_UN);
        } else {
            $output->writeln('Program pengantri untuk aktifasi sedang berjalan, terasa aneh ? coba cek log nya.');
        }
    }
}
