<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;

class LoginAttemptCrudController extends CrudController implements LoginAttemptCrudControllerInterface
{
    protected $entityClass = 'LaVestima\\HannaAgency\\AccessControlBundle\\Entity\\LoginAttempts';

    public function createEntity(EntityInterface $entity) {
        $entity->setDateCreated(new \DateTime('now'));
        return parent::createEntity($entity);
    }
}