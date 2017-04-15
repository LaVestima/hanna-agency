<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 15/04/17
 * Time: 14:03
 */

namespace LaVestima\HannaAgency\UserManagementBundle\Controller\Crud;


use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class ConfigurationCrudController extends CrudController {
    protected $entityClass = 'LaVestima\\HannaAgency\\UserManagementBundle\\Entity\\Configurations';
}