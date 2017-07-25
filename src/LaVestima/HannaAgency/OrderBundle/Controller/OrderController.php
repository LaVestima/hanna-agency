<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\OrderBundle\Entity\Orders;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class OrderController extends BaseController
{
	public function listAction(Request $request)
    {
        $authChecker = $this->get('security.authorization_checker');

        $orderCrudController = $this->get('order_crud_controller');

        if ($authChecker->isGranted('ROLE_ADMIN')) {
            $orderCrudController->readAllUndeletedEntities();
        }
        else if ($authChecker->isGranted('ROLE_CUSTOMER')) {
            // TODO: only undeleted
            $orderCrudController->readEntitiesBy([
                'idCustomers' => $this->getCustomer()
            ]);
        }
        else {
//	        throw new AccessDeniedHttpException();
            // TODO: exception ?? for ROLE_USER and lower
        }
        // TODO: sort orders ...


        $query = $orderCrudController->getQuery();

        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            10 /*limit per page*/
        );

        foreach ($pagination->getItems() as $order) {
            $order->setStatus($orderCrudController->generateStatus($order));
        }

        return $this->render('@Order/Order/list.html.twig', [
            'pagination' => $pagination
        ]);



        // --------------------

//        $orders = $orders->sortBy(['dateCreated' => 'DESC'])
//            ->getEntities();
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

	    if (!$order || !$this->isUserAllowedToViewEntity($order)) {
            $this->addFlash('warning', 'No order found!');
        } else {
            $this->get('order_crud_controller')
                ->deleteEntity($order);

            $this->addFlash('notice', 'Order deleted!');
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
}