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

define('ACCESS_KEY_ID', 'AKIAIG2BKUVNSGSZWJYA');
define('SECRET_ACCESS_KEY', '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');

class AlexaCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
        ->setName('jariff:cron:alexa')
        ->setDescription('Mengeksekusi alexa')
        ->setHelp('<<<EOF
            <info>jariff:cron:alexa</info>
            Command digunakan untuk untuk mengambil informasi rank situs dari alexa

            Syntax :
            php app/console jariff:cron:alexa
            EOF'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //semua cron file dengan command seperti ini harus ada file lock nya,
        //untuk menghindari duplikasi proses
        //nama file nya 
        // 'lock-'.str_replace(':', '-', 'nama command di definisi fungsi configure di atas').'.txt'
        $fp = fopen(getcwd() . '/lock/jariff-alexa.lock', 'w');

        if (flock($fp, LOCK_EX | LOCK_NB)) {

            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            echo "mulai\n";
            $entites = $em->getRepository('JariffAdminBundle:Competitor')->findAll();

            $site = 'importgenius.com';

            foreach ($entites as $entity) {
                $urlInfo = new UrlInfo(ACCESS_KEY_ID, SECRET_ACCESS_KEY, $entity->getWebsite(), $entity->getId());
                $dailyRank = $urlInfo->getUrlInfo();
                $em->persist($dailyRank);
                
            }
            $em->flush();
            // echo $result;
            
            echo "finished\n";


            flock($fp, LOCK_UN);
        } else {
            $output->writeln('Program pengantri untuk aktifasi sedang berjalan, terasa aneh ? coba cek log nya.');
        }
    }
}



