<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller\Crud;

use Doctrine\Common\Persistence\ManagerRegistry;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderCrudController extends CrudController implements OrderCrudControllerInterface
{
	protected $entityClass = 'LaVestima\\HannaAgency\\OrderBundle\\Entity\\Orders';

	private $orderProductCrudController;
	private $orderStatusCrudController;

    /**
     * OrderCrudController constructor.
     *
     * @param ManagerRegistry $doctrine
     * @param TokenStorageInterface $tokenStorage
     * @param OrderProductCrudControllerInterface $orderProductCrudController
     * @param OrderStatusCrudControllerInterface $orderStatusCrudController
     */
	public function __construct(
	    ManagerRegistry $doctrine,
        TokenStorageInterface $tokenStorage,
        OrderProductCrudControllerInterface $orderProductCrudController,
        OrderStatusCrudControllerInterface $orderStatusCrudController
    ) {
	    $this->orderProductCrudController = $orderProductCrudController;
	    $this->orderStatusCrudController = $orderStatusCrudController;

        parent::__construct($doctrine, $tokenStorage);
    }

    /**
     * @param $order
     * @return mixed
     */
    public function generateStatus($order)
    {
        $ordersProducts = $this->orderProductCrudController
            ->readEntitiesBy(['idOrders' => $order->getId()])
            ->getResult();

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

        $orderStatus = $this->orderStatusCrudController
            ->readOneEntityBy(['name' => $orderStatusName])
            ->getResult();

        return $orderStatus;
    }
}