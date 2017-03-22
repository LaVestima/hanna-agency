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
		var_dump($activationToken);

		return $this->render('@AccessControl/AccountActivation/index.html.twig');
	}
}