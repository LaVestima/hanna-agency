<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class ProductCrudController extends CrudController {
	protected $entityClass = 'LaVestima\\HannaAgency\\ProductBundle\\Entity\\Products';
}