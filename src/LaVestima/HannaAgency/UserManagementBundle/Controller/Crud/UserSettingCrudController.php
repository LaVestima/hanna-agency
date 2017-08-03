<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class UserSettingCrudController extends CrudController implements UserSettingCrudControllerInterface
{
    protected $entityClass = 'LaVestima\\HannaAgency\\UserManagementBundle\\Entity\\UsersSettings';
}