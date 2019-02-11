<?php

namespace App\Controller\Product\Async;

use App\Controller\Infrastructure\Async\BaseAsyncController;
//use App\Controller\Product\Crud\ProductCrudControllerInterface;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductAsyncController extends BaseAsyncController
{
    private $productCrudController;

    /**
     * ProductAsyncController constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(
        ProductRepository $productRepository
    ) {
        $this->productCrudController = $productRepository;
    }

    /**
     * @Route("/product/Async/list", name="product_async_list", options={"expose"=true})
     *
     * @return mixed
     */
    public function list(Request $request)
    {
//        die;
        return parent::list($request);
    }

    /**
     * Product Async Generic List Action.
     *
     * @param Request $request
     * @return mixed
     */
    protected function genericList(Request $request)
    {
//        echo 'asdf';die;
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
        $this->setView('Product/Async/list.html.twig');

        return parent::baseList($request);
    }
}
