<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\DocumentBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Jariff\MemberBundle\Entity\CommissionDetail;
use Jariff\ProjectBundle\Util;
use Jariff\AdminBundle\Entity\DailyRank;
use Symfony\Component\Console\Input\InputArgument;
use PDO;

class TrademangoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('jariff:cron:trademango')
        ->setDescription('Perhitungan trademango. run setiap jam 19.')
        ->addArgument(
                'slug',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fp = fopen(getcwd() . '/jariff-cron-trademango.lock', 'w');

        if (flock($fp, LOCK_EX | LOCK_NB)) {
            $slug = $input->getArgument('slug');
            $collection = $db->suppliers;
            // $data = $dm->getRepository("JariffDocumentBundle:Suppliers")->findOneBy(array('value.slug' => $slug));
            // var_dump($data);

            $company = "1010 printing international ltd";
            echo "\n".$company."\n";

            // $competitors = $em->getRepository('JariffAdminBundle:Competitor')->findAll();

            // foreach ($competitors as $competitor) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_VERBOSE, false);
            curl_setopt($curl, CURLOPT_URL, 'http://www.trademango.com/search?q='.str_replace(" ", "+", $company));

            $exec = curl_exec($curl);
            sleep(1);

                //inisialisasi dom untuk parsing html nya
            $dom = new \DOMDocument();

            @$dom->loadHTML($exec);
            $xpath = new \DOMXPath($dom);

            $texts = array();
            $hrefs = array();

            $nodes = $xpath->query('/html/body/div/div[3]/ul/li/a');
            foreach($nodes as $node) {
                // $text = ($nodes->length > 0) ? $node->getAttribute('title') : '';
                $href = ($nodes->length > 0) ? $node->getAttribute('href') : '';
                $split = explode("/", $href);
                $text = $split[3];
                $texts[] = $text;
                $hrefs[] = $href;
                echo $text."\nhttp://www.trademango.com".$href."\n\n";
            }

            $shortest = -1;
            $shortestIdx = 0;
            $company = str_replace(" ", "-", $company);
            echo "\n".$company."\n";

            $i = 0;

            foreach ($texts as $text) {
                $lev = levenshtein($company, $text);
                if ($lev == 0) {
                    $closest = $text;
                    $shortest = 0;
                    $shortestIdx = $i;
                    break;
                }

                if ($lev <= $shortest || $shortest < 0) {
                    $closest  = $text;
                    $shortest = $lev;
                    $shortestIdx = $i;
                }
                $i++;
            }

            $aqrob = $hrefs[$shortestIdx];
            echo "\nAQROB === http://www.trademango.com".$aqrob."\n";

            $url = 'http://www.trademango.com'.$aqrob;
            $url = str_replace(" ", '%20', $url);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_URL, $url);

            $exec = curl_exec($curl);
            sleep(1);
            $dom = new \DOMDocument();

            @$dom->loadHTML($exec);
            $xpath = new \DOMXPath($dom);

            $texts = array();
            $hrefs = array();

            $nodes = $xpath->query('/html/body/div/div[2]/div/div/div[2]/table/tbody/tr[2]/td[2]/a');
            $website = ($nodes->length > 0) ? $nodes->item(0)->textContent : "";
            echo "\nwebsite ".$website."\n";

            $nodes = $xpath->query('/html/body/div/div[2]/div/div/div[2]/table/tbody/tr[4]/td[2]');
            $phone = ($nodes->length > 0) ? $nodes->item(0)->textContent : "";
            echo "\nphone ".$phone."\n";


            flock($fp, LOCK_UN);
        } else {
            $output->writeln('ok');
        }
    }

}