<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use LaVestima\HannaAgency\AccessControlBundle\Controller\Crud\TokenCrudControllerInterface;
use LaVestima\HannaAgency\AccessControlBundle\Form\ForgotPasswordType;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudControllerInterface;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Request;

class ForgotPasswordController extends BaseController
{
    private $userCrudController;
    private $tokenCrudController;

    /**
     * ForgotPasswordController constructor.
     *
     * @param TokenCrudControllerInterface $tokenCrudController
     */
    public function __construct(
        UserCrudControllerInterface $userCrudController,
        TokenCrudControllerInterface $tokenCrudController
    ) {
        $this->userCrudController = $userCrudController;
        $this->tokenCrudController = $tokenCrudController;
    }

    /**
     * Forgot Password Main Action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $user = new Users();

        $form = $this->createForm(ForgotPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user = $this->userCrudController
                ->readOneEntityBy(['email' => $form->get('email')->getData()])
                ->getResult()
            ) {
                $this->get('password_reset_email_sender')
                    ->sendEmail($user->getEmail());

                // TODO: finish

                return $this->redirectToRoute('access_control_email_sent');
            }
            else {
                $this->addFlash('warning', 'No user registered with this email!');
            }
        }

        return $this->render('@AccessControl/ForgotPassword/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Email Sent Action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function emailSentAction()
    {
        return $this->render('@AccessControl/ForgotPassword/emailSent.html.twig');
    }
}
