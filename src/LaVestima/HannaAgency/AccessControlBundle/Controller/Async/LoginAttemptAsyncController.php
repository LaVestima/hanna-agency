<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller\Async;

use LaVestima\HannaAgency\AccessControlBundle\Controller\Crud\LoginAttemptCrudControllerInterface;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\Async\BaseAsyncController;
use Symfony\Component\HttpFoundation\Request;

class LoginAttemptAsyncController extends BaseAsyncController
{
    private $loginAttemptCrudController;

    /**
     * LoginAttemptAsyncController constructor.
     *
     * @param LoginAttemptCrudControllerInterface $loginAttemptCrudController
     */
    public function __construct(
        LoginAttemptCrudControllerInterface $loginAttemptCrudController
    ) {
        $this->loginAttemptCrudController = $loginAttemptCrudController;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function genericListAction(Request $request)
    {
        $filters = $request->get('filters');
        $sorters = $request->get('sorters');

        $this->loginAttemptCrudController
            ->setAlias('la');

        if (empty($filters)) {
            $this->loginAttemptCrudController
                ->readAllEntities();
        } else {
            $this->loginAttemptCrudController
                ->readEntitiesBy(
                    $this->convertFiltersToCrudCondition($filters)
                );
        }

        $this->setQuery($this->loginAttemptCrudController
            ->leftJoin('idUsers', 'u')
            ->orderBy(
                isset($sorters) ? $sorters[0]['column'] : 'dateCreated',
                isset($sorters) ? $sorters[0]['direction'] : 'desc'
            )
            ->getQuery()
        );
        $this->setView('@AccessControl/LoginAttempt/Async/list.html.twig');

        return parent::baseListAction($request);
    }
}
