<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller\Async;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\OrderBundle\Controller\Crud\OrderCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderAsyncController extends BaseController
{
    private $orderCrudController;

    private $isListDeleted = false;

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
     * Order Async List Action.
     *
     * @param Request $request
     * @return mixed
     */
    public function listAction(Request $request)
    {
        $this->isListDeleted = false;

        return $this->genericListAction($request);
    }

    /**
     * Order Async Deleted List Action.
     *
     * @param Request $request
     * @return mixed
     */
    public function deletedListAction(Request $request)
    {
        $this->isListDeleted = true;

        return $this->genericListAction($request);
    }

    private function genericListAction(Request $request)
    {
        $filters = $request->get('filters');

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
                ->orderBy('dateCreated', 'DESC')
                ->getQuery()
        );
        $this->setView('@Order/Order/Async/list.html.twig');

        return parent::listAction($request);
    }
}
