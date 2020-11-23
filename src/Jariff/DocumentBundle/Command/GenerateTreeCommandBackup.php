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
            ->setDescription('Mengeksekusi tree suppliers')
            ->setHelp(<<<EOF
<info>jariff:cron:map-reduce-suppliers</info>
Command digunakan untuk generate tree yang digunakan untuk trade-map

Syntax :
php app/console jariff:cron:generate-tree-trade-map-backup
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
                foreach ($row->getValue()->getConsigneeName() as $consignee) {
                    if (Util\Util::slugify($consignee) != $row->getValue()->getSlug()) {
                        $items[] = array(
                            'id' => Util\Util::slugify($consignee),
                            'name' => $consignee,
                            'data' => array(
                                'band' => $row->getValue()->getShipperName(),
                                'relation' => 'Buyer of band'
                            )

                        );
                    }

                }
            }

            echo "items collected\n";
            // echo json_encode($items);

            foreach ($items as &$item) {
                $parent = $item['data'];
                $children[Util\Util::slugify($parent['band'])][] = & $item;
            }

            unset($item);

            foreach ($items as &$item) {

                if (isset($children[$item['id']])) {

                    $item['children'] = $children[$item['id']];

                }

            }
            unset($item);
            $dataMap = $dm->getRepository("JariffDocumentBundle:TradeMap")->findAll();

            foreach ($dataMap as $dC) {
                $dm->getManager()->remove($dC);
            }
            echo "strarting recording\n";

            $tradeMap = new TradeMap();
            foreach ($children as $key => $child) {

                $test = array(
                    'id' => $key,
                    'name' => $key,
                    'children' => is_array($child) ? $child : array()
                );
                // echo $test['id'] . "\n";
                $tradeMap = new TradeMap();
                $tree = serialize($test);


                $tradeMap->setTree($tree);
                $tradeMap->setParent($key);
                $dm->getManager()->persist($tradeMap);
            }

            $dm->getManager()->flush();

            echo "finished\n";

            flock($fp, LOCK_UN);
        } else {
            $output->writeln('Program pengantri untuk aktifasi sedang berjalan, terasa aneh ? coba cek log nya.');
        }
    }

    function json_encode_ex_as_array(array $v)
    {
        for ($i = 0; $i < count($v); $i++) {
            if (!isset($v[$i])) {
                return false;
            }
        }
        return true;
    }

    function json_encode_ex($v)
    {
        if (is_object($v)) {
            $type = is_a($v, 'EncoderData') ? JSON_ENCODE_EX_EncoderDataObject : JSON_ENCODE_EX_OBJECT;
        } else if (is_array($v)) {
            $type = $this->json_encode_ex_as_array($v) ? JSON_ENCODE_EX_ARRAY : JSON_ENCODE_EX_OBJECT;
        } else {
            $type = JSON_ENCODE_EX_SCALAR;
        }

        switch ($type) {
            case JSON_ENCODE_EX_ARRAY: // array [...]
                foreach ($v as $value) {
                    $rv[] = $this->json_encode_ex($value);
                }
                $rv = '[' . join(',', $rv) . ']';
                break;
            case JSON_ENCODE_EX_OBJECT: // object { .... }
                $rv = array();
                foreach ($v as $key => $value) {
                    $rv[] = json_encode((string)$key) . ':' . $this->json_encode_ex($value);
                }
                $rv = '{' . join(',', $rv) . '}';
                break;
            case JSON_ENCODE_EX_EncoderDataObject:
                $rv = $this->json_encode_ex($v->getData());
                break;
            default:
                $rv = json_encode($v);
        }
        return $rv;
    }
}



