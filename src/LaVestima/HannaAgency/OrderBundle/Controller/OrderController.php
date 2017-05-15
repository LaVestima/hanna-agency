<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper\CrudHelper;

class OrderController extends BaseController {
	public function listAction() {
	    $authChecker = $this->get('security.authorization_checker');

        $orderCrudController = $this->get('order_crud_controller');
        $orders = null;

	    if ($authChecker->isGranted('ROLE_ADMIN')) {
            $orders = $orderCrudController->readAllEntities();
        }
        else if ($authChecker->isGranted('ROLE_CUSTOMER')) {
	        // TODO: change user to customer checking
            $currentCustomer = $this->get('customer_crud_controller')
                ->readOneEntityBy(['idUsers' => $this->getUser()]);
	        $orders = $orderCrudController
                ->readEntitiesBy(['idCustomers' => $currentCustomer]);
        }
        else {
	        // TODO: exception ?? for ROLE_USER and lower
        }

        $orders = $orders->sortBy(['datePlaced' => 'DESC'])
            ->getEntities();
		
		return $this->render('@Order/Order/list.html.twig', [
			'orders' => $orders,
		]);
	}

	public function showAction(string $pathSlug) {
        $authChecker = $this->get('security.authorization_checker');
        $orderCrudController = $this->get('order_crud_controller');

        $order = null;

        if ($authChecker->isGranted('ROLE_ADMIN')) {
            $order = $orderCrudController
                ->readOneEntityBy([
                    'pathSlug' => $pathSlug
                ]);
        }
        else if ($authChecker->isGranted('ROLE_CUSTOMER')) {
            $order = $orderCrudController
                ->readOneEntityBy([
                    'pathSlug' => $pathSlug,
                    'idCustomers' => $this->getCustomer()
                ]);
        }
        else {
            // TODO: exception ?? for ROLE_USER and lower
        }

		if (!$order) {
            $this->addFlash(
                'warning',
                'No order found!'
            );

		    return $this->redirectToRoute('order_list');
        }

		$ordersProducts = $this->get('order_product_crud_controller')
			->readEntitiesBy(['idOrders' => $order])
            ->getEntities();
		
		return $this->render('@Order/Order/show.html.twig', [
			'order' => $order,
			'ordersProducts' => $ordersProducts,
		]);
	}

	public function newAction() {

	    return $this->render('@Order/Order/new.html.twig');
    }

    public function deleteAction(string $pathSlug) {
	    $order = $this->get('order_crud_controller')
            ->readOneEntityBy(['pathSlug' => $pathSlug]);

	    if (!$order) {
	        // TODO: change
	        var_dump('errorrrrr');
	        die();
        }

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            || $order->getIdCustomers() === $this->getCustomer()) {
            $this->get('order_crud_controller')
                ->deleteEntity($order);

            $this->addFlash(
                'notice',
                'Order deleted!'
            );
        }
        else {
            $this->addFlash(
                'warning',
                'No order found!'
            );
        }

        return $this->redirectToRoute('order_list');
    }
}