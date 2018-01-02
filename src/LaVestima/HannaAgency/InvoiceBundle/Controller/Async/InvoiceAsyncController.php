<?php

namespace LaVestima\HannaAgency\InvoiceBundle\Controller\Async;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\Async\BaseAsyncController;
use LaVestima\HannaAgency\InvoiceBundle\Controller\Crud\InvoiceCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;

class InvoiceAsyncController extends BaseAsyncController
{
    private $invoiceCrudController;

    /**
     * InvoiceAsyncController constructor.
     *
     * @param InvoiceCrudControllerInterface $invoiceCrudController
     */
    public function __construct(
        InvoiceCrudControllerInterface $invoiceCrudController
    ) {
        $this->invoiceCrudController = $invoiceCrudController;
    }

    /**
     * Invoice Async Generic List Action.
     *
     * @param Request $request
     * @return mixed
     */
    protected function genericListAction(Request $request)
    {
        $filters = $request->get('filters');
        $sorters = $request->get('sorters');

        $this->invoiceCrudController
            ->setAlias('i');

        if (empty($filters)) {
            $this->invoiceCrudController
                ->readAllEntities();
        } else {
            $this->invoiceCrudController
                ->readEntitiesBy(
                    $this->convertFiltersToCrudCondition($filters)
                );
        }

        if ($this->isListDeleted) {
            $this->invoiceCrudController
                ->onlyDeleted();
        } else {
            $this->invoiceCrudController
                ->onlyUndeleted();
        }

        $this->setQuery($this->invoiceCrudController
            ->join('idCustomers', 'c')
            ->join('userCreated', 'u')
            ->orderBy(
                isset($sorters) ? $sorters[0]['column'] : 'dateCreated',
                isset($sorters) ? $sorters[0]['direction'] : 'desc'
            )
            ->getQuery()
        );
        $this->setView('@Invoice/Invoice/Async/list.html.twig');

        return parent::baseListAction($request);
    }
}
