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
use Jariff\DocumentBundle\Document\TradeMap;

define('JSON_ENCODE_EX_SCALAR', 0);
define('JSON_ENCODE_EX_ARRAY', 1);
define('JSON_ENCODE_EX_OBJECT', 2);
define('JSON_ENCODE_EX_EncoderDataObject', 3);

class GenerateRivalMarker extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('jariff:cron:generate-rival-marker')
            ->setDescription('Mengeksekusi cache rival marker')
            ->setHelp(<<<EOF
<info>jariff:cron:map-reduce-buyers</info>
Command digunakan untuk generate tree yang digunakan untuk trade-map

Syntax :
php app/console jariff:cron:generate-rival-marker
EOF
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //semua cron file dengan command seperti ini harus ada file lock nya,
        //untuk menghindari duplikasi proses
        //nama file nya 
        // 'lock-'.str_replace(':', '-', 'nama command di definisi fungsi configure di atas').'.txt'
        $fp = fopen(getcwd() . '/lock/jariff-generate-rival-marker.lock', 'w');

        if (flock($fp, LOCK_EX | LOCK_NB)) {

            echo "mulai\n";

            $data = $dm->getRepository("JariffDocumentBundle:Buyers")->findAll();

            $children = array();

            echo "fetched\n";

            $items = array();
            foreach ($data as $row) {
                foreach ($row->getValue()->getShipperName() as $shipper) {
                    $items[] = array(
                        'id' => Util\Util::slugify($shipper),
                        'name' => $shipper,
                        'data' => array(
                            'buyer' => $row->getValue()->getConsigneeName(),
                            'address' => $row->getValue()->getConsigneeAddress(),
                            'type' => '1'
                        )
                    );
                }
            }

            echo "items collected\n";


            $server = $this->getContainer()->getParameter('mongo_server');
            $db = $this->getContainer()->getParameter('mongo_database_name');
            $mongo = new \MongoClient($server);

            $db = $mongo->selectDB($db);
            $collection = $db->trade_map;
            $collection->drop();

            echo "strarting recording\n";
    

            $trees = array();
            foreach ($children as $key => $child) {
                $buyerName = $child[0]['data']['buyer'];
                // $address = $child[0]['data']['address'];
                // $i++;
                $test = array(
                    'id' => $key,
                    'name' => $buyerName,
                    'data' => $child[0]['data'],
                    'children' => is_array($child) ? $child : array()
                );
                // var_dump($test);
                $tree = serialize($test);
                $trees[] = array('parent' => $key, 'tree' => $tree);
            }

            $size = strlen(serialize($trees));
            echo "\nsize of trees : ".$size."\n";

            $numOfArrays = ceil($size/40000000);

            echo "\nnumOfArrays : ".$numOfArrays."\n";

            $arrayOfTrees = array_chunk($trees, floor(count($trees)/$numOfArrays));

            echo "\nsize of arrayOfTrees : ".count($arrayOfTrees)."\n";
            
            for ($i = 0; $i < count($arrayOfTrees); $i++) {
                // echo "\n".strlen(serialize($arrayOfTrees[$i]))."\n";
                $collection->batchInsert($arrayOfTrees[$i]);
            }

            echo "finished\n";


            flock($fp, LOCK_UN);
        } else {
            $output->writeln('Program pengantri untuk aktifasi sedang berjalan, terasa aneh ? coba cek log nya.');
        }
    }
}



