<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\OrderBundle\Controller\Crud\OrderCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;
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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $this->orderCrudController->setAlias('o');

        if ($this->isAdmin()) {
            $this->orderCrudController->readAllUndeletedEntities();
        } elseif ($this->isCustomer()) {
            // TODO: only undeleted
            $this->orderCrudController->readEntitiesBy([
                'idCustomers' => $this->getCustomer()->getId()
            ]);
        }

        $this->orderCrudController
            ->join('idCustomers', 'c')
            ->orderBy('dateCreated', 'DESC');

        // TODO: find better way of generating status
//        foreach ($pagination->getItems() as $order) {
//            $order->setStatus(
//                $this->orderCrudController->generateStatus($order)
//            );
//        }

        $this->setQuery($this->orderCrudController->getQuery());
        $this->setView('@Order/Order/list.html.twig');
        $this->setActionBar([
            [
                'label' => 'Place Order',
                'path' => 'order_placement_new'
            ],
            [
                'label' => 'Deleted Orders',
                'path' => 'order_deleted_list'
            ],
//            [
//                'label' => 'New Order',
//                'path' => 'order_new',
//                'role' => 'ROLE_ADMIN'
//            ]
        ]);

        return parent::listAction($request);
	}

    /**
     * Order Deleted List Action.
     *
     * @param Request $request
     *
     * @return mixed
     */
	public function deletedListAction(Request $request)
    {
        $this->orderCrudController->setAlias('o');

        if ($this->isAdmin()) {
            $this->orderCrudController->readAllDeletedEntities();
        } elseif ($this->isCustomer()) {
            // TODO: only undeleted
            $this->orderCrudController->readEntitiesBy([
                'idCustomers' => $this->getCustomer()->getId()
            ]);
        }

        $this->orderCrudController
            ->join('idCustomers', 'c')
            ->orderBy('dateCreated', 'DESC');

        $this->setQuery($this->orderCrudController->getQuery());
        $this->setView('@Order/Order/list.html.twig');
        $this->setActionBar([
            [
                'label' => '< Back',
                'path' => 'order_list'
            ]
        ]);

        return parent::listAction($request);
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