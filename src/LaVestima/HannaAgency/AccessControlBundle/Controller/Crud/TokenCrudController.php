<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class TokenCrudController extends CrudController {
	protected $entityClass = 'LaVestima\\HannaAgency\\AccessControlBundle\\Entity\\Tokens';
}