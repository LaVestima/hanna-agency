<?php

namespace App\Handler;

use App\Entity\LoginAttempt;
use App\Helper\SessionHelper;
use App\Repository\LoginAttemptRepository;
use App\Repository\UserRepository;
use App\Repository\UserSettingRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
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

    /**
     * AuthenticationHandler constructor.
     *
     * @param LoginAttemptRepository $loginAttemptRepository
     * @param UserRepository $userRepository
     * @param UserSettingRepository $userSettingRepository
     * @param SessionHelper $sessionHelper
     * @param RouterInterface $router
     * @param SessionInterface $session
     */
    public function __construct(
        LoginAttemptRepository $loginAttemptRepository,
        UserRepository $userRepository,
        UserSettingRepository $userSettingRepository,
        SessionHelper $sessionHelper,
        RouterInterface $router,
        SessionInterface $session
    ) {
        $this->loginAttemptCrudController = $loginAttemptRepository;
        $this->userCrudController = $userRepository;
        $this->userSettingCrudController = $userSettingRepository;
        $this->sessionHelper = $sessionHelper;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * Event After Successful Login.
     *
     * @param Request $request
     * @param TokenInterface $token
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
//        var_dump("yay");die;
        $loginAttempt = new LoginAttempt();

        $loginAttempt->setIpAddress($request->getClientIp());
        $loginAttempt->setIsFailed(0);
        $loginAttempt->setIdUsers($token->getUser());

        $this->loginAttemptCrudController
            ->createEntity($loginAttempt);

        $this->sessionHelper->loadUserSettingsToSession($token->getUser());

        $this->session->getFlashBag()->add('success', 'Logged in!');

        return new RedirectResponse($this->router->generate('homepage_homepage'));
    }

    /**
     * Event After Failed Login.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return RedirectResponse
     * @throws \Exception
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
//        var_dump("no");die;
        $loginAttempt = new LoginAttempt();

        $user = $this->userCrudController
            ->readOneEntityBy([
                'login' => $exception->getToken()->getUser()
            ])
            ->getResult();

        $loginAttempt->setIpAddress($request->getClientIp());
        $loginAttempt->setIsFailed(1);
        $loginAttempt->setIdUsers($user);

        $this->loginAttemptCrudController
            ->createEntity($loginAttempt);

        $this->session->getFlashBag()->add('error', 'Login failed!');

        return new RedirectResponse($this->router->generate('access_control_login'));
    }
}
