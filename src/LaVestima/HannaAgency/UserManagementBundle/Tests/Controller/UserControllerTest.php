<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Tests\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends BaseWebTestCase
{
    private $listActionPath = '/user/list';
    private $showActionPath = '/user/show';

    public function testListActionAnonymous()
    {
        $this->client->request('GET', $this->listActionPath);

        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testListActionGuest()
    {
        $this->logInGuest();

        $this->client->request('GET', $this->listActionPath);

        $this->assertSame(
            Response::HTTP_FORBIDDEN,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testListActionCustomer()
    {
        $this->logInCustomer();

        $this->client->request('GET', $this->listActionPath);

        $this->assertSame(
            Response::HTTP_FORBIDDEN,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testListActionAdmin()
    {
        $this->logInAdmin();

        $this->client->request('GET', $this->listActionPath);

        $this->assertSame(
            Response::HTTP_FORBIDDEN,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testListActionSuperAdmin()
    {
        $this->logInSuperAdmin();

        $this->client->request('GET', $this->listActionPath);

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }
}
