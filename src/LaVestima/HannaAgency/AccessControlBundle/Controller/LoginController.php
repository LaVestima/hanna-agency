<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use LaVestima\HannaAgency\OrderBundle\Entity\Orders;
use LaVestima\HannaAgency\ProductBundle\Entity\Sizes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
	public function indexAction(Request $request)
    {
	    // TODO: add limit for number of failed logins during some time
		$authenticationUtils = $this->get('security.authentication_utils');

		$error = $authenticationUtils->getLastAuthenticationError();

		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('AccessControlBundle:Login:index.html.twig', array(
			'last_username' => $lastUsername,
			'error'         => $error,
		));
	}
}