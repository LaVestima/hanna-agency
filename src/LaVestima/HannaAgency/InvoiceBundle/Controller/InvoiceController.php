<?php

namespace LaVestima\HannaAgency\InvoiceBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\InvoiceBundle\Controller\Crud\InvoiceCrudControllerInterface;
use LaVestima\HannaAgency\InvoiceBundle\Controller\Crud\InvoiceProductCrudControllerInterface;
use LaVestima\HannaAgency\InvoiceBundle\Entity\Invoices;
use LaVestima\HannaAgency\InvoiceBundle\Form\InvoiceType;
use Symfony\Component\HttpFoundation\Request;

class InvoiceController extends BaseController
{
    private $invoiceCrudController;
    private $invoiceProductCrudController;

    /**
     * InvoiceController constructor.
     *
     * @param InvoiceCrudControllerInterface $invoiceCrudController
     */
    public function __construct(
        InvoiceCrudControllerInterface $invoiceCrudController,
        InvoiceProductCrudControllerInterface $invoiceProductCrudController
    ) {
        $this->invoiceCrudController = $invoiceCrudController;
        $this->invoiceProductCrudController = $invoiceProductCrudController;
    }

    /**
     * Invoice List Action.
     *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listAction(Request $request)
    {
		$this->invoiceCrudController
            ->setAlias('i')
            ->readAllEntities()
            ->join('idCustomers', 'c')
            ->join('userCreated', 'u')
            ->orderBy('dateCreated', 'DESC');

        $this->setQuery($this->invoiceCrudController->getQuery());
        $this->setView('@Invoice/Invoice/list.html.twig');
        $this->setActionBar([
            [
                'label' => 'New Invoice',
                'path' => 'invoice_new',
                'icon' => 'fa-plus'
            ],
            [
                'label' => 'Deleted Invoices',
                'path' => 'invoice_deleted_list',
                'icon' => 'fa-close'
            ]
        ]);

        return parent::baseListAction($request);
	}

    /**
     * Invoice Deleted List Action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function deletedListAction(Request $request)
    {
	    $this->invoiceCrudController
            ->readAllDeletedEntities();

	    $this->setQuery($this->invoiceCrudController->getQuery());
	    $this->setView('@Invoice/Invoice/list.html.twig');
	    $this->setActionBar([
	        [
	            'label' => '< Invoice List',
                'path' => 'invoice_list'
            ]
        ]);

	    return parent::baseListAction($request);
    }

	/**
     * Invoice Show Action.
     *
	 * @param $pathSlug
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showAction(string $pathSlug)
    {
		$invoice = $this->invoiceCrudController
			->readOneEntityBy(['pathSlug' => $pathSlug])
            ->getResult();

		$invoicesProducts = $this->invoiceProductCrudController
			->readEntitiesBy(['idInvoices' => $invoice->getId()])
		    ->getResultAsArray();

		return $this->render('InvoiceBundle:Invoice:show.html.twig', [
			'invoice' => $invoice,
			'invoicesProducts' => $invoicesProducts,
		]);
	}

    /**
     * Invoice New Action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function newAction(Request $request)
    {
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

//    /**
//     * Invoice Delete Action.
//     *
//     * @param $invoiceId
//     */
//	public function deleteAction($invoiceId)
//    {
//		$this->invoiceCrudController
//            ->deleteEntity((int)$invoiceId);
//	}
}