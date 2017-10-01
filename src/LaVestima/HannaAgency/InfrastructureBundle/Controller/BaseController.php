<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\Action\ActionControllerTrait;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\Action\ListActionControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    use ActionControllerTrait;
    use ListActionControllerTrait;

    protected $request;

    /**
     * @return bool
     */
    public function isDevEnvironment()
    {
        if ($this->get('kernel')->getEnvironment() === 'dev') {
            return true;
        }

        return false;
    }

    /**
     * Check if user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->get('security.authorization_checker')
            ->isGranted('ROLE_ADMIN');
    }

    /**
     * Check if user is a customer.
     *
     * @return bool
     */
    public function isCustomer()
    {
        return $this->get('security.authorization_checker')
            ->isGranted('ROLE_CUSTOMER');
    }

    /**
     * Get logged in customer.
     *
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->get('customer_crud_controller')
            ->readOneEntityBy(['idUsers' => $this->getUser()])
            ->getResult();
    }

    /**
     * Check if user is allowed to view the entity.
     *
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