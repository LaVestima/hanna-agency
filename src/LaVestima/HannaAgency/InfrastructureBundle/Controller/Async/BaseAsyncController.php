<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Async;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Intl\Exception\MethodNotImplementedException;

class BaseAsyncController extends BaseController
{
    /**
     * @var Request
     */
    protected $request;
    protected $isListDeleted = false;

    /**
     * Async List Action.
     *
     * @param Request $request
     * @return mixed
     */
    public function listAction(Request $request)
    {
        $this->request = $request;

        $this->denyNonXhrs();

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
        $this->request = $request;

        $this->denyNonXhrs();

        $this->isListDeleted = true;

        return $this->genericListAction($request);
    }

    /**
     * Async Generic List Action.
     *
     * @param Request $request
     * @throws MethodNotImplementedException
     */
    protected function genericListAction(Request $request)
    {
        throw new MethodNotImplementedException('genericListAction');
    }

    /**
     * Deny access when request is not asynchronous.
     *
     * @throws AccessDeniedHttpException
     */
    private function denyNonXhrs()
    {
        if (!$this->request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        } else {
            $this->isAsync = true;
        }
    }
}
