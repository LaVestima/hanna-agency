<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Async;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Exception\MethodNotImplementedException;

class BaseAsyncController extends BaseController
{
    protected $isListDeleted = false;

    public function listAction(Request $request)
    {
        $this->isListDeleted = false;

        return $this->genericListAction($request);
    }

    /**
     * Async Deleted List Action.
     *
     * @param Request $request
     * @return mixed
     */
    public function deletedListAction(Request $request)
    {
        $this->isListDeleted = true;

        return $this->genericListAction($request);
    }

    protected function genericListAction(Request $request)
    {
        throw new MethodNotImplementedException('genericListAction');
    }
}