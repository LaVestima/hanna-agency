<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 26.01.17
 * Time: 00:09
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller {
	/**
	 * @Route("/invoice_list", name="user_invoice_list")
	 */
	public function invoiceListAction() {
		$invoices = $this->getDoctrine()->getRepository('AppBundle:Invoices')
			->findAll();

		return $this->render('user/invoice_list.html.twig', array(
			'invoices' => $invoices
		));
	}

	/**
	 * @Route("/invoice/{id}", name="user_invoice")
	 */
	public function InvoiceAction($id) {
		$em = $this->getDoctrine()->getManager();
		$qb = $em->createQueryBuilder();
		$qb->select('i.name AS iName' ,
			'i.dateIssued',
			'ip.quantity',
			'ip.discount',
			'ip.priceFinal',
			'p.name AS pName'
		)
			->from('AppBundle:Invoices', 'i')
			->join('AppBundle:InvoicesProducts', 'ip', 'WITH', 'ip.idInvoices = i.id')
			->join('AppBundle:Products', 'p', 'WITH', 'p.id=ip.idProducts')
			->where('i.id = :id')
			->orderBy('i.dateIssued', 'DESC')
			->setParameter('id', $id)
		;
		$query = $qb->getQuery();

		$invoice = $query->getResult();

		if (!$invoice) {
			$this->addFlash('notice', 'No invoice found!');
			$this->addFlash('noticeType', 'negative');
			return $this->redirectToRoute('user_invoice_list');
		}

		return $this->render('user/invoice.html.twig', array(
			'invoice' => $invoice,
		));
	}
}