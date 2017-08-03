<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseWebTestCase extends WebTestCase
{
    protected $client;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->client = static::createClient();

        parent::__construct($name, $data, $dataName);
    }

//    public function setUp()
//    {
//
//    }

    protected function logInAdmin()
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ]);
    }

    protected function logInCustomer()
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'customer',
            'PHP_AUTH_PW'   => 'customer',
        ]);
    }

//    protected function logInUser()
//    {
//        $this->client = static::createClient([], [
//            'PHP_AUTH_USER' => 'user',
//            'PHP_AUTH_PW'   => 'user',
//        ]);
//    }

    protected function logInGuest()
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'guest',
            'PHP_AUTH_PW'   => 'guest',
        ]);
    }
}