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

class ExportShipmentsARImportsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
        ->setName('jariff:cron:export-shipments-ar-imports')
        ->setDescription('Mengeksekusi file export shipments AR IM')
        ->setHelp('<<<EOF
            <info>jariff:cron:export</info>
            Command digunakan untuk menyimpan data pencarian

            Syntax :
            php app/console jariff:cron:export-shipments-ar-imports
            EOF'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //semua cron file dengan command seperti ini harus ada file lock nya,
        //untuk menghindari duplikasi proses
        //nama file nya 
        // 'lock-'.str_replace(':', '-', 'nama command di definisi fungsi configure di atas').'.txt'
        $fp = fopen(getcwd() . '/lock/jariff-export-ar-imports.lock', 'w');

        if (flock($fp, LOCK_EX | LOCK_NB)) {

            $em = $this->getContainer()->get('doctrine.orm.entity_manager');

            echo "mulai\n";
            $exportDownloadQueque = $em->getRepository('JariffMemberBundle:ExportTools')->findOneBy(array('process' => 1,'collection' => 'ar-imports'));


            if (!$exportDownloadQueque)
                die('empty');

            // $exportDownloadQueque->setProcess(0);
            // $em->persist($exportDownloadQueque);
            // $em->flush();

            $util = new StringTwig();
            $util2 = new StringExtension();
            $s_cache = json_decode($util->decrypted($exportDownloadQueque->getQuery()));

            $phpExcelObject = $this->getContainer()->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()->setCreator("TRADEtegy")
            ->setLastModifiedBy("TRADEtegy")
            ->setTitle("Shipments Data AR Imports");

            $fields = array("consignee_name",
                "product",
                "embarq_port",
                "orig_country",
                "hs_code",
                "type_of_transport",
                'brand');

            $util->setEm($em);
            $size = $exportDownloadQueque->getDownloadTo() - ($exportDownloadQueque->getDownloadFrom() - 1);

            $BaseCon = new BaseCon;

            $queryDSL = $BaseCon-->queryDSL($s_cache, "date", "product", $fields, $size, $page);

            $query = new \Elastica\Query();
            $query->setRawQuery($queryDSL);

            $client = new \Elastica\Client(array("host" => $this->container->getParameter('es_server')
                ));

            $searchable = new Index($client, 'trade-ar-imports-*');

            $adapter = new ElasticaAdapter($searchable, $query);

            $res = new Pagerfanta($adapter);

            $res->setMaxPerPage($size);


            if (empty($s_cache->column)){
                $column = array(
                    'product',
                    'consignee_name',
                    'date',
                    "quantity_subitem",
                    "hs_code",
                    "brand",
                    "total_fob",
                    "total_cif",
                    "freight_us",
                    "insurance_us",
                    "gross_weight",
                    "orig_country",
                    "embarq_port",
                    "item_number",
                    "quantity_subitem",
                    "fob_item",
                    "variety",
                    "custom",
                    "number_of_packages",
                    "incoterms",
                    "attributes",
                    "type_of_transport",
                    "operation_type");
            }
            else {
                $column = (is_array($s_cache->column)) ? $s_cache->column : array($s_cache->column);
            }

            $columns = array();
            $cell = $phpExcelObject->createSheet(0)->setTitle('Shipments AR Imports');
            $i = 0;
            foreach ($column as $col) {
                $columns[$util->ar_column_view($col)] = $util->rowXls($i) . '1';
                $cell->setCellValue($util->rowXls($i) . '1', $util->ar_column_view($col));
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
