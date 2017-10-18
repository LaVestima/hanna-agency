<?php

namespace LaVestima\HannaAgency\InvoiceBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class InvoiceCrudController extends CrudController implements InvoiceCrudControllerInterface
{
	protected $entityClass = 'LaVestima\\HannaAgency\\InvoiceBundle\\Entity\\Invoices';
}