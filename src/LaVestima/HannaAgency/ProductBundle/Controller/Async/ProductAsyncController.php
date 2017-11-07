<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller\Async;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\ProductCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductAsyncController extends BaseController
{
    private $productCrudController;

    private $isListDeleted = false;

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
        $this->isListDeleted = false;

        return $this->genericListAction($request);
    }

    /**
     * Product Async Deleted List Action.
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
     * Product Async Generic List Action.
     *
     * @param Request $request
     * @return mixed
     */
    private function genericListAction(Request $request)
    {
        $filters = $request->get('filters');

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
            ->orderBy('name')
            ->getQuery()
        );
        $this->setView('@Product/Product/Async/list.html.twig');

        return parent::listAction($request);
    }
}
