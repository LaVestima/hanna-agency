<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;

class TokenCrudController extends CrudController {
	protected $entityClass = 'LaVestima\\HannaAgency\\AccessControlBundle\\Entity\\Tokens';

	public function createEntity(EntityInterface $entity) {
	    $entity->setDateExpired(new \DateTime('now +1 day'));

        return parent::createEntity($entity);
    }
}