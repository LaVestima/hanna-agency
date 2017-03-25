<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 22.03.17
 * Time: 21:33
 */

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use LaVestima\HannaAgency\AccessControlBundle\Entity\Tokens;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountActivationController extends Controller {
	// TODO: add flashes
	public function indexAction(string $activationToken) {
		$token = $this->getDoctrine()->getRepository('AccessControlBundle:Tokens')
			->findOneBy(['token' => $activationToken]);

		if ($token) {
			if ($token->getDateExpired() < (new \DateTime('now'))) {
				$message = 'Token expired';
			}
			else {
				$user = $token->getIdUsers();
				$userRole = $this->getDoctrine()->getRepository('UserManagementBundle:Roles')
					->findOneBy(['code' => 'ROLE_USER']);
				$user->setIdRoles($userRole);

				$this->disableToken($token);
				$em = $this->getDoctrine()->getManager();
				$em->flush();

				$message = 'User ok!';
			}
		}
		else {
			$message = 'Wrong token!';
		}

		return $this->render('@AccessControl/AccountActivation/index.html.twig', [
			'message' => $message
		]);
	}

	private function disableToken(Tokens $token) {
		$token->setDateExpired(new \DateTime('now'));
		$this->getDoctrine()->getManager()->flush();
	}


}