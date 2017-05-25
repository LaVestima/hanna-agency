<?php

namespace LaVestima\HannaAgency\CustomerBundle\Controller\Crud;

use LaVestima\HannaAgency\CustomerBundle\Controller\Helper\CustomerCrudHelper;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class CustomerCrudController extends CrudController {
	protected $entityClass = 'LaVestima\\HannaAgency\\CustomerBundle\\Entity\\Customers';

	public function createEntity($entity) {
	    $entity->setIdentificationNumber(CustomerCrudHelper::generateIdentificationNumber());
        return parent::createEntity($entity);
    }
}