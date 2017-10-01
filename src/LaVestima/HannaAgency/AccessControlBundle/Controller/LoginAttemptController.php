<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class LoginAttemptController extends BaseController
{
    public function listAction(Request $request)
    {
        $this->setQuery($this->get('login_attempt_crud_controller') // TODO: DI
            ->setAlias('la')
            ->readAllEntities()
            ->leftJoin('idUsers', 'u')
            ->orderBy('dateCreated', 'DESC')
            ->getQuery());
        $this->setView('@AccessControl/LoginAttempt/list.html.twig');

        return parent::listAction($request);
    }
}