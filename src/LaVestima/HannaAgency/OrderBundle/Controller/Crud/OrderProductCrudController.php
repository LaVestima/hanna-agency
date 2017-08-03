<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class OrderProductCrudController extends CrudController implements OrderProductCrudControllerInterface
{
	protected $entityClass = 'LaVestima\\HannaAgency\\OrderBundle\\Entity\\OrdersProducts';
}