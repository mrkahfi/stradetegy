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
use Elastica\Client as ClientEs;
use FOS\ElasticaBundle\Client;
use Jariff\ProjectBundle\Controller\BaseController as BaseCon; 

class ExportShipmentsUSImportsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
        ->setName('jariff:cron:export-shipments-us-imports')
        ->setDescription('Mengeksekusi file export shipments US Imports')
        ->setHelp('<<<EOF
            <info>jariff:cron:export</info>
            Command digunakan untuk menyimpan data pencarian

            Syntax :
            php app/console jariff:cron:export-shipments-us-imports
            EOF'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //semua cron file dengan command seperti ini harus ada file lock nya,
        //untuk menghindari duplikasi proses
        //nama file nya 
        // 'lock-'.str_replace(':', '-', 'nama command di definisi fungsi configure di atas').'.txt'
        $fp = fopen(getcwd() . '/lock/jariff-export-us-imports.lock', 'w');

        if (flock($fp, LOCK_EX | LOCK_NB)) {

            $em = $this->getContainer()->get('doctrine.orm.entity_manager');

            echo "mulai\n";
            $exportDownloadQueque = $em->getRepository('JariffMemberBundle:ExportTools')->findOneBy(array('process' => 1,'collection' => 'us-imports'));




            if (!$exportDownloadQueque)
                die('empty');            

            $util = new StringTwig();
            $util2 = new StringExtension();
            $s_cache = json_decode($util->decrypted($exportDownloadQueque->getQuery()));

            $phpExcelObject = $this->getContainer()->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()->setCreator("TRADEtegy")
            ->setLastModifiedBy("TRADEtegy")
            ->setTitle("Shipments Data US Imports");

            $fields = array('product_desc',
                'consignee_name',
                'consignee_address',
                'shipper_name',
                'shipper_address',
                'notify_party_names',
                'last_vist_foreign_port',
                'bill_of_lading',
                'vessel_name',
                'master_bill_of_lading',
                'country',
                'marks_numbers',
                'carrier_sasc_code',
                "container_id",
                "unloading_port");


            $util->setEm($em);
            $size = $exportDownloadQueque->getDownloadTo() - ($exportDownloadQueque->getDownloadFrom() - 1);

            $BaseCon = new BaseCon;

            $queryDSL = $BaseCon->queryDSL($s_cache, "actual_arrival_date", "product_desc", $fields, $size, 1,array('em' => $em));



            $query = new \Elastica\Query();
            $query->setRawQuery($queryDSL);

            $client = new \Elastica\Client(array("host" => $this->getContainer()->getParameter('es_server')
                ));

            $searchable = new Index($client, 'trade-usa-imports-*');

            $adapter = new ElasticaAdapter($searchable, $query);

            $res = new Pagerfanta($adapter);            
            $res->setMaxPerPage($size);



            if (empty($s_cache->column))
                $column = array('actual_arrival_date', 'product_desc',
                    'consignee_name', 'consignee_address', 'shipper_name', 'shipper_address',
                    'notify_party_name', 'notify_party_address', 'quantity', 'quantity_unit',
                    'teu', 'hs_code', 'marks_and_numbers','mode_of_transportation',
                    'weight', 'container_id','carrier_sasc_code','bill_of_lading','master_bill_of_lading',
                    'place_of_receipt','vessel_name','container_type','loading_port','voyage'
                    );
            else {
                $column = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
            }



            $columns = array();
            $cell = $phpExcelObject->createSheet(0)->setTitle('Shipments US Imports');
            $i = 0;
            foreach ($column as $col) {
                $columns[$util->us_column_view($col)] = $util->rowXls($i) . '1';
                $cell->setCellValue($util->rowXls($i) . '1', $util->us_column_view($col));
                $i++;
            }


            $entityWeight = $em->getRepository('JariffMemberBundle:MemberSetting')->findOneByMember($exportDownloadQueque->getMember());

            if ($entityWeight) {
                $weightOri = $entityWeight->getWeight();
            } else {
                $weightOri = 'KG';
            }


            $loop = 2;
            $startProcentage = 0;
            $loopingPercentage = 1;
            $procentageUP = 0;
            $resHybird = $res->getCurrentPageResults()->getResults();
            foreach ($resHybird as $r) {
                $i = 0;
                $startProcentage++;
                foreach ($column as $col) {
                    $cell->setCellValue($util->rowXls($i) . $loop, $r->getHit()["_source"][$col]);

                    if ($col == "product_desc") {
                        $clear = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($r->getHit()["_source"][$col]))))));
                        $cell->setCellValue($util->rowXls($i) . $loop, $clear);
                    } elseif ($col == "weight") {
                        $cell->setCellValue($util->rowXls($i) . $loop, $util->setting_gross_weight($r->getHit()["_source"][$col], $r->getHit()["_source"]['weight_unit'], $exportDownloadQueque->getMember()));
                    } elseif ($col == "actual_arrival_date") {
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

                if($size > 100){
                    $setProcentage = ($size / 100) * $loopingPercentage;

                    if($setProcentage == $startProcentage){
                        $procentageUP++;
                        $loopingPercentage++;
                        $exportDownloadQueque->setProcentage($procentageUP);
                        $em->persist($exportDownloadQueque);
                        $em->flush();
                        sleep(1);
                    }
                }else{
                    $exportDownloadQueque->setProcentage(100);
                    $em->persist($exportDownloadQueque);
                    $em->flush();
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
                ->setSubject('Your TRADEtegy file download for '. $filenameexplode[0] .' is ready')
                ->setFrom('no-reply@tradetegy.com')
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
