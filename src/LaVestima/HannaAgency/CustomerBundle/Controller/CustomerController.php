<?php

namespace LaVestima\HannaAgency\CustomerBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use LaVestima\HannaAgency\CustomerBundle\Entity\Customers;
use LaVestima\HannaAgency\CustomerBundle\Form\NewCustomerType;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends BaseController
{
    public function listAction()
    {
        $customers = $this->get('customer_crud_controller')
            ->readAllEntities()
            ->getEntities();

        return $this->render('@Customer/Customer/list.html.twig', [
            'customers' => $customers,
        ]);
    }

    public function showAction(string $pathSlug)
    {
        $customer = $this->get('customer_crud_controller')
            ->readOneEntityBy(['pathSlug' => $pathSlug]);

        if (!$customer) {
            $this->addFlash(
                'warning',
                'No customer found!'
            );

            return $this->redirectToRoute('customer_list');
        }

        $orders = $this->get('order_crud_controller')
            ->readEntitiesBy(['idCustomers' => $customer])
            ->getEntities();

        $invoices = $this->get('invoice_crud_controller')
            ->readEntitiesBy(['idCustomers' => $customer])
            ->getEntities();

        return $this->render('@Customer/Customer/show.html.twig', [
            'customer' => $customer,
            'orders' => $orders,
            'invoices' => $invoices,
        ]);
    }

    public function newAction(Request $request)
    {
        $customer = new Customers();

        $form = $this->createForm(NewCustomerType::class, $customer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();

            try {
                $this->get('customer_crud_controller')
                    ->createEntity($customer);
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Customer with this data already exists!');

                return $this->redirectToRoute('customer_list');
            }

            $this->addFlash('success', 'Customer added!');

            return $this->redirectToRoute('customer_list');
        }

        return $this->render('@Customer/Customer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}