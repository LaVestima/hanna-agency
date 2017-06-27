<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller\Crud;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;

class OrderCrudController extends CrudController {
	protected $entityClass = 'LaVestima\\HannaAgency\\OrderBundle\\Entity\\Orders';

	public function createEntity(EntityInterface $entity)
    {
        $entity->setDatePlaced(new \DateTime('now'));

        return parent::createEntity($entity);
    }
}