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
        $this->setView('@Invoice/Invoice/list.html.twig');
        $this->setActionBar([
//            [
//                'label' => 'New Invoice',
//                'path' => 'invoice_new',
//                'icon' => 'fa-plus'
//            ],
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
	    $this->setView('@Invoice/Invoice/deletedList.html.twig');
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

		$this->setView('InvoiceBundle:Invoice:show.html.twig');
		$this->setTemplateEntities([
            'invoice' => $invoice,
            'invoicesProducts' => $invoicesProducts,
        ]);
		$this->setActionBar([
		   [
		       'label' => 'List',
               'path' => 'invoice_list',
               'icon' => 'fa-chevron-left'
           ]
        ]);

		return parent::baseShowAction();
	}

    /**
     * Invoice New Action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function newAction(Request $request, string $orderPathSlug)
    {
        $invoice = new Invoices();

        if ($orderPathSlug) {
            $order = $this->get('order_crud_controller')
                ->readOneEntityBy([
                    'pathSlug' => $orderPathSlug
                ])->getResult();

            if ($order) {
                $invoice->setIdOrders($order);

                $ordersProducts = $this->get('order_product_crud_controller')
                    ->readEntitiesBy([
                        'idOrders' => $order->getId()
                    ])->getResult();
            } else {
                $this->addFlash('warning', 'No order found!');

                return $this->redirectToRoute('invoice_list');
            }
        }

		$form = $this->createForm(InvoiceType::class, $invoice);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$invoice = $form->getData();
			$invoice->setIdCustomers($order->getIdCustomers());
			$invoice->setDateIssued(
			    \DateTime::createFromFormat(
			        'd.m.Y H:i:s',
                    $form->get('dateIssued')->getData() . '00:00:00'
                )
            );

            $this->get('invoice_crud_controller')
                ->createEntity($invoice);

            $this->addFlash('success', 'Invoice issued!');

            return $this->redirectToRoute('order_list');
		}

        $this->setView('InvoiceBundle:Invoice:new.html.twig');
		$this->setForm($form);
		$this->setActionBar([
		    [
		        'label' => 'Back',
                'path' => 'order_list',
                'icon' => 'fa-chevron-left'
            ]
        ]);
		$this->setTemplateEntities([
		    'order' => $order ?? null,
            'ordersProducts' => $ordersProducts ?? null
        ]);

		return $this->baseNewAction();
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