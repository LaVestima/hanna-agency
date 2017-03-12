<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 12.03.17
 * Time: 14:34
 */

namespace LaVestima\HannaAgency\InvoiceBundle\Controller;

use AppBundle\Entity\Customers;
use AppBundle\Entity\Invoices;
use AppBundle\Entity\Users;
use DateTime;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class InvoiceController extends CrudController {
	public function listAction() {
		return $this->render('@Invoice/Invoice/list.html.twig');
	}

	public function newAction() {
		$this->createEntity(
			(new Invoices())
			->setName('testInvoice')
			->setIdCustomers(new Customers())
			->setIdUsers(new Users())
			->setDateIssued(new DateTime('now'))
		);
		return $this->render('@Invoice/Invoice/new.html.twig');
	}
}