<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;

class LoginAttemptCrudController extends CrudController {
    protected $entityClass = 'LaVestima\\HannaAgency\\AccessControlBundle\\Entity\\LoginAttempts';

    public function createEntity(EntityInterface $entity) {
        $entity->setTimeLogged(new \DateTime('now'));
        return parent::createEntity($entity);
    }
}