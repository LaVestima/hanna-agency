<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use LaVestima\HannaAgency\AccessControlBundle\Entity\Tokens;
use LaVestima\HannaAgency\AccessControlBundle\Form\ResetPasswordType;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Request;

class ResetPasswordController extends BaseController {
    public function indexAction(string $resetPasswordToken, Request $request) {
        $token = $this->get('token_crud_controller')
            ->readOneEntityBy(['token' => $resetPasswordToken]);

        if ($token && $this->isTokenActive($token)) {
            $user = new Users();

            $form = $this->createForm(ResetPasswordType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $newPassword = $form->get('password')->getData();

                $user = $token->getIdUsers();

                $newPasswordHash = $this->get('security.password_encoder')
                    ->encodePassword($user, $newPassword);

                $this->get('user_crud_controller')
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

    private function isTokenActive(Tokens $token) {
        return $token->getDateExpired() > (new \DateTime('now'));
    }

    private function disableToken(Tokens $token) {
        $this->get('token_crud_controller')
            ->updateEntity($token, [
                'dateExpired' => (new \DateTime('now')),
            ]);
    }
}