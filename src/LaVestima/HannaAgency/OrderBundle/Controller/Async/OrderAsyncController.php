<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller\Async;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\Async\BaseAsyncController;
use LaVestima\HannaAgency\OrderBundle\Controller\Crud\OrderCrudControllerInterface;
use LaVestima\HannaAgency\OrderBundle\Entity\Orders;
use Symfony\Component\HttpFoundation\Request;

class OrderAsyncController extends BaseAsyncController
{
    private $orderCrudController;

    /**
     * OrderAsyncController constructor.
     *
     * @param OrderCrudControllerInterface $orderCrudController
     */
    public function __construct(
        OrderCrudControllerInterface $orderCrudController
    ) {
        $this->orderCrudController = $orderCrudController;
    }

    /**
     * Order Async Generic List Action.
     *
     * @param Request $request
     * @return mixed
     */
    protected function genericListAction(Request $request)
    {
        $filters = $request->get('filters');
        $sorters = $request->get('sorters');

        $this->orderCrudController
            ->setAlias('o');

        if (empty($filters)) {
            $this->orderCrudController
                ->readAllEntities();
        } else {
            $this->orderCrudController
                ->readEntitiesBy(
                    $this->convertFiltersToCrudCondition($filters)
                );
        }

        $this->orderCrudController->addSelect(
            Orders::getStatusColumnQuery()
        );

        if ($this->isListDeleted) {
            $this->orderCrudController
                ->onlyDeleted();
        } else {
            $this->orderCrudController
                ->onlyUndeleted();
        }

        $this->setQuery(
            $this->orderCrudController
                ->join('idCustomers', 'c')
                ->join('userCreated', 'u')
                ->orderBy(
                    isset($sorters) ? $sorters[0]['column'] : 'dateCreated',
                    isset($sorters) ? $sorters[0]['direction'] : 'desc'
                )
                ->getQuery()
        );
        $this->setView('@Order/Order/Async/list.html.twig');

        return parent::baseListAction($request);
    }
}
