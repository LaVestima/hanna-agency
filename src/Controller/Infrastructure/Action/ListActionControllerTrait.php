<?php

namespace App\Controller\Infrastructure\Action;

use Doctrine\ORM\Query;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\HttpFoundation\Request;

trait ListActionControllerTrait
{
    protected $query;
    protected $pagination;

    /**
     * List Action.
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function baseList(Request $request)
    {
        $this->request = $request;

        try {
            $this->configure();
        } catch (\Exception $e) {
            if ($this->isDevEnvironment()) {
                var_dump($e);
            }
        }

        return $this->render($this->view, [
            'isAsync' => $this->isAsync,
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
        if ($this->isAsync && !isset($this->query)) {
            throw new \Exception('No query defined!');
        }
        if (!isset($this->view)) {
            throw new \Exception('No view defined!');
        }

//        $this->pagination = $this->paginate();

        if ($this->isAsync) {
            $this->pagination = $this->paginate();

            if (
                strpos($this->pagination->getRoute(), 'async_list') === false &&
                strpos($this->pagination->getRoute(), 'async_deleted_list') === false
            ) {
                if (strpos($this->pagination->getRoute(), 'deleted_list') !== false) {
                    $this->pagination->setUsedRoute(
                        str_replace('deleted_list', 'async_deleted_list', $this->pagination->getRoute())
                    );
                } else {
                    $this->pagination->setUsedRoute(
                        str_replace('list', 'async_list', $this->pagination->getRoute())
                    );
                }
            }
        }
    }

    /**
     * Paginate with given query.
     *
     * @return mixed
     */
    private function paginate()
    {
        //dump($this->query);
//        dump($this->get('knp_paginator'));

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

    /**
     * Convert filters from inputs to SQL CRUD format.
     *
     * @param $filters
     * @return array
     */
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
