<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Tests;

use LaVestima\HannaAgency\InfrastructureBundle\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ForgotPasswordControllerTest extends BaseWebTestCase
{
    private $indexActionPath = '/forgot_password';

    public function testRegisterActionAnonymous()
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

    public function testRegisterActionGuest()
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