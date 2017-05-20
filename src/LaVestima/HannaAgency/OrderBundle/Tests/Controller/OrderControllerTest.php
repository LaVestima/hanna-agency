<?php

namespace LaVestima\HannaAgency\OrderBundle\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase {
    public function testListAction() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/order/list');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Order list")')->count()
        );
    }
}