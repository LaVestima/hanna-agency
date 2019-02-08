<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 21.01.17
 * Time: 16:03
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AdminControllerTest extends WebTestCase {
	private $client = null;

	public function setUp() {
		$this->client = static::createClient();
	}

	private function logIn() {
		$session = $this->client->getContainer()->get('session');
		$firewall = 'secured_area';

		$token = new UsernamePasswordToken('admin', 'lave_toor', $firewall, array('ROLE_ADMIN'));
		$session->set('_security_'.$firewall, serialize($token));
		$session->save();

		$cookie = new Cookie($session->getName(), $session->getId());
		$this->client->getCookieJar()->set($cookie);
	}

	public function testCustomerList() {
		$crawler = $this->client->request('GET', '/admin/customer_list');
//		$this->assertTrue($this->client->getResponse()->isSuccessful());
		$this->assertEquals(302, $this->client->getResponse()->getStatusCode());

		$this->logIn();

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
	}

	public function testPanel() {
		$crawler = $this->client->request('GET', '/admin/panel');
		$this->assertEquals(302, $this->client->getResponse()->getStatusCode());
	}

	public function testAddCustomer() {
		$crawler = $this->client->request('GET', '/admin/add_customer');
		$this->assertEquals(302, $this->client->getResponse()->getStatusCode());
	}
}