<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls200
     * @param $url
     * @param $role
     */
    public function testPage200($url, $role = 'ROLE_ANON')
    {
        $credentials = $this->getCredentialsForRole($role);

        $client = self::createClient([], $credentials);
        $client->request('GET', $url);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider provideUrls404
     * @param $url
     * @param $role
     */
    public function testPage404($url, $role = 'ROLE_ANON')
    {
        $credentials = $this->getCredentialsForRole($role);

        $client = self::createClient([], $credentials);
        $client->request('GET', $url);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider provideUrls302
     * @param $url
     * @param $role
     */
    public function testPage302($url, $role = 'ROLE_ANON')
    {
        $credentials = $this->getCredentialsForRole($role);

        $client = self::createClient([], $credentials);
        $client->request('GET', $url);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function provideUrls200()
    {
        return [
            ['/'],
            ['/contact'],
            ['/producer/list'],
            ['/product/list', 'ROLE_USER'],
            ['/product/show/Cool-Shoe-39', 'ROLE_USER'],
            ['/order/list'],
            ['/user/list', 'ROLE_ADMIN']
            // ...
        ];
    }

    public function provideUrls404()
    {
        return [
            ['/asdf'],
            ['/product'],
            ['/product/show/Cool-Shoe-390', 'ROLE_USER'],
        ];
    }

    public function provideUrls302()
    {
        return [
            ['/product/list'],
            ['/product/show/Cool-Shoe-39'],
            ['/product/show/Cool-Shoe-390'],
        ];
    }

    private function getCredentialsForRole(string $role)
    {
        $credentials = [];

        switch ($role) {
            case 'ROLE_USER':
                $credentials = [
                    'PHP_AUTH_USER' => 'user',
                    'PHP_AUTH_PW' => 'user'
                ];

                break;
            case 'ROLE_ADMIN':
                $credentials = [
                    'PHP_AUTH_USER' => 'admin',
                    'PHP_AUTH_PW' => 'admin'
                ];

                break;
        }

        return $credentials;
    }
}
