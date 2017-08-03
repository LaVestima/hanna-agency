<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\OrderBundle\Controller\Crud\OrderCrudControllerInterface;
use LaVestima\HannaAgency\OrderBundle\Entity\Orders;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class OrderController extends BaseController
{
    private $authorizationChecker;
    private $orderCrudController;

    /**
     * OrderController constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param OrderCrudControllerInterface $orderCrudController
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        OrderCrudControllerInterface $orderCrudController

    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->orderCrudController = $orderCrudController;
    }

    /**
     * Order List Action.
     *
     * @param Request $request
     * @param string $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, string $type)
    {
        if (!in_array($type, ['', 'deleted'])) {
            throw new NotFoundHttpException();
        }

        $this->orderCrudController->setAlias('o');

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            if ($type === '') {
                $this->orderCrudController->readAllUndeletedEntities();
            } elseif ($type === 'deleted') {
                $this->orderCrudController->readAllDeletedEntities();
            }
        }
        else if ($this->authorizationChecker->isGranted('ROLE_CUSTOMER')) {
            // TODO: only undeleted
            $this->orderCrudController->readEntitiesBy([
                'idCustomers' => $this->getCustomer()->getId()
            ]);
        }

        $this->orderCrudController
            ->join('idCustomers', 'c')
            ->orderBy('dateCreated', 'DESC');

        $pagination = $this->get('knp_paginator')->paginate(
            $this->orderCrudController->getQuery(),
            $request->query->getInt('page', 1)/*page number*/,
            10 /*limit per page*/
        );

        foreach ($pagination->getItems() as $order) {
            $order->setStatus(
                $this->orderCrudController->generateStatus($order)
            );
        }

        return $this->render('@Order/Order/list.html.twig', [
            'pagination' => $pagination,
            'listType' => $type
        ]);
	}

    /**
     * Order Show Action
     *
     * @param string $pathSlug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
	public function showAction(string $pathSlug)
    {
        $order = null;

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $order = $this->orderCrudController
                ->readOneEntityBy([
                    'pathSlug' => $pathSlug
                ]);
        }
        else if ($this->authorizationChecker->isGranted('ROLE_CUSTOMER')) {
            $order = $this->orderCrudController
                ->readOneEntityBy([
                    'pathSlug' => $pathSlug,
                    'idCustomers' => $this->getCustomer()->getId()
                ]);
        }

        $order = $this->orderCrudController->getResult();

		if (!$order) {
            $this->addFlash('warning', 'No order found!');

		    return $this->redirectToRoute('order_list');
        }

		$ordersProducts = $this->get('order_product_crud_controller')
			->readEntitiesBy([
			    'idOrders' => $order->getId()
            ])
            ->getResult();
		
		return $this->render('@Order/Order/show.html.twig', [
			'order' => $order,
			'ordersProducts' => $ordersProducts,
		]);
	}

	// TODO: delete??
//	public function newAction()
//    {
//
//	    return $this->render('@Order/Order/new.html.twig');
//    }

    /**
     * Order Delete Action
     *
     * @param string $pathSlug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(string $pathSlug)
    {
	    $order = $this->orderCrudController
            ->readOneEntityBy(['pathSlug' => $pathSlug])
            ->getResult();

	    if (!$order || !$this->isUserAllowedToViewEntity($order)) {
            $this->addFlash('warning', 'No order found!');
        } else {
            $this->orderCrudController->deleteEntity($order);

            $this->addFlash('notice', 'Order deleted!');
        }

        return $this->redirectToRoute('order_list');
    }

    /**
     * Order Restore Action
     *
     * @param string $pathSlug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function restoreAction(string $pathSlug)
    {
	    $order = $this->get('order_crud_controller')
            ->readOneEntityBy(['pathSlug' => $pathSlug])
            ->getResult();

	    $this->get('order_crud_controller')
            ->restoreEntity($order);

	    $this->addFlash('success', 'Order restored!');

	    return $this->redirectToRoute('order_list');
	    // TODO: finish
    }
}