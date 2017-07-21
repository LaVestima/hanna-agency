<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller\Crud;

use Doctrine\Bundle\DoctrineBundle\Registry;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderCrudController extends CrudController {
	protected $entityClass = 'LaVestima\\HannaAgency\\OrderBundle\\Entity\\Orders';

	private $orderProductCrudController;
	private $orderStatusCrudController;

	public function __construct(
	    Registry $doctrine,
        TokenStorageInterface $tokenStorage,
        OrderProductCrudController $orderProductCrudController,
        OrderStatusCrudController $orderStatusCrudController
    ) {
	    $this->orderProductCrudController = $orderProductCrudController;
	    $this->orderStatusCrudController = $orderStatusCrudController;

        parent::__construct($doctrine, $tokenStorage);
    }

    public function generateStatus($order)
    {
        // TODO: think about something better, causes too many db queries

        $ordersProducts = $this->orderProductCrudController
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

        $orderStatus = $this->orderStatusCrudController
            ->readOneEntityBy(['name' => $orderStatusName]);

        return $orderStatus;
    }
}