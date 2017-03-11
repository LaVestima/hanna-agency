<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 11.03.17
 * Time: 10:24
 */

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoginController extends Controller
{
	public function indexAction() {
		$authenticationUtils = $this->get('security.authentication_utils');

		$error = $authenticationUtils->getLastAuthenticationError();

		$lastUsername = $authenticationUtils->getLastUsername();

		if (!$error) {
//			$this->addFlash('notice', 'Logged in!');
//			$this->addFlash('noticeType', 'positive');
		}

		return $this->render('AccessControlBundle:Login:index.html.twig', array(
			'last_username' => $lastUsername,
			'error'         => $error,
		));
	}
}