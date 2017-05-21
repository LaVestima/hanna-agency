<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller {
    public function isAdmin() {
        return $this->get('security.authorization_checker')
            ->isGranted('ROLE_ADMIN');
    }

    public function getCustomer() {
        return $this->get('customer_crud_controller')
            ->readOneEntityBy(['idUsers' => $this->getUser()]);
    }
}