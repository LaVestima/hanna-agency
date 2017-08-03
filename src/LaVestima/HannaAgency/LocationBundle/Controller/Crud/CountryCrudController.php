<?php

namespace LaVestima\HannaAgency\LocationBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class CountryCrudController extends CrudController implements CountryCrudControllerInterface
{
    protected $entityClass = 'LaVestima\\HannaAgency\\LocationBundle\\Entity\\Countries';
}