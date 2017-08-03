<?php

namespace LaVestima\HannaAgency\MoneyBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class CurrencyCrudController extends CrudController implements CurrencyCrudControllerInterface
{
    protected $entityClass = 'LaVestima\\HannaAgency\\MoneyBundle\\Entity\\Currencies';
}