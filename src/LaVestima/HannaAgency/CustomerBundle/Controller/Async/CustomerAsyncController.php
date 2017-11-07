<?php

namespace LaVestima\HannaAgency\CustomerBundle\Controller\Async;

use LaVestima\HannaAgency\CustomerBundle\Controller\Crud\CustomerCrudControllerInterface;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class CustomerAsyncController extends BaseController
{
    private $customerCrudController;

    private $isListDeleted = false;

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
     * Customer Async List Action.
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
     * Customer Async Deleted List Action.
     *
     * @param Request $request
     * @return mixed
     */
    public function deletedListAction(Request $request)
    {
        $this->isListDeleted = true;

        return $this->genericListAction($request);
    }

    /**
     * Customer Async Generic List Action.
     *
     * @param Request $request
     * @return mixed
     */
    private function genericListAction(Request $request)
    {
        $filters = $request->get('filters');

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
            ->orderBy('identificationNumber')
            ->getQuery()
        );
        $this->setView('@Customer/Customer/Async/list.html.twig');

        return parent::listAction($request);
    }
}