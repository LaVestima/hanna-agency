<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
	public function indexAction(Request $request)
    {
        $ipAddress = $request->getClientIp();

		$authenticationUtils = $this->get('security.authentication_utils');

		$error = $authenticationUtils->getLastAuthenticationError();

        if (count($this->getFailedLogins($ipAddress, 6)) > 2) {
            $error['message'] = 'Access Blocked. Please try again later!';
            throw new \Exception('Access Blocked. Too many failed login attempts. Please try again later!');
        } else {
            $error['message'] = 'ha';
        }

		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('AccessControlBundle:Login:index.html.twig', array(
			'last_username' => $lastUsername,
			'error'         => $error,
		));
	}

	private function getFailedLogins(string $ip, $hours)
    {
        $failedLogins = $this->get('login_attempt_crud_controller')
            ->readEntitiesBy([
                'ipAddress' => $ip,
                'dateCreated' => ['now -' . $hours . ' hours', '<'],
                'isFailed' => true,
            ])
            ->getResult();

        return $failedLogins;
    }
}