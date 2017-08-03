<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class ProductCrudController extends CrudController implements ProductCrudControllerInterface
{
	protected $entityClass = 'LaVestima\\HannaAgency\\ProductBundle\\Entity\\Products';
}