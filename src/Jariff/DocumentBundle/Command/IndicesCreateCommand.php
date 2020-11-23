<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\DocumentBundle\Command;

use Jariff\MemberBundle\Event\TimezoneListener;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Jariff\ProjectBundle\Util as Util;


class IndicesCreateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
        ->setName('jariff:cron:indices')
        ->setDescription('Membuat index di elasticsearch')
        ->setHelp('<<<EOF
            <info>jariff:cron:export</info>
            Command digunakan untuk membuat index di elasticsearch setiap hari

            Syntax :
            php app/console jariff:cron:indices
            EOF'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //semua cron file dengan command seperti ini harus ada file lock nya,
        //untuk menghindari duplikasi proses
        //nama file nya 
        // 'lock-'.str_replace(':', '-', 'nama command di definisi fungsi configure di atas').'.txt'
        $fp = fopen(getcwd() . '/lock/jariff-indices.lock', 'w');

        if (flock($fp, LOCK_EX | LOCK_NB)) {


            $client2 = new \Elastica\Client(array("host" => array($this->getContainer()->getParameter('es_server'))));

            $today = new \DateTime('now', new \DateTimeZone("US/Eastern"));
            $indexParams['index'] = 'logst-' . $today->format("Y");

            $myTypeMapping = array(
                '_source' => array(
                    'enabled' => true
                    ),
                'properties' => array(
                    'user_id' => array(
                        'type' => 'integer'
                        ),
                    'query' => array(
                        'type' => 'string'
                        ),
                    'date_search' => array(
                        'type' => 'date'
                        ),
                    'total_row' => array(
                        'type' => 'double'
                        ),
                    'set_pin' => array(
                        'type' => 'string'
                        ),
                    'priority' => array(
                        'type' => 'integer'
                        ),
                    )
                );
            $indexParams['body']['mappings']['shipments_log'] = $myTypeMapping;
            // var_dump($client2->getIndex($indexParams['index']));
            $searchable = new \Elastica\Index($client2, $indexParams['index']);

            if ($searchable->exists())
                die('sudah ada');


            die('sini');
            $client2->indices()->create($indexParams);

            $params['body'] = array(
                'actions' => array(
                    array(
                        'add' => array(
                            'index' => $indexParams['index'],
                            'alias' => 'logme'
                            )
                        )
                    )
                );
            $client2->indices()->updateAliases($params);

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



