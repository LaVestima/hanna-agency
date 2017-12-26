<?php

namespace LaVestima\HannaAgency\CustomerBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use LaVestima\HannaAgency\CustomerBundle\Controller\Crud\CustomerCrudControllerInterface;
use LaVestima\HannaAgency\CustomerBundle\Entity\Customers;
use LaVestima\HannaAgency\CustomerBundle\Form\NewCustomerType;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\OrderBundle\Controller\Crud\OrderCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends BaseController
{
    private $customerCrudController;
    private $orderCrudController;

    /**
     * CustomerController constructor.
     *
     * @param CustomerCrudControllerInterface $customerCrudController
     * @param OrderCrudControllerInterface $orderCrudController
     */
    public function __construct(
        CustomerCrudControllerInterface $customerCrudController,
        OrderCrudControllerInterface $orderCrudController
    ) {
        $this->customerCrudController = $customerCrudController;
        $this->orderCrudController = $orderCrudController;
    }

    /**
     * Customer List Action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $this->setView('@Customer/Customer/list.html.twig');
        $this->setActionBar([
            [
                'label' => 'New Customer',
                'path' => 'customer_new',
                'role' => 'ROLE_ADMIN',
                'icon' => 'fa-plus'
            ],
            [
                'label' => 'Deleted Customers',
                'path' => 'customer_deleted_list',
                'role' => 'ROLE_ADMIN',
                'icon' => 'fa-close'
            ]
        ]);

        return parent::baseListAction($request);
    }

    /**
     * Customer Deleted List Action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deletedListAction(Request $request)
    {
        $this->setView('@Customer/Customer/deletedList.html.twig');
        $this->setActionBar([
            [
                'label' => 'Back',
                'path' => 'customer_list',
                'icon' => 'fa-chevron-left'
            ]
        ]);

        return parent::baseListAction($request);
    }

    /**
     * Customer Show Action.
     *
     * @param string $pathSlug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(string $pathSlug)
    {
        $customer = $this->get('customer_crud_controller')
            ->readOneEntityBy(['pathSlug' => $pathSlug])
            ->getResult();

        if (!$customer) {
            $this->addFlash('warning', 'No customer found!');

            return $this->redirectToRoute('customer_list');
        }

        $orders = $this->get('order_crud_controller')
            ->readEntitiesBy(['idCustomers' => $customer->getId()])
            ->getResultAsArray();

        foreach ($orders as $order) {
            $order->setStatus(
                $this->orderCrudController->generateStatus($order)
            );
        }

        $invoices = $this->get('invoice_crud_controller')
            ->readEntitiesBy(['idCustomers' => $customer->getId()])
            ->getResultAsArray();

        $this->setView('@Customer/Customer/show.html.twig');
        $this->setTemplateEntities([
            'customer' => $customer,
            'orders' => $orders,
            'invoices' => $invoices,
        ]);
        $this->setActionBar([
            [
                'label' => 'Back',
                'path' => 'customer_list',
                'icon' => 'fa-chevron-left'
            ]
        ]);

        return parent::baseShowAction();
    }

    /**
     * Customer New Action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $customer = new Customers();

        $form = $this->createForm(NewCustomerType::class, $customer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();

            try {
                $this->customerCrudController
                    ->createEntity($customer);
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Customer with this data already exists!');

                return $this->redirectToRoute('customer_list');
            }

            $this->addFlash('success', 'Customer added!');

            return $this->redirectToRoute('customer_list');
        }

        $this->setView('@Customer/Customer/new.html.twig');
        $this->setForm($form);
        $this->setActionBar([
            [
                'label' => 'Back',
                'path' => 'customer_list',
                'icon' => 'fa-chevron-left'
            ]
        ]);

        return parent::baseNewAction($request);
    }
}
