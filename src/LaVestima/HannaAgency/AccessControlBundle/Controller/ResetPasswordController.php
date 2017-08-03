<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use LaVestima\HannaAgency\AccessControlBundle\Controller\Crud\TokenCrudControllerInterface;
use LaVestima\HannaAgency\AccessControlBundle\Entity\Tokens;
use LaVestima\HannaAgency\AccessControlBundle\Form\ResetPasswordType;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudControllerInterface;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Request;

class ResetPasswordController extends BaseController
{
    private $tokenCrudController;
    private $userCrudController;

    /**
     * ResetPasswordController constructor.
     *
     * @param TokenCrudControllerInterface $tokenCrudController
     * @param UserCrudControllerInterface $userCrudController
     */
    public function __construct(
        TokenCrudControllerInterface $tokenCrudController,
        UserCrudControllerInterface $userCrudController
    ) {
        $this->tokenCrudController = $tokenCrudController;
        $this->userCrudController = $userCrudController;
    }

    /**
     * Reset Password Main Action.
     *
     * @param string $resetPasswordToken
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(string $resetPasswordToken, Request $request)
    {
        $token = $this->tokenCrudController
            ->readOneEntityBy(['token' => $resetPasswordToken])
            ->getResult();

        if ($token && $this->isTokenActive($token)) {
            $user = new Users();

            $form = $this->createForm(ResetPasswordType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $newPassword = $form->get('password')->getData();

                $user = $token->getIdUsers();

                // TODO: DI
                $newPasswordHash = $this->get('security.password_encoder')
                    ->encodePassword($user, $newPassword);

                $this->userCrudController
                    ->updateEntity($user, [
                        'passwordHash' => $newPasswordHash,
                    ]);

                $this->disableToken($token);

                $this->addFlash('success', 'Password changed!');

                return $this->redirectToRoute('access_control_login');
            }

            return $this->render('@AccessControl/ResetPassword/index.html.twig', [
                'form' => $form->createView()
            ]);
        }
        else {
            $this->addFlash('error', 'Wrong token!');

            return $this->redirectToRoute('homepage_homepage');
        }
    }

    /**
     * @param Tokens $token
     * @return bool
     */
    private function isTokenActive(Tokens $token)
    {
        return $token->getDateExpired() > (new \DateTime('now'));
    }

    /**
     * @param Tokens $token
     */
    private function disableToken(Tokens $token)
    {
        $this->tokenCrudController
            ->updateEntity($token, [
                'dateExpired' => (new \DateTime('now')),
            ]);
    }
}