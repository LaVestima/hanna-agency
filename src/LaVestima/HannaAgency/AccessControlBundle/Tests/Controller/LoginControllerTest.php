<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Tests;

use LaVestima\HannaAgency\InfrastructureBundle\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginControllerTest extends BaseWebTestCase
{
    private $indexActionPath = '/login';

    public function testLoginActionAnonymous()
    {
        $crawler = $this->client->request('GET', $this->indexActionPath);

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        $this->assertEquals(
            1,
            $crawler->filter('.page-content > form')->count()
        );
    }

    public function testLoginActionAdmin()
    {
        $this->logInAdmin();

        $crawler = $this->client->request('GET', $this->indexActionPath);

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        $this->assertEquals(
            0,
            $crawler->filter('.page-content > form')->count()
        );
    }

    public function testLoginActionCustomer()
    {
        $this->logInCustomer();

        $crawler = $this->client->request('GET', $this->indexActionPath);

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        $this->assertEquals(
            0,
            $crawler->filter('.page-content > form')->count()
        );
    }

    // TODO: maybe USER

    public function testLoginActionGuest()
    {
        $this->logInGuest();

        $crawler = $this->client->request('GET', $this->indexActionPath);

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        $this->assertEquals(
            0,
            $crawler->filter('.page-content > form')->count()
        );
    }
}