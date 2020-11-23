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

class GenerateTreeCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('jariff:cron:generate-tree-trade-map')
            ->setDescription('Mengeksekusi tree buyyer')
            ->setHelp(<<<EOF
<info>jariff:cron:map-reduce-buyers</info>
Command digunakan untuk generate tree yang digunakan untuk trade-map

Syntax :
php app/console jariff:cron:generate-tree-trade-map
EOF
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //semua cron file dengan command seperti ini harus ada file lock nya,
        //untuk menghindari duplikasi proses
        //nama file nya 
        // 'lock-'.str_replace(':', '-', 'nama command di definisi fungsi configure di atas').'.txt'
        $fp = fopen(getcwd() . '/lock/jariff-generate-tree-trade-map.lock', 'w');

        if (flock($fp, LOCK_EX | LOCK_NB)) {

            echo "mulai\n";

            $children = array();

            echo "fetched\n";

            $items = array();
            foreach ($data as $row) {
                foreach ($row->getValue()->getShipper() as $shipper) {
                    $shipperName = $shipper->getShipperName();
                    $containerCount = $row->getValue()->getContainerCount();
                    if ($containerCount == NULL) {
                        $containerCount = $row->getValue()->getContainerCount();
                    }
                    $shipperContainerCount = $shipper->getContainerCount();
                    if ($shipperContainerCount == null) {
                        $shipperContainerCount = 0;
                    }
                    $items[] = array(
                        'id' => Util\Util::slugify($shipperName),
                        'name' => $shipperName,
                        'data' => array(
                            'buyer' => $row->getValue()->getCompanyName(),
                            'address' => $row->getValue()->getCompanyAddress(),
                            'shipper_address' => $shipper->getCompanyJoinAddress(),
                            'country' => $shipper->getCountry(),
                            'containerCount' => $containerCount,
                            'shipperContainerCount' => $shipperContainerCount,
                            'type' => '2'
                        )
                    );
                }
            }

            echo "items collected\n".count($items)."\n";


            foreach ($items as &$item) {
                $parent = $item['data'];
                $slugified = Util\Util::slugify($parent['buyer']);
                $children[$slugified][] = &$item;
            }

            unset($item);

            foreach ($items as &$item) {
                if (isset($children[$item['id']])) {
                    $item['children'] = $children[$item['id']];
                }

            }
            unset($item);

            $server = $this->getContainer()->getParameter('mongo_server');
            $db = $this->getContainer()->getParameter('mongo_database_name');
            $mongo = new \MongoClient($server);

            $db = $mongo->selectDB($db);
            $collection = $db->trade_map;
            $collection->drop();

            echo "strarting recording\n";

            $i = 0;
            $trees = array();
            foreach ($children as $key => $child) {
                $buyerName = $child[0]['data']['buyer'];
                $test = array(
                    'id' => $key,
                    'name' => $buyerName,
                    'data' => $child[0]['data'],
                    'children' => is_array($child) ? $child : array()
                );
                $tree = serialize($test);
                $trees[] = array('parent' => $key, 'tree' => $tree);
                $i++;

                if ($i > 3 && $i < 7) {
                    var_dump($test);
                }
            }

            $size = strlen(serialize($trees));
            echo "\nsize of trees : ".$size."\n";

            $numOfArrays = ceil($size/40000000);

            echo "\nnumOfArrays : ".$numOfArrays."\n";
            echo "\n".floor(count($trees)/$numOfArrays)."\n";

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



