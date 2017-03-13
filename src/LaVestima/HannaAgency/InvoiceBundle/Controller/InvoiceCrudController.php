<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 13.03.17
 * Time: 19:45
 */

namespace LaVestima\HannaAgency\InvoiceBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class InvoiceCrudController extends CrudController {
	protected $entityClass = 'LaVestima\\HannaAgency\\InvoiceBundle\\Entity\\Invoices';
	
	public function readInvoice($id) {
		return $this->readEntity($id);
	}
	
	public function readAllInvoices() {
		return $this->readAllEntities();
	}
}