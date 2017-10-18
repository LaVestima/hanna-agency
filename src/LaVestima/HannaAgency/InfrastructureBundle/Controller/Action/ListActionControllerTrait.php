<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Action;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;

trait ListActionControllerTrait
{
    protected $request;
    protected $query;

    /**
     * List Action.
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function listAction(Request $request)
    {
        if (!isset($this->query)) {
            throw new \Exception('No query defined!');
        }
        if (!isset($this->view)) {
            throw new \Exception('No view defined!');
        }

        $this->request = $request;

        $pagination = $this->paginate();

        return $this->render($this->view, [
            'pagination' => $pagination,
            'actionBar' => $this->actionBar,
        ]);
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
}