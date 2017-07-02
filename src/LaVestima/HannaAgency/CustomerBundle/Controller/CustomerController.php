<?php

namespace LaVestima\HannaAgency\CustomerBundle\Controller;

use LaVestima\HannaAgency\CustomerBundle\Entity\Customers;
use LaVestima\HannaAgency\CustomerBundle\Form\NewCustomerType;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends BaseController {
    public function listAction() {
        $customers = $this->get('customer_crud_controller')
            ->readAllEntities()
            ->getEntities();

        return $this->render('@Customer/Customer/list.html.twig', [
            'customers' => $customers,
        ]);
    }

    public function showAction(string $pathSlug) {
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

    public function newAction(Request $request) {
        $customer = new Customers();

        $countries = $this->get('country_crud_controller')
            ->readAllEntities()
            ->getEntities();
        $cities = $this->get('city_crud_controller')
            ->readAllEntities()
            ->getEntities();
        $currencies = $this->get('currency_crud_controller')
            ->readAllEntities()
            ->getEntities();
        $users = $this->get('user_crud_controller')
            ->readAllEntities()
            ->getEntities();

        $form = $this->createForm(NewCustomerType::class, $customer, [
            'countries' => $countries,
            'cities' => $cities,
            'currencies' => $currencies,
            'users' => $users,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();

            $this->get('customer_crud_controller')
                ->createEntity($customer);
        }

        return $this->render('@Customer/Customer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}