<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller\Async;

use LaVestima\HannaAgency\AccessControlBundle\Controller\Crud\LoginAttemptCrudControllerInterface;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class LoginAttemptAsyncController extends BaseController
{
    private $loginAttemptCrudController;

    public function __construct(
        LoginAttemptCrudControllerInterface $loginAttemptCrudController
    ) {
        $this->loginAttemptCrudController = $loginAttemptCrudController;
    }

    public function listAction(Request $request)
    {
        $filters = $request->get('filters');

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
            ->orderBy('dateCreated', 'DESC')
            ->getQuery()
        );
        $this->setView('@AccessControl/LoginAttempt/Async/list.html.twig');

        return parent::listAction($request);
    }
}
