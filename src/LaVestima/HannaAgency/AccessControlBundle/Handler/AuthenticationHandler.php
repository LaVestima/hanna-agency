<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Handler;

use LaVestima\HannaAgency\AccessControlBundle\Controller\Crud\LoginAttemptCrudController;
use LaVestima\HannaAgency\AccessControlBundle\Entity\LoginAttempts;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper\SessionHelper;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudController;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserSettingCrudController;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationHandler implements
    AuthenticationSuccessHandlerInterface,
    AuthenticationFailureHandlerInterface
{
    private $loginAttemptCrudController;
    private $userCrudController;
    private $userSettingCrudController;
    private $sessionHelper;
    private $router;
    private $session;

    public function __construct(
        LoginAttemptCrudController $loginAttemptCrudController,
        UserCrudController $userCrudController,
        UserSettingCrudController $userSettingCrudController,
        SessionHelper $sessionHelper,
        Router $router,
        SessionInterface $session
    ) {
        $this->loginAttemptCrudController = $loginAttemptCrudController;
        $this->userCrudController = $userCrudController;
        $this->userSettingCrudController = $userSettingCrudController;
        $this->sessionHelper = $sessionHelper;
        $this->router = $router;
        $this->session = $session;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $loginAttempt = new LoginAttempts();

        $loginAttempt->setIpAdddress($request->getClientIp());
        $loginAttempt->setIsFailed(0);
        $loginAttempt->setIdUsers($token->getUser());

        $this->loginAttemptCrudController
            ->createEntity($loginAttempt);

        $this->sessionHelper->loadUserSettingsToSession($token->getUser());

        $this->session->getFlashBag()->add('success', 'Logged in!');

        return new RedirectResponse($this->router->generate('homepage_homepage'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $loginAttempt = new LoginAttempts();

        $user = $this->userCrudController
            ->readOneEntityBy([
                'login' => $exception->getToken()->getUser()
            ])
            ->getResult();

        $loginAttempt->setIpAdddress($request->getClientIp());
        $loginAttempt->setIsFailed(1);
        $loginAttempt->setIdUsers($user);

        $this->loginAttemptCrudController
            ->createEntity($loginAttempt);

        $this->session->getFlashBag()->add('error', 'Login failed!');

        return new RedirectResponse($this->router->generate('access_control_login'));
    }
}
