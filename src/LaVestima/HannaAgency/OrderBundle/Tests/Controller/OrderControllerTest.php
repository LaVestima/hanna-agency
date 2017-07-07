<?php

namespace LaVestima\HannaAgency\OrderBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class OrderControllerTest extends WebTestCase
{
    private $client;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->client = static::createClient();
        parent::__construct($name, $data, $dataName);
    }

//    public function setUp()
//    {
//
//    }

    public function testListAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/order/list');

        $this->assertEquals(
            0,
            $crawler->filter('html:contains("Order List")')->count()
        );
    }

    public function testListActionCustomer()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'customer',
            'PHP_AUTH_PW'   => 'customer',
        ));

        $crawler = $client->request('GET', '/order/list');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Order List")')->count()
        );
    }

    public function testListActionAdmin()
    {
        $this->logInAdmin();

        $crawler = $this->client->request('GET', '/order/list');

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
//        $this->assertNotSame(
//            Response::HTTP_FORBIDDEN,
//            $this->client->getResponse()->getStatusCode()
//        );

//        $this->assertGreaterThan(
//            0,
//            $crawler->filter('html:contains("Order List")')->count()
//        );
    }

    public function testListActionGuest()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'guest',
            'PHP_AUTH_PW'   => 'guest',
        ));

        $crawler = $client->request('GET', '/order/list');

        $this->assertEquals(
            0,
            $crawler->filter('html:contains("Order List")')->count()
        );
    }

    private function logInAdmin()
    {
        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
    }

    private function logInUser()
    {
        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
    }
}