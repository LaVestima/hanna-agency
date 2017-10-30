<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Action;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;

trait ListActionControllerTrait
{
    protected $request;
    protected $query;

    protected $pagination;

    /**
     * List Action.
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function listAction(Request $request)
    {
        $this->request = $request;

        $this->configure();

        return $this->render($this->view, [
            'pagination' => $this->pagination,
            'actionBar' => $this->actionBar,
        ]);
    }

    /**
     * Initial configuration.
     *
     * @throws \Exception
     */
    private function configure()
    {
        if (!isset($this->query)) {
            throw new \Exception('No query defined!');
        }
        if (!isset($this->view)) {
            throw new \Exception('No view defined!');
        }

        $this->pagination = $this->paginate();

        if (strpos($this->pagination->getRoute(), 'async_list') === false) {
            $this->pagination->setUsedRoute(
                str_replace('list', 'async_list', $this->pagination->getRoute())
            );
        }
    }

    /**
     * Paginate with given query.
     *
     * @return mixed
     */
    private function paginate()
    {
        return $this->get('knp_paginator')->paginate(
            $this->query,
            $this->request->query->getInt('page', 1),
            $this->getParameter('paginator_entity_limit')
        );
    }

    /**
     * Set DB query.
     *
     * @param Query $query
     * @return $this
     */
    public function setQuery(Query $query)
    {
        $this->query = $query;

        return $this;
    }

    protected function convertFiltersToCrudCondition($filters)
    {
        $columnValueFilter = [];

        foreach ($filters as $key => $filter) {
            if (isset($filters[$key]['value'])) {
                $columnValueFilter[$filters[$key]['column']] = [$filters[$key]['value'], 'LIKE'];
            }
        }

        return $columnValueFilter;
    }
}