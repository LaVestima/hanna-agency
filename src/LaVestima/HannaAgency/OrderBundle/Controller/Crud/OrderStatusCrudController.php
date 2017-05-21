<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 26/04/17
 * Time: 18:00
 */

namespace LaVestima\HannaAgency\OrderBundle\Controller\Crud;


use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class OrderStatusCrudController extends CrudController {
    protected $entityClass = 'LaVestima\\HannaAgency\\OrderBundle\\Entity\\OrdersStatuses';
}