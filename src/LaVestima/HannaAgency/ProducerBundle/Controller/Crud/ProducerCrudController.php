<?php

namespace LaVestima\HannaAgency\ProducerBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class ProducerCrudController extends CrudController implements ProducerCrudControllerInterface
{
    protected $entityClass = 'LaVestima\\HannaAgency\\ProducerBundle\\Entity\\Producers';
}