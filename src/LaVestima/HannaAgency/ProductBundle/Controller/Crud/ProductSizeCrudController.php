<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class ProductSizeCrudController extends CrudController implements ProductSizeCrudControllerInterface
{
    protected $entityClass = 'LaVestima\\HannaAgency\\ProductBundle\\Entity\\ProductsSizes';
}