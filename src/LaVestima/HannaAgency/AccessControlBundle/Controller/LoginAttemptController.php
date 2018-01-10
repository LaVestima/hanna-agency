<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use LaVestima\HannaAgency\AccessControlBundle\Controller\Crud\LoginAttemptCrudControllerInterface;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class LoginAttemptController extends BaseController
{
    private $loginAttemptCrudController;

    /**
     * LoginAttemptController constructor.
     *
     * @param LoginAttemptCrudControllerInterface $loginAttemptCrudController
     */
    public function __construct(
        LoginAttemptCrudControllerInterface $loginAttemptCrudController
    ) {
        $this->loginAttemptCrudController = $loginAttemptCrudController;
    }

    /**
     * Login Attempt List Action.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function listAction(Request $request)
    {
        $this->setQuery($this->loginAttemptCrudController
            ->setAlias('la')
            ->readAllEntities()
            ->leftJoin('idUsers', 'u')
            ->orderBy('dateCreated', 'DESC')
            ->getQuery());
        $this->setView('@AccessControl/LoginAttempt/list.html.twig');
        $this->setActionBar([
            [
                'label' => 'User List',
                'path' => 'user_list',
                'icon' => 'fa-chevron-left'
            ]
        ]);

        return parent::baseListAction($request);
    }
}
