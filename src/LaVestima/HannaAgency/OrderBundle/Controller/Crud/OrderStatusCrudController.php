<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class OrderStatusCrudController extends CrudController implements OrderStatusCrudControllerInterface
{
    protected $entityClass = 'LaVestima\\HannaAgency\\OrderBundle\\Entity\\OrdersStatuses';
}