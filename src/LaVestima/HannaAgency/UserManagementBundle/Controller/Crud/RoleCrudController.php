<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class RoleCrudController extends CrudController implements RoleCrudControllerInterface {
	protected $entityClass = 'LaVestima\\HannaAgency\\UserManagementBundle\\Entity\\Roles';
}
