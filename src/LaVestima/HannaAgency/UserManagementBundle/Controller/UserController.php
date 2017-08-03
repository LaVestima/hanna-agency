<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Controller;

use LaVestima\HannaAgency\CustomerBundle\Controller\Crud\CustomerCrudControllerInterface;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    private $userCrudController;
    private $customerCrudController;

    public function __construct(
        UserCrudControllerInterface $userCrudController,
        CustomerCrudControllerInterface $customerCrudController
    ) {
        $this->userCrudController = $userCrudController;
        $this->customerCrudController = $customerCrudController;
    }

    /**
     * User List Action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $this->userCrudController->setAlias('u')
            ->readAllUndeletedEntities()
            ->join('idRoles', 'r')
            ->orderBy('login', 'ASC');

        $pagination = $this->get('knp_paginator')->paginate(
            $this->userCrudController->getQuery(),
            $request->query->getInt('page', 1),
            10
        );
		
		return $this->render('@UserManagement/User/list.html.twig', [
            'pagination' => $pagination
		]);
	}

    /**
     * User Show Action.
     *
     * @param $pathSlug
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function showAction($pathSlug)
    {
		$user = $this->get('user_crud_controller')
			->readOneEntityBy(['pathSlug' => $pathSlug])
            ->getResult();
		
//		$producer =
        $customer = null;

		// TODO: delete role??
//		if ($user->getIdRoles()->getCode() === 'ROLE_PRODUCER') {
//
//		}
		if ($user->getIdRoles()->getCode() === 'ROLE_CUSTOMER') {
			$customer = $this->get('customer_crud_controller')
				->readOneEntityBy(['idUsers' => $user])
                ->getResult();
		}

		$userSettings = $this->get('user_setting_crud_controller')
            ->readOneEntityBy(['idUsers' => $user])
            ->getResult();
		
		return $this->render('@UserManagement/User/show.html.twig', [
			'user' => $user,
//			'producer' => $producer,
			'customer' => $customer,
            'userSettings' => $userSettings,
		]);
	}
}