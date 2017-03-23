<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 22.03.17
 * Time: 21:33
 */

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountActivationController extends Controller {
	public function indexAction(string $activationToken) {
		$token = $this->getDoctrine()->getRepository('AccessControlBundle:Tokens')
			->findOneBy(['token' => $activationToken]);

		if ($token) {
			$user = $token->getIdUsers();
			$user->setIdRoles($this->getDoctrine()
				->getRepository('UserManagementBundle:Roles')
				->findOneBy(['code' => 'ROLE_USER'])
			);
			
			$em = $this->getDoctrine()->getManager();
			$token->setDateExpired(new \DateTime('now'));
			$em->flush();

			$message = 'User ok!';
		}
		else {
			$message = 'Wrong token!';
		}

		return $this->render('@AccessControl/AccountActivation/index.html.twig', [
			'message' => $message
		]);
	}
}