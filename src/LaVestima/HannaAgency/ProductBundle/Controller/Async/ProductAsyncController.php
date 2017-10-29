<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller\Async;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\ProductCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductAsyncController extends BaseController
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
     * Product Async List Action.
     *
     * @param Request $request
     * @return mixed
     */
    public function listAction(Request $request)
    {
        $filters = $request->get('filters');

        $this->productCrudController
            ->setAlias('p');

        if (empty($filters)) {
            $this->productCrudController
                ->readAllUndeletedEntities();
        } else {
            $columnValueFilter = [];
            foreach ($filters as $key => $filter) {
                if (isset($filters[$key]['value'])) {
                    $columnValueFilter[$filters[$key]['column']] = [$filters[$key]['value'], 'LIKE'];
                }
            }

            $this->productCrudController
                ->readEntitiesBy($columnValueFilter);
        }

        $this->setQuery($this->productCrudController
            ->join('idCategories', 'c')
            ->join('idProducers', 'pr')
            ->orderBy('name')
            ->getQuery()
        );
        $this->setView('@Product/Product/Async/list.html.twig');

        return parent::listAction($request);
    }
}
