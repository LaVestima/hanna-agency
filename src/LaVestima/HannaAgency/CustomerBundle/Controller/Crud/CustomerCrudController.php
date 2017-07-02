<?php

namespace LaVestima\HannaAgency\CustomerBundle\Controller\Crud;

use LaVestima\HannaAgency\CustomerBundle\Controller\Helper\CustomerCrudHelper;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;

class CustomerCrudController extends CrudController {
	protected $entityClass = 'LaVestima\\HannaAgency\\CustomerBundle\\Entity\\Customers';

	public function createEntity(EntityInterface $entity) {
	    $entity->setIdentificationNumber(CustomerCrudHelper::generateIdentificationNumber());
        return parent::createEntity($entity);
    }
}