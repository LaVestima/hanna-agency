<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 25.03.17
 * Time: 19:16
 */

namespace LaVestima\HannaAgency\InvoiceBundle\Controller\Crud;


use LaVestima\HannaAgency\InfrastructureBundle\Controller\CrudController;

class InvoiceProductCrudController extends CrudController {
	protected $entityClass = 'LaVestima\\HannaAgency\\InvoiceBundle\\Entity\\InvoicesProducts';
}