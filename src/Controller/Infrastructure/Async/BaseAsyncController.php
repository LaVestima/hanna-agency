<?php

namespace App\Controller\Infrastructure\Async;

use App\Controller\Infrastructure\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Exception\MethodNotImplementedException;

class BaseAsyncController extends BaseController
{

    protected $isListDeleted = false;

    /**
     * Async List Action.
     *
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request)
    {
        $this->request = $request;

        $this->denyNonXhrs();

        $this->isListDeleted = false;
        $this->isAsync = true;

        return $this->genericList($request);
    }

    /**
     * Async Deleted List Action.
     *
     * @param Request $request
     * @return mixed
     */
    public function deletedList(Request $request)
    {
        $this->request = $request;

        $this->denyNonXhrs();

        $this->isListDeleted = true;

        return $this->genericList($request);
    }

    /**
     * Async Generic List Action.
     *
     * @param Request $request
     * @throws MethodNotImplementedException
     */
    protected function genericList(Request $request)
    {
        throw new MethodNotImplementedException('genericListAction');
    }
}
