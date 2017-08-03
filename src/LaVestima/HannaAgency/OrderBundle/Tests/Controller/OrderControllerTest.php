<?php

namespace LaVestima\HannaAgency\OrderBundle\Tests\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class OrderControllerTest extends BaseWebTestCase
{
    private $testPathSlug = 'Umgewrmyefi6thiDJZMmz4LHuKrJDjaVbPZzfCgwLS6Fr5FKhs';

    private $listActionPath = '/order/list';
    private $showActionPath = '/order/show';

    public function testListActionAnonymous()
    {
        $this->client->request('GET', $this->listActionPath);

        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testListActionAdmin()
    {
        $this->logInAdmin();

        $this->client->request('GET', $this->listActionPath);

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testListActionCustomer()
    {
        $this->logInCustomer();

        $this->client->request('GET', $this->listActionPath);

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }

    // TODO: maybe USER

    public function testListActionGuest()
    {
        $this->logInGuest();

        $this->client->request('GET', $this->listActionPath);

        $this->assertSame(
            Response::HTTP_FORBIDDEN,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testShowActionAnonymous()
    {
        $this->client->request('GET', $this->showActionPath . '/' . $this->testPathSlug);

        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testShowActionAdmin()
    {
        $this->logInAdmin();

        $this->client->request('GET', $this->showActionPath . '/' . $this->testPathSlug);

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testShowActionCorrectCustomer()
    {
        $this->logInCustomer();

        $this->client->request('GET', $this->showActionPath . '/ZZgtZjORKCCKKTflMgLr7UpKpC2ErHGE2LqW6tMASMylmPKlBP');

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testShowActionIncorrectCustomer()
    {
        $this->logInCustomer();

        $this->client->request('GET', $this->showActionPath . '/' . $this->testPathSlug);

        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );
        $this->assertTrue(
            $this->client->getResponse()->isRedirect($this->listActionPath)
        );
    }

    // TODO: maybe USER

    public function testShowActionGuest()
    {
        $this->logInGuest();

        $this->client->request('GET', $this->showActionPath . '/' . $this->testPathSlug);

        $this->assertSame(
            Response::HTTP_FORBIDDEN,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testShowActionIncorrectPathSlug()
    {
        $this->logInAdmin();

        $this->client->request('GET', $this->showActionPath . '/wrongPathSlugxxxxxxxx');

        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );
        $this->assertTrue(
            $this->client->getResponse()->isRedirect($this->listActionPath)
        );
    }
}