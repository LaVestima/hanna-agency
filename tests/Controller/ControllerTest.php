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
        $client = static::createClient([], $this->getCredentialsForRole($role));
        $client->request('GET', $url);

//        if (!$client->getResponse()->isSuccessful()) {
//            $block = $crawler->filter('div.text_exception > h1');
//            if ($block->count()) {
//                $error = $block->text();
//                var_dump($error);
//            }
//        }

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider provideUrls403
     * @param $url
     * @param $role
     */
    public function testPage403($url, $role = 'ROLE_ANON')
    {
        $client = self::createClient([], $this->getCredentialsForRole($role));
        $client->request('GET', $url);

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider provideUrls404
     * @param $url
     * @param $role
     */
    public function testPage404($url, $role = 'ROLE_ANON')
    {
        $client = self::createClient([], $this->getCredentialsForRole($role));
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
            [''],
            ['/'],
            ['/login'],
            ['/contact'],
            ['/cart'],
            ['/product/show/Cool-Shoe-39', 'ROLE_USER'],
            ['/product/show/Cool-Shoe-39', 'ROLE_ADMIN'],
            ['/product/show/Cool-Shoe-39', 'ROLE_PRODUCER'],
            ['/product/edit/Cool-Shoe-39', 'ROLE_PRODUCER'],
            ['/store/dashboard', 'ROLE_PRODUCER'],
            ['/orders', 'ROLE_USER'],
            ['/settings', 'ROLE_USER'],
            ['/conversations', 'ROLE_USER'],
            ['/inventory', 'ROLE_PRODUCER'],
            ['/search'], // TODO: ???
            ['/search?query=duck'],
//            ['/search?category=.....'], // TODO: finish
            // TODO: orders,
            // TODO: ...
        ];
    }

    public function provideUrls403()
    {
        return [
            ['/product/edit/Cool-Shoe-39', 'ROLE_USER'],
            ['/product/activate/Cool-Shoe-39', 'ROLE_USER'],
            ['/product/deactivate/Cool-Shoe-39', 'ROLE_USER'],
        ];
    }

    public function provideUrls404()
    {
        return [
            ['/asdf'],
            ['/product'],
            ['/product/show/a', 'ROLE_USER'],
        ];
    }

    public function provideUrls302()
    {
        return [
            ['/logout'],
            ['/store/dashboard'],
            ['/orders'],
            ['/settings'],
            ['/conversations'],
            ['/inventory'],
            ['/product/activate/Cool-Shoe-39', 'ROLE_PRODUCER'],
            ['/product/deactivate/Cool-Shoe-39', 'ROLE_PRODUCER'],
//            ['/product/list'],
//            ['/product/show/Cool-Shoe-39'],
//            ['/product/show/Cool-Shoe-390'],
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
            case 'ROLE_PRODUCER':
                $credentials = [
                    'PHP_AUTH_USER' => 'producer',
                    'PHP_AUTH_PW' => 'producer'
                ];

                break;
        }

        return $credentials;
    }
}
