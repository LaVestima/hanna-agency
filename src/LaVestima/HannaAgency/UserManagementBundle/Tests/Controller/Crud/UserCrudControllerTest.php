<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Tests\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Tests\BaseWebTestCase;

class UserCrudControllerTest extends BaseWebTestCase
{
    public function testReadUserFromDatabase()
    {
        $user = $this->client->getContainer()->get('user_crud_controller')
            ->readOneEntityBy(['id' => 1]);

        $this->assertInstanceOf(
            'LaVestima\HannaAgency\UserManagementBundle\Entity\Users',
            $user
        );
    }
}