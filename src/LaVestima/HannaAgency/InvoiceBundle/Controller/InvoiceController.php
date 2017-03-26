<?php

namespace LaVestima\HannaAgency\InvoiceBundle\Controller;

use LaVestima\HannaAgency\InvoiceBundle\Entity\Invoices;
use LaVestima\HannaAgency\InvoiceBundle\Form\InvoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

	public function newAction(Request $request) {
		$invoice = new Invoices();
		$form = $this->createForm(InvoiceType::class, $invoice);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$invoice = $form->getData();


		}
		
		
//		$invoice->setDateIssued(new \DateTime('now')); // TODO: change??
//
//		$this->get('invoice_crud_controller')
//			->createEntity($invoice);
//

		return $this->render('InvoiceBundle:Invoice:new.html.twig', [
			'form' => $form->createView(),
		]);
	}

	public function deleteAction($invoiceId) {
		$this->get('invoice_crud_controller')->deleteEntity((int)$invoiceId);
	}
}