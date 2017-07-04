<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\OrderBundle\Entity\Orders;

class OrderController extends BaseController
{
	public function listAction()
    {
	    $authChecker = $this->get('security.authorization_checker');

        $orderCrudController = $this->get('order_crud_controller');
        $orders = null;

	    if ($authChecker->isGranted('ROLE_ADMIN')) {
            $orders = $orderCrudController->readAllUndeletedEntities();
        }
        else if ($authChecker->isGranted('ROLE_CUSTOMER')) {
	        // TODO: only undeleted
	        $orders = $orderCrudController
                ->readEntitiesBy(['idCustomers' => $this->getCustomer()]);
        }
        else {
	        // TODO: exception ?? for ROLE_USER and lower
        }

        $orders = $orders->sortBy(['datePlaced' => 'DESC'])
            ->getEntities();

        foreach ($orders as $order) {
            $order->setStatus(
                $this->generateOrderStatus($order)
            );
        }
		
		return $this->render('@Order/Order/list.html.twig', [
			'orders' => $orders,
		]);
	}

	public function deletedListAction()
    {
	    $orders = $this->get('order_crud_controller')
            ->readAllDeletedEntities()
            ->sortBy(['datePlaced' => 'DESC'])
            ->getEntities();

	    return $this->render('@Order/Order/deletedList.html.twig', [
	        'orders' => $orders,
        ]);
    }

	public function showAction(string $pathSlug)
    {
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
            $this->addFlash('warning', 'No order found!');

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

	// TODO: delete??
	public function newAction()
    {

	    return $this->render('@Order/Order/new.html.twig');
    }

    public function deleteAction(string $pathSlug)
    {
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

            $this->addFlash('notice', 'Order deleted!');
        }
        else {
            $this->addFlash('warning', 'No order found!');
        }

        return $this->redirectToRoute('order_list');
    }

    public function restoreAction(string $pathSlug)
    {
	    $order = $this->get('order_crud_controller')
            ->readOneEntityBy(['pathSlug' => $pathSlug]);

	    $this->get('order_crud_controller')
            ->restoreEntity($order);

	    $this->addFlash('success', 'Order restored!');

	    return $this->redirectToRoute('order_list');
	    // TODO: finish
    }

    protected function generateOrderStatus(Orders $order)
    {
	    $ordersProducts = $this->get('order_product_crud_controller')
            ->readEntitiesBy(['idOrders' => $order])
            ->getEntities();

	    $orderStatusName = 'Queued';
	    $isOrderCompleted = true;

	    foreach ($ordersProducts as $ordersProduct) {
            $ordersProductStatus = $ordersProduct->getIdStatuses()->getName();
            if ($ordersProductStatus === 'Rejected') {
                $orderStatusName = $ordersProductStatus;
                $isOrderCompleted = false;
                break;
            }
            else if ($ordersProductStatus === 'Pending') {
                $orderStatusName = $ordersProductStatus;
                $isOrderCompleted = false;
            }
            else if ($ordersProductStatus === 'Queued') {
                $isOrderCompleted = false;
            }
        }

        if ($isOrderCompleted) {
	        $orderStatusName = 'Completed';
        }

        $orderStatus = $this->get('order_status_crud_controller')
            ->readOneEntityBy(['name' => $orderStatusName]);
        return $orderStatus;
    }
}