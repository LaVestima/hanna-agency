<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class OrderCrudController extends CrudController {
	protected $entityClass = 'LaVestima\\HannaAgency\\OrderBundle\\Entity\\Orders';
}