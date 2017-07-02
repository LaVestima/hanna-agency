<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller {
	public function listAction() {
		$users = $this->get('user_crud_controller')
			->readAllEntities()->getEntities();
		
		return $this->render('@UserManagement/User/list.html.twig', [
			'users' => $users,
		]);
	}

	public function showAction($pathSlug) {
		$user = $this->get('user_crud_controller')
			->readOneEntityBy(['pathSlug' => $pathSlug]);
		
//		$producer =
        $customer = null;

		// TODO: delete role??
//		if ($user->getIdRoles()->getCode() === 'ROLE_PRODUCER') {
//
//		}
		if ($user->getIdRoles()->getCode() === 'ROLE_CUSTOMER') {
			$customer = $this->get('customer_crud_controller')
				->readOneEntityBy(['idUsers' => $user]);
		}

		$userSettings = $this->get('user_setting_crud_controller')
            ->readOneEntityBy(['idUsers' => $user]);
		
		return $this->render('@UserManagement/User/show.html.twig', [
			'user' => $user,
//			'producer' => $producer,
			'customer' => $customer,
            'userSettings' => $userSettings,
		]);
	}
}