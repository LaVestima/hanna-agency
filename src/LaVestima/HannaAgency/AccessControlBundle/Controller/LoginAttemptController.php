<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class LoginAttemptController extends BaseController
{
    /**
     * Login Attempt List Action.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function listAction(Request $request)
    {
        $this->setQuery($this->get('login_attempt_crud_controller') // TODO: DI
            ->setAlias('la')
            ->readAllEntities()
            ->leftJoin('idUsers', 'u')
            ->orderBy('dateCreated', 'DESC')
            ->getQuery());
        $this->setView('@AccessControl/LoginAttempt/list.html.twig');
        $this->setActionBar([
            [
                'label' => '< User List',
                'path' => 'user_list'
            ]
        ]);

        return parent::listAction($request);
    }
}