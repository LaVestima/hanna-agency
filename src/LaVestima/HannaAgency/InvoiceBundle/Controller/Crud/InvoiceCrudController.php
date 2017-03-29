<?php

namespace LaVestima\HannaAgency\InvoiceBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class InvoiceCrudController extends CrudController {
	protected $entityClass = 'LaVestima\\HannaAgency\\InvoiceBundle\\Entity\\Invoices';
}