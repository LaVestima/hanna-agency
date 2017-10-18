<?php

namespace LaVestima\HannaAgency\InvoiceBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class InvoiceProductCrudController extends CrudController implements InvoiceProductCrudControllerInterface
{
	protected $entityClass = 'LaVestima\\HannaAgency\\InvoiceBundle\\Entity\\InvoicesProducts';
}