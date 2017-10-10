<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    const FAILED_LOGIN_MAX_ATTEMPTS = 3;
    const FAILED_LOGIN_RESET_TIME = 10;

    /**
     * Login Index Action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
	public function indexAction(Request $request)
    {
        $ipAddress = $request->getClientIp();

		$authenticationUtils = $this->get('security.authentication_utils');

		$error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $loginDisabledMessage = null;

        if (count($this->getFailedLogins($ipAddress, self::FAILED_LOGIN_RESET_TIME)) >= self::FAILED_LOGIN_MAX_ATTEMPTS) {
            $loginDisabledMessage = 'Access Blocked. Too many failed login attempts. Please try again later!';
        }

		return $this->render('AccessControlBundle:Login:index.html.twig', [
			'last_username' => $lastUsername,
			'error'         => $error,
            'loginDisabled' => $loginDisabledMessage ? ['message' => $loginDisabledMessage] : null,
		]);
	}

    /**
     * Get the array of failed logins from given IP, within given minutes.
     *
     * @param string $ip
     * @param $minutes
     *
     * @return array
     */
	private function getFailedLogins(string $ip, $minutes)
    {
        $failedLogins = $this->get('login_attempt_crud_controller')
            ->readEntitiesBy([
                'ipAddress' => $ip,
                'dateCreated' => [(new \DateTime('now -' . $minutes . ' minutes')), '>'],
                'isFailed' => true,
            ])
            ->getResult();

        if (!is_array($failedLogins)) {
            $failedLogins = [$failedLogins];
        }

        return $failedLogins;
    }
}