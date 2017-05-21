<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class LoginAttemptCrudController extends CrudController {
    protected $entityClass = 'LaVestima\\HannaAgency\\AccessControlBundle\\Entity\\LoginAttempts';

    public function createEntity($entity) {
        $entity->setTimeLogged(new \DateTime('now'));
        return parent::createEntity($entity);
    }
}