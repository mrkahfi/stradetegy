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

class ExportShipmentsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
        ->setName('jariff:cron:export-shipments')
        ->setDescription('Mengeksekusi file export shipments')
        ->setHelp('<<<EOF
            <info>jariff:cron:export</info>
            Command digunakan untuk menyimpan data pencarian

            Syntax :
            php app/console jariff:cron:export-shipments
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

            while(true){
                $em = $this->getContainer()->get('doctrine.orm.entity_manager');

                echo "mulai\n";
                $exportDownloadQueque = $em->getRepository('JariffMemberBundle:ExportTools')->findOneBy(array('process' => 1));

                if (!$exportDownloadQueque){
                    echo "kosong";
                    sleep(5);
                    continue;
                }
                else{
                    echo exec('php ~/public_html/stradetegy/app/console jariff:cron:export-shipments-'.$exportDownloadQueque->getCollection());
                }

                echo "finished\n";
                
            }

            flock($fp, LOCK_UN);
        } else {
            $output->writeln('Program pengantri untuk aktifasi sedang berjalan, terasa aneh ? coba cek log nya.');
        }
    }
}
