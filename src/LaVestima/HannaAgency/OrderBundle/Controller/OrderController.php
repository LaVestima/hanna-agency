<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper\CrudHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrderController extends Controller {
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

	public function showAction($pathSlug) {
		$order = $this->get('order_crud_controller')
			->readOneEntityBy([
			    'pathSlug' => $pathSlug,
                'idCustomers' => $this->getCustomer() // TODO: not for admin+
            ]);

		if (!$order) {
		    // TODO: 'no order found' flash
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

    public function getCustomer() {
	    return $this->get('customer_crud_controller')
            ->readOneEntityBy(['idUsers' => $this->getUser()]);
    }
}