<?php

namespace LaVestima\HannaAgency\CustomerBundle\Controller\Async;

use LaVestima\HannaAgency\CustomerBundle\Controller\Crud\CustomerCrudControllerInterface;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\Async\BaseAsyncController;
use Symfony\Component\HttpFoundation\Request;

class CustomerAsyncController extends BaseAsyncController
{
    private $customerCrudController;

    /**
     * CustomerAsyncController constructor.
     *
     * @param CustomerCrudControllerInterface $customerCrudController
     */
    public function __construct(
        CustomerCrudControllerInterface $customerCrudController
    ) {
        $this->customerCrudController = $customerCrudController;
    }

    /**
     * Customer Async Generic List Action.
     *
     * @param Request $request
     * @return mixed
     */
    protected function genericListAction(Request $request)
    {
        $filters = $request->get('filters');
        $sorters = $request->get('sorters');

        $this->customerCrudController
            ->setAlias('c');

        if (empty($filters)) {
            $this->customerCrudController
                ->readAllEntities();
        } else {
            $this->customerCrudController
                ->readEntitiesBy(
                    $this->convertFiltersToCrudCondition($filters)
                );
        }

        if ($this->isListDeleted) {
            $this->customerCrudController
                ->onlyDeleted();
        } else {
            $this->customerCrudController
                ->onlyUndeleted();
        }

        $this->setQuery($this->customerCrudController
            ->leftJoin('idUsers', 'u')
            ->orderBy(
                isset($sorters) ? $sorters[0]['column'] : 'identificationNumber',
                isset($sorters) ? $sorters[0]['direction'] : 'asc'
            )
            ->getQuery()
        );
        $this->setView('@Customer/Customer/Async/list.html.twig');

        return parent::baseListAction($request);
    }
}
