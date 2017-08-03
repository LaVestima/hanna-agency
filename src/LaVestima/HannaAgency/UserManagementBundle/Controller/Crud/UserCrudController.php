<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class UserCrudController extends CrudController implements UserCrudControllerInterface
{
	protected $entityClass = 'LaVestima\\HannaAgency\\UserManagementBundle\\Entity\\Users';
}