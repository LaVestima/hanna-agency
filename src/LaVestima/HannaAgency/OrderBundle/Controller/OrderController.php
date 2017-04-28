<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper\CrudHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrderController extends Controller {
	public function listAction() {
		$orders = $this->get('order_crud_controller')
			->readAllEntities()
            ->sortBy(['datePlaced' => 'DESC'])
            ->getEntities();
		
		return $this->render('@Order/Order/list.html.twig', [
			'orders' => $orders,
		]);
	}

	public function showAction($pathSlug) {
		$order = $this->get('order_crud_controller')
			->readOneEntityBy(['pathSlug' => $pathSlug]);

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
}