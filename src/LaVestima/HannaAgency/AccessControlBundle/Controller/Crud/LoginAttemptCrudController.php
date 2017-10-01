<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class LoginAttemptCrudController extends CrudController implements LoginAttemptCrudControllerInterface
{
    protected $entityClass = 'LaVestima\\HannaAgency\\AccessControlBundle\\Entity\\LoginAttempts';
}