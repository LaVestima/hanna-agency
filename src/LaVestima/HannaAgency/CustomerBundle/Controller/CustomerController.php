<?php

namespace LaVestima\HannaAgency\CustomerBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;

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

        // TODO: $invoices

        return $this->render('@Customer/Customer/show.html.twig', [
            'customer' => $customer,
            'orders' => $orders,
        ]);
    }

    public function newAction() {

    }
}