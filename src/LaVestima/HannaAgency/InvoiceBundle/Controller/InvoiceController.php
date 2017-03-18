<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 12.03.17
 * Time: 14:34
 */

namespace LaVestima\HannaAgency\InvoiceBundle\Controller;

use LaVestima\HannaAgency\InvoiceBundle\Controller\Crud\InvoiceCrudController;
use AppBundle\Entity\Customers;
use AppBundle\Entity\Invoices;
use AppBundle\Entity\Users;
use DateTime;

class InvoiceController extends InvoiceCrudController {
	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listAction() {
		$invoices = $this->readAllInvoices();
		return $this->render('InvoiceBundle:Invoice:list.html.twig', [
			'invoices' => $invoices
		]);
	}

	/**
	 * @param $invoiceId
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showAction($invoiceId) {
		if ($invoiceId === 0) {
			// TODO: create flash
			// TODO: redirect to list
		}
		$invoice = $this->readInvoice($invoiceId);
		return $this->render('InvoiceBundle:Invoice:show.html.twig', [
			'invoice' => $invoice
		]);
	}

	public function newAction() {
//		$this->createEntity(
//			(new Invoices())
//			->setName('testInvoice')
//			->setIdCustomers(new Customers())
//			->setIdUsers(new Users())
//			->setDateIssued(new DateTime('now'))
//		);

		return $this->render('InvoiceBundle:Invoice:new.html.twig');
	}

	public function deleteAction($invoiceId) {
		$this->deleteInvoice((int)$invoiceId);
	}
}