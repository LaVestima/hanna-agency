<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\OrderBundle\Controller\Crud\OrderCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class OrderController extends BaseController
{
    private $authorizationChecker;
    protected $orderCrudController;

    protected $entityName = 'order';

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
        // TODO: find better way of generating status
//        foreach ($pagination->getItems() as $order) {
//            $order->setStatus(
//                $this->orderCrudController->generateStatus($order)
//            );
//        }

        $this->setView('@Order/Order/list.html.twig');
        $this->setActionBar([
            [
                'label' => 'Place Order',
                'path' => 'order_placement_new',
                'role' => 'ROLE_CUSTOMER',
                'icon' => 'fa-file-text-o'
            ],
            [
                'label' => 'Deleted Orders',
                'path' => 'order_deleted_list',
                'role' => 'ROLE_ADMIN',
                'icon' => 'fa-close'
            ],
        ]);

        return parent::baseListAction($request);
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
        $this->setView('@Order/Order/deletedList.html.twig');
        $this->setActionBar([
            [
                'label' => '< Back',
                'path' => 'order_list'
            ]
        ]);

        return parent::baseListAction($request);
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
            ->getResultAsArray();

		return $this->render('@Order/Order/show.html.twig', [
			'order' => $order,
			'ordersProducts' => $ordersProducts,
		]);
	}

    /**
     * Order Restore Action.
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
