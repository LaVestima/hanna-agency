<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller\Async;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\Async\BaseAsyncController;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\ProductCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductAsyncController extends BaseAsyncController
{
    private $productCrudController;

    /**
     * ProductAsyncController constructor.
     *
     * @param ProductCrudControllerInterface $productCrudController
     */
    public function __construct(
        ProductCrudControllerInterface $productCrudController
    ) {
        $this->productCrudController = $productCrudController;
    }

    /**
     * Product Async Generic List Action.
     *
     * @param Request $request
     * @return mixed
     */
    protected function genericListAction(Request $request)
    {
        $filters = $request->get('filters');
        $sorters = $request->get('sorters');

        $this->productCrudController
            ->setAlias('p');

        if (empty($filters)) {
            $this->productCrudController
                ->readAllEntities();
        } else {
            $this->productCrudController
                ->readEntitiesBy(
                    $this->convertFiltersToCrudCondition($filters)
                );
        }

        if ($this->isListDeleted) {
            $this->productCrudController
                ->onlyDeleted();
        } else {
            $this->productCrudController
                ->onlyUndeleted();
        }

        $this->setQuery($this->productCrudController
            ->join('idCategories', 'c')
            ->join('idProducers', 'pr')
            ->orderBy(
                isset($sorters) ? $sorters[0]['column'] : 'name',
                isset($sorters) ? $sorters[0]['direction'] : 'asc'
            )
            ->getQuery()
        );
        $this->setView('@Product/Product/Async/list.html.twig');

        return parent::baseListAction($request);
    }
}
