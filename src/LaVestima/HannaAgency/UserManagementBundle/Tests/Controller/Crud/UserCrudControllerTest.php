<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Tests\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Tests\BaseWebTestCase;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;

class UserCrudControllerTest extends BaseWebTestCase
{
    public function testReadUserFromDatabase()
    {
        $user = $this->client->getContainer()->get('user_crud_controller')
            ->readOneEntityBy(['id' => 1])
            ->getResult();

        $this->assertInstanceOf(
            'LaVestima\HannaAgency\UserManagementBundle\Entity\Users',
            $user
        );
    }

    public function testReadUsersFromDatabase()
    {
        $users = $this->client->getContainer()->get('user_crud_controller')
            ->readEntitiesBy([
                'id' => ['0', '>']
            ])
            ->getResult();

        foreach ($users as $user) {
            if (!$user instanceof Users) {
                $this->assertTrue(false);

                return;
            }
        }

        $this->assertTrue(true);
    }
}