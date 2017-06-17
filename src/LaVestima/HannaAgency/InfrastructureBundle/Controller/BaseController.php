<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends Controller {
    /**
     * @return boolean
     */
    public function isAdmin() {
        return $this->get('security.authorization_checker')
            ->isGranted('ROLE_ADMIN');
    }

    /**
     * @return mixed
     */
    public function getCustomer() {
        return $this->get('customer_crud_controller')
            ->readOneEntityBy(['idUsers' => $this->getUser()]);
    }

    /**
     * @param $entity
     *
     * @return bool
     */
    public function isUserAllowedToViewEntity($entity)
    {
        // TODO: test !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

        if ($this->isAdmin()) {
            return true;
        } elseif (method_exists($entity, 'getIdCustomers') &&
            $entity->getIdCustomers() === $this->getCustomer()) {
            return true;
        } else {
            return false;
        }
    }


}