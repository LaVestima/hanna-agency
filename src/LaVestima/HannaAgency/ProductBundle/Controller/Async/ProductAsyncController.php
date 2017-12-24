<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller\Async;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\Async\BaseAsyncController;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\ProductCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        // TODO: put this for all async actions
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

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

        return parent::baseListAction($request);
    }
}
