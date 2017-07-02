<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use LaVestima\HannaAgency\AccessControlBundle\Entity\Tokens;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;

class AccountActivationController extends BaseController {
	// TODO: add flashes
	public function indexAction(string $activationToken) {
	    $token = $this->get('token_crud_controller')
            ->readOneEntityBy(['token' => $activationToken]);

		if ($token) {
			if (!$this->isTokenActive($token)) {
			    $this->addFlash('error', 'Token expired!');
			}
			else {
				$user = $token->getIdUsers();
				$userRole = $this->get('role_crud_controller')
                    ->readOneEntityBy(['code' => 'ROLE_USER']);

				$user->setIdRoles($userRole);

				$this->disableToken($token);

				$this->addFlash('success', 'User account activated!');
			}
		}
		else {
            $this->addFlash('error', 'Wrong token!');
		}

        return $this->redirectToRoute('homepage_homepage');
	}

	private function disableToken(Tokens $token) {
	    $this->get('token_crud_controller')
            ->updateEntity($token, [
                'dateExpired' => (new \DateTime('now')),
            ]);
	}

    private function isTokenActive(Tokens $token) {
        return $token->getDateExpired() > (new \DateTime('now'));
    }
}