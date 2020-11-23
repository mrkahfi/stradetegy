<?php


namespace Jariff\DocumentBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ChangePasswordCommand
 */
class TranslateCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this->setName('jariff-translate');
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $dm->getManager()
                ->createQueryBuilder('JariffDocumentBundle:ImportDocument')
                ->field('product_description_id')->equals(null)
                ->limit(1)
                ->getQuery()
                ->getSingleResult();


        $cookies  = dirname(__FILE__).'/cookies.txt';
        $curl     = curl_init();
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookies);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, false);
        $dom = new \DOMDocument();

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(
            array(
                'sl'   => 'en',
                'tl'   => 'id',
                'js'   => 'n',
                'prev' => '_t',
                'hl'   => 'en',
                'ie'   => 'UTF-8',
                'text' => $data->getProductDescription(),
                'file' => '',
            )));
        curl_setopt($curl, CURLOPT_URL, 'http://translate.google.com/');



        $exec = curl_exec ($curl);sleep(1);
        $fp = fopen(dirname(__FILE__).'/translate2.html', 'w');fwrite($fp, $exec);fclose($fp);
        @$dom->loadHTML($exec);
        $xpath = new DOMXPath($dom);
        $test  = $xpath->evaluate("//span[@id='result_box']");
        var_dump($test);
        echo $test->item(0)->textContent;
        $data->setProductDescription($test->item(0)->textContent);
        $dm->flush();
    }
}
