<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Controller;

use LaVestima\HannaAgency\CustomerBundle\Entity\Customers;
use LaVestima\HannaAgency\CustomerBundle\Form\CustomerType;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class UserCustomerController extends BaseController
{
    public function newAction(Request $request)
    {
        // TODO: fix it !!!

        $customer = new Customers();

        $form = $this->createForm(CustomerType::class, $customer);

        $this->setView('@Customer/Customer/new.html.twig');
        $this->setForm($form);
        $this->setActionBar([
            [
                'label' => 'Back',
                'path' => 'customer_list',
                'icon' => 'fa-chevron-left'
            ]
        ]);

        return parent::baseNewAction();
    }
}
