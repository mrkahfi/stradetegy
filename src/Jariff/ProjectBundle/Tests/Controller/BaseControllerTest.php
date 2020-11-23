<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\ProjectBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseControllerTest extends WebTestCase
{
    private $client;
    private $em;
    private $router;
    private $adminLoggedIn  = false;
    private $memberLoggedIn = false;

    public function setUp()
    {
        $this->client   = static::createClient(array(), array('HTTP_HOST' => 'strdtgy.dev'));
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->router = static::$kernel->getContainer()
            ->get('router');
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }

    public function routerGetMember($route_name, $options = array(), $statusCode = 200)
    {
        if ($this->loginMember()) {
            return $this->routerGet($route_name, $options, $statusCode);
        }
    }

    public function routerGetAdmin($route_name, $options = array(), $statusCode = 200)
    {
        if ($this->loginAdmin()) {
            return $this->routerGet($route_name, $options, $statusCode);
        }
    }

    public function routerGet($route_name, $options = array(), $statusCode = 200)
    {
        $url     = $this->router->generate($route_name, $options);
        $crawler = $this->client->request('GET', $url);
        $this->assertEquals($statusCode, $this->client->getResponse()->getStatusCode(), "Error get ($route_name) $url");

        //bila kita ingin mengetest request dengan redirect, maka status kode nya 
        //harus sama dengan 302, yg berarti client yg di return adalah client setelah
        //request url redirect yg baru, kalau tidak nanti dapatnya client dengan 
        //request sebelum redirect, sehingga xpath filter nya akan salah.
        if($statusCode == 302){
            return $this->client->followRedirect();
        }

        return $crawler;
    }

    public function loginAdmin()
    {
        if ($this->adminLoggedIn) {
            return true;
        }
        // Create a new entry in the database
        $crawler = $this->client->request('GET', '/admin-login');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Tidak bisa akses halaman /admin-login");

        $form = $crawler->selectButton('Log in')->form(array(
            '_username' => 'ardianys@gmail.com',
            '_password' => 'asdfasdf',
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "admin tidak bisa login");

        if ($this->client->getResponse()->getStatusCode() === 200) {
            $this->adminLoggedIn = true;
            return true;
        } else {
            $this->adminLoggedIn = false;
            return false;
        }
    }

    public function loginMember()
    {
        if ($this->memberLoggedIn) {
            return true;
        }
        // Create a new entry in the database
        $crawler = $this->client->request('GET', '/login');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Tidak bisa akses halaman /login");

        $form = $crawler->selectButton('login')->form(array(
            '_username' => 'ilmoe@yahoo.com',
            '_password' => 'asdfasdf',
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "member tidak bisa login");

        if ($this->client->getResponse()->getStatusCode() === 200) {
            $this->memberLoggedIn = true;
            return true;
        } else {
            $this->memberLoggedIn = false;
            return false;
        }
    }

    public function repo($entity)
    {
        return $this->em->getRepository($entity);
    }

    public function em()
    {
        return $this->em;
    }

    public function getClient(){
        return $this->client;
    }

    public function getRouter(){
        return $this->router;
    }

    public function checkRouter($name){
        $router = $this->router->getRouteCollection()->get($name);

        if(!$router)
            return false;

        return true;
	}

    public function getKernel(){
        return static::$kernel;
    }
}