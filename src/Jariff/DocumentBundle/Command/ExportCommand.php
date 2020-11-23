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
use Jariff\DocumentBundle\Command\UrlInfo;

use Jariff\MemberBundle\Entity\ExportTools;
use Jariff\ProjectBundle\Twig\StringExtension as StringExtension;

class ExportCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('jariff:cron:export')
            ->setDescription('Mengeksekusi file export')
            ->setHelp('<<<EOF
            <info>jariff:cron:export</info>
            Command digunakan untuk menyimpan data pencarian

            Syntax :
            php app/console jariff:cron:export
            EOF'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //semua cron file dengan command seperti ini harus ada file lock nya,
        //untuk menghindari duplikasi proses
        //nama file nya 
        // 'lock-'.str_replace(':', '-', 'nama command di definisi fungsi configure di atas').'.txt'
        $fp = fopen(getcwd() . '/lock/jariff-export.lock', 'w');

        if (flock($fp, LOCK_EX | LOCK_NB)) {

            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            echo "mulai\n";
            $exportDownloadQueque = $em->getRepository('JariffMemberBundle:ExportTools')->findOneBy(array('process' => 1));

            $queryDecode = json_decode($exportDownloadQueque->getQuery(), 1);
            $categoryDecode = json_decode($exportDownloadQueque->getCategory(), 1);

            $q = $queryDecode['q'];
            $collect = $queryDecode['collect'];
            $condition = $queryDecode['condition'];

            $phpExcelObject = $this->getContainer()->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()->setCreator("Stradetegy")
                ->setLastModifiedBy("Stradetegy")
                ->setTitle("Search Global Download");

            $sheet = 0;
            foreach ($categoryDecode as $key => $category) {

                // definisi untuk mendapatkan product
                $filter = array();

                $queryFirst = array(
                    "query_string" => array(
                        'query' => Util\Util::slugify($q[0])
                    )
                );

                // definisi digunakan untuk pencarian dengan company_as tertentu
                $filter['bool']['must'][] = array(
                    "query" => array(
                        "nested" => array(
                            "path" => "value",
                            "query" => array(
                                "query_string" => array(

                                    "default_field" => 'company_as',
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
                                "path" => "value",
                                "query" => array(
                                    "query_string" => array(

                                        "default_field" => $this->nameOfField($collect[0]),
                                        "query" => Util\Util::slugify($q[0])

                                    )
                                )
                            )
                        )
                    );


                    $i = 1;
                }


                if (!empty($category['country'])) {
                    $itsCountry = $category['country'][0];
                    $k = 0;
                    foreach ($category['country'] as $country) {
                        if ($k > 1)
                            $itsCountry .= $itsCountry . ' or ' . $country;

                        $k++;
                    }

                    $filter['bool']['should'][] = array(
                        "query" => array(
                            "nested" => array(
                                "path" => "value",
                                "query" => array(
                                    "query_string" => array(
                                        "default_field" => 'slug_country',
                                        "query" => $itsCountry

                                    )
                                )
                            )
                        )
                    );
                }

                if (!empty($condition)) {
                    foreach ($condition as $cond) {
                        if ($cond == 'and') {
                            if ($collect[$i] == 'all') {
                                $filter['bool']['must'][] = array(
                                    'query' => array(
                                        "query_string" => array(
                                            'query' => Util\Util::slugify($q[$i])
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
                                                        "query" => Util\Util::slugify($q[$i])
                                                    )
                                                )
                                            )
                                        )
                                    );
                                } else {
                                    $filter['bool']['must'][] = array(
                                        "query" => array(
                                            "nested" => array(
                                                "path" => "value",
                                                "query" => array(
                                                    "query_string" => array(
                                                        "default_field" => $this->nameOfField($collect[$i]),
                                                        "query" => Util\Util::slugify($q[$i])
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
                                            'query' => Util\Util::slugify($q[$i])
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
                                                        "query" => Util\Util::slugify($q[$i])
                                                    )
                                                )
                                            )
                                        )
                                    );
                                } else {
                                    $filter['bool']['should'][] = array(
                                        "query" => array(
                                            "nested" => array(
                                                "path" => "value",
                                                "query" => array(
                                                    "query_string" => array(
                                                        "default_field" => $this->nameOfField($collect[$i]),
                                                        "query" => Util\Util::slugify($q[$i])
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
                                            'query' => Util\Util::slugify($q[$i])
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
                                                        "query" => Util\Util::slugify($q[$i])
                                                    )
                                                )
                                            )
                                        )
                                    );
                                } else {

                                    $filter['bool']['must_not'][] = array(
                                        "query" => array(
                                            "nested" => array(
                                                "path" => "value",
                                                "query" => array(
                                                    "query_string" => array(
                                                        "default_field" => $this->nameOfField($collect[$i]),
                                                        "query" => Util\Util::slugify($q[$i])
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
                $filterKeyword = $em->getRepository('JariffAdminBundle:FilterKeywords')->findAll();

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
                            "value.product_description" => array("number_of_fragments" => 0, "pre_tags" => array("<strong>"), "post_tags" => array("</strong>")),
                        )
                    )
                );

                $toPacket = 'fos_elastica.finder.jariff.all_data';

                $finder = $this->getContainer()->get($toPacket);
                $query = new \Elastica\Query();
                $facet = new \Elastica\Facet\Terms('tags');
                $facetCategory = new \Elastica\Facet\Terms('category');


                $facetCategory->setField("value.company_as");
                $facetCategory->setSize(2);
                $facetCategory->setOrder("reverse_term");
                $facetCategory->setNested("value");

                $facet->setField("value.slug_country");
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

                        $cell->setCellValue('A' . $loop, $row->getValue()->getConsigneeName());
                        $cell->setCellValue('B' . $loop, $row->getValue()->getConsigneeAddress());
                        $cell->setCellValue('C' . $loop, $row->getValue()->getTotalShipment());
                        $cell->setCellValue('D' . $loop, $row->getValue()->getShipperName());
                        $cell->setCellValue('E' . $loop, $extention->twolettercodeslug($row->getValue()->getSlugCountry())['country']);
                        $cell->setCellValue('F' . $loop, $row->getValue()->getProductDescription());
                        $cell->setCellValue('G' . $loop, $row->getValue()->getTotalWeight());
                        $cell->setCellValue('H' . $loop, $row->getValue()->getLoadingPort());
                        $cell->setCellValue('I' . $loop, $row->getValue()->getUnloadingPort());
                        $cell->setCellValue('J' . $loop, $row->getValue()->getMarksNumbers());

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

                        $cell->setCellValue('A' . $loop, $row->getValue()->getShipperName());
                        $cell->setCellValue('B' . $loop, $row->getValue()->getShipperAddress());
                        $cell->setCellValue('C' . $loop, $row->getValue()->getTotalShipment());
                        $cell->setCellValue('D' . $loop, $row->getValue()->getConsigneeName());
                        $cell->setCellValue('E' . $loop, $extention->twolettercodeslug($row->getValue()->getSlugCountry())['country']);
                        $cell->setCellValue('F' . $loop, $row->getValue()->getProductDescription());
                        $cell->setCellValue('G' . $loop, $row->getValue()->getTotalWeight());
                        $cell->setCellValue('H' . $loop, $row->getValue()->getLoadingPort());
                        $cell->setCellValue('I' . $loop, $row->getValue()->getUnloadingPort());
                        $cell->setCellValue('J' . $loop, $row->getValue()->getMarksNumbers());

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
                        "Weight (Kg)" => "E1",
                        "Marks & Numbers" => "F1"
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

                            $str = $row->getResult()->getHit()["highlight"]['value.product_description'][0];

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
                            $extention = new StringExtension();

                            $cell->setCellValue('A' . $loop, $objRichText);;
                            $cell->setCellValue('B' . $loop, $extention->convert_date($row->getTransformed()->getValue()->getActualArrivalDate()));
                            $cell->setCellValue('C' . $loop, $row->getTransformed()->getValue()->getShipperName());
                            $cell->setCellValue('D' . $loop, $row->getTransformed()->getValue()->getConsigneeName());
                            $cell->setCellValue('E' . $loop, $row->getTransformed()->getValue()->getTotalWeight());
                            $cell->setCellValue('F' . $loop, $row->getTransformed()->getValue()->getMarksNumbers());

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

//            $objDrawing = new \PHPExcel_Worksheet_Drawing();
//
//
//            $objDrawing->setPath('./path_to/image.png');
//            $objDrawing->setCoordinates('B1');
//
//            $objDrawing->setOffsetX(1);
//            $objDrawing->setOffsetY(5);

            $phpExcelObject->setActiveSheetIndex(0);

            // create the writer
            $writer = $this->getContainer()->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');

            $writer->save(realpath(__DIR__ . '/../../../../') . "/web/convert/" . $exportDownloadQueque->getFileName() . '.' . $exportDownloadQueque->getFormat());

            // echo $result;

//            if ($exportDownloadQueque->getIsAlert()) {
//                $mailer = $this->getContainer()->get('mailer');
//                $transport = $mailer->getTransport();
//
//                $mail = \Swift_Message::newInstance();
//
//                $mail
//                    ->setSubject('Your export in Strategy has been successful')
//                    ->setFrom(array('noreply@stradetegy.com' => 'Stradetegy Export'))
//                    ->setTo(array($exportDownloadQueque->getEmail() => $exportDownloadQueque->getEmail()))
//                    ->addPart('Your export in Strategy has been successful, please check in menu member, stats, and choice download', 'text/plain')
//                    ->setBody('Your export in Strategy has been successful, please check in menu member, stats, and choice download', 'text/html');
//
//
//                $mailer->send($mail);
//            }

            $exportDownloadQueque->setIsExecute(1);
            $em->persist($exportDownloadQueque);
            $em->flush();

            echo "finished\n";


            flock($fp, LOCK_UN);
        } else {
            $output->writeln('Program pengantri untuk aktifasi sedang berjalan, terasa aneh ? coba cek log nya.');
        }
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



