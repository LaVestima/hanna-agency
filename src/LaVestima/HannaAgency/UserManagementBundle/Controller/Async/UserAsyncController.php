<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Controller\Async;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserAsyncController extends BaseController
{
    private $userCrudController;

    protected $isListDeleted = false;

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
     * User Async List Action.
     *
     * @param Request $request
     * @return mixed
     */
    public function listAction(Request $request)
    {
        $this->isListDeleted = false;

        return $this->genericListAction($request);
    }

    /**
     * User Async Deleted List Action.
     *
     * @param Request $request
     * @return mixed
     */
    public function deletedListAction(Request $request)
    {
        $this->isListDeleted = true;

        return $this->genericListAction($request);
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

        return parent::listAction($request);
    }
}
