<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Controller\Async;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\Async\BaseAsyncController;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserAsyncController extends BaseAsyncController
{
    private $userCrudController;

    /**
     * UserAsyncController constructor.
     *
     * @param UserCrudControllerInterface $userCrudController
     */
    public function __construct(
        UserCrudControllerInterface $userCrudController
    ) {
        $this->userCrudController = $userCrudController;
    }

    /**
     * User Async Generic List Action.
     *
     * @param Request $request
     * @return mixed
     */
    public function genericListAction(Request $request)
    {
        $filters = $request->get('filters');

        $this->userCrudController
            ->setAlias('u');

        if (empty($filters)) {
            $this->userCrudController
                ->readAllEntities();
        } else {
            $this->userCrudController
                ->readEntitiesBy(
                    $this->convertFiltersToCrudCondition($filters)
                );
        }

        if ($this->isListDeleted) {
            $this->userCrudController
                ->onlyDeleted();
        } else {
            $this->userCrudController
                ->onlyUndeleted();
        }

        $this->setQuery($this->userCrudController
            ->join('idRoles', 'r')
            ->orderBy('login', 'ASC')
            ->getQuery()
        );
        $this->setView('@UserManagement/User/Async/list.html.twig');

        return parent::baseListAction($request);
    }
}
