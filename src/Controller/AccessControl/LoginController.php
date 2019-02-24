<?php

namespace App\Controller\AccessControl;

use App\Controller\Infrastructure\BaseController;
use App\Repository\LoginAttemptRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends BaseController
{
    const FAILED_LOGIN_MAX_ATTEMPTS = 3;
    const FAILED_LOGIN_RESET_TIME = 10;

    private $loginAttemptRepository;
    private $authenticationUtils;

    public function __construct(
        LoginAttemptRepository $loginAttemptRepository,
        AuthenticationUtils $authenticationUtils
    ) {
        $this->loginAttemptRepository = $loginAttemptRepository;
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * @Route("/login", name="access_control_login")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $ipAddress = $request->getClientIp();

        $authenticationUtils = $this->authenticationUtils;

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $loginDisabledMessage = null;

        if (count($this->getFailedLogins($ipAddress, self::FAILED_LOGIN_RESET_TIME)) >= self::FAILED_LOGIN_MAX_ATTEMPTS) {
            $loginDisabledMessage = 'Access Blocked. Too many failed login attempts. Please try again later!';
        }

        return $this->render('AccessControl/login.html.twig', [
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
     * @throws \Exception
     */
    private function getFailedLogins(string $ip, $minutes)
    {
        $failedLogins = $this->loginAttemptRepository
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
