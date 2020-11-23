<?php

namespace Jariff\MemberBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PendingControllerTest extends WebTestCase
{
    public function testCompleteScenario()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signup');
        $this->assertTrue($crawler->filter('html:contains("Everything Plan")')->count() > 0);


        // Fill in the form and submit it
        $form = $crawler->selectButton('Submit')->form(array(
            'pending[history]'        => '59', // 59, 99, 150
            'pending[search]'         => '10',  // 0, 10, 20, 35
            'pending[download]'       => '0',  // 0, 30, 40, 60, 70, 80
            'pending[bigPicture]'     => '30',  // 0, 30
            'pending[paymentTerm]'    => 'pif',// pif, mtm
            'pending[month]'          => '10',    // 20, 15, 10
            'pending[everythingPlan]' => "true",
            'pending[customPlan]'     => "false",
            'pending[email]'          => 'ardianys'.rand(1,10000).'@outlook.com',
            'pending[phone]'          => '4343434',
            'pending[salutation]'     => 'Mr.',      // Mr., Mrs., Ms.
            'pending[firstName]'      => 'ardian',
            'pending[lastName]'       => 'yuli',
            'pending[country]'        => 'Indonesia',
            'pending[companyName]'    => 'sTradetegy',
            'pending[password]'       => 'asdfasdf',
            'pending[payment]'        => 'bankwire',
            'pending[ccType]'         => '',
            'pending[number]'         => '',
            'pending[secureCode]'     => '',
            'pending[expired][month]' => '1',       // 1..12
            'pending[expired][day]'   => '1',
            'pending[expired][year]'  => strval(date('Y') + 1),
        ));

        
        $crawler = $client->submit($form);
        echo $crawler->html();

        // Check data in the show view Congratulation
        $this->assertTrue($crawler->filter('html:contains("Your registration data has been saved")')->count() > 0);
    }
}
