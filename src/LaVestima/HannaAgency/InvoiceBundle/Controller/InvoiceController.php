<?php

namespace LaVestima\HannaAgency\InvoiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InvoiceController extends Controller {
	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listAction() {
		$invoices = $this->get('invoice_crud_controller')->readAllEntities();
		return $this->render('InvoiceBundle:Invoice:list.html.twig', [
			'invoices' => $invoices
		]);
	}

	/**
	 * @param $pathSlug
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showAction($pathSlug) {
		if ($pathSlug === '') {
			var_dump('No path slug!');
			// TODO: create flash
			// TODO: redirect to list
		}

		$invoice = $this->get('invoice_crud_controller')
			->readOneEntityBy(['pathSlug' => $pathSlug]);
		$invoicesProducts = $this->get('invoice_product_crud_controller')
			->readEntitiesBy(['idInvoices' => $invoice]);

		return $this->render('InvoiceBundle:Invoice:show.html.twig', [
			'invoice' => $invoice,
			'invoicesProducts' => $invoicesProducts,
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
		$this->get('invoice_crud_controller')->deleteEntity((int)$invoiceId);
	}
}