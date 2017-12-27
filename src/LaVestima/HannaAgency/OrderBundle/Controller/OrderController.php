<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\OrderBundle\Controller\Crud\OrderCrudControllerInterface;
use LaVestima\HannaAgency\OrderBundle\Controller\Crud\OrderStatusCrudControllerInterface;
use LaVestima\HannaAgency\OrderBundle\Entity\Orders;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class OrderController extends BaseController
{
    private $authorizationChecker;
    protected $orderCrudController;
    protected $orderStatusCrudController;

    protected $entityName = 'order';

    /**
     * OrderController constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param OrderCrudControllerInterface $orderCrudController
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        OrderCrudControllerInterface $orderCrudController,
        OrderStatusCrudControllerInterface $orderStatusCrudController
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->orderCrudController = $orderCrudController;
        $this->orderStatusCrudController = $orderStatusCrudController;
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
                'label' => 'Back',
                'path' => 'order_list',
                'icon' => 'fa-chevron-left'
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

        $this->orderCrudController->setAlias('o');

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $this->orderCrudController
                ->readOneEntityBy([
                    'pathSlug' => $pathSlug
                ]);
        }
        else if ($this->authorizationChecker->isGranted('ROLE_CUSTOMER')) {
            $this->orderCrudController
                ->readOneEntityBy([
                    'pathSlug' => $pathSlug,
                    'idCustomers' => $this->getCustomer()->getId()
                ]);
        }

        $this->orderCrudController->addSelect(
            Orders::getStatusColumnQuery()
        );

        $order = $this->orderCrudController->getResult();

        if (!$order) {
            $this->addFlash('warning', 'No order found!');

            return $this->redirectToRoute('order_list');
        }

        $orderStatus = $this->orderStatusCrudController->readOneEntityBy([
            'name' => $order['status']
        ])->getResult();

        if ($orderStatus) {
            $order[0]->setStatus($orderStatus);
        }

        $order = $order[0];

		$ordersProducts = $this->get('order_product_crud_controller')
			->readEntitiesBy([
			    'idOrders' => $order->getId()
            ])
            ->getResultAsArray();

		$this->setView('@Order/Order/show.html.twig');
		$this->setTemplateEntities([
            'order' => $order,
            'ordersProducts' => $ordersProducts,
        ]);

		return parent::baseShowAction();
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
