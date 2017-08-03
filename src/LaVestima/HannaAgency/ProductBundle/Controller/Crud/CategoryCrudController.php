<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class CategoryCrudController extends CrudController implements CategoryCrudControllerInterface
{
    protected $entityClass = 'LaVestima\\HannaAgency\\ProductBundle\\Entity\\Categories';
}