<?php

namespace LaVestima\HannaAgency\CustomerBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class CustomerCrudController extends CrudController {
	protected $entityClass = 'LaVestima\\HannaAgency\\CustomerBundle\\Entity\\Customers';
}