<?php

namespace LaVestima\HannaAgency\HomepageBundle\Tests\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomepageControllerTest extends BaseWebTestCase
{
    public function testHomepageActionAnonymous()
    {
        $this->client->request('GET', '/');

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testContactActionAnonymous()
    {
        $this->client->request('GET', '/contact');

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }
}