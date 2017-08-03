<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use LaVestima\HannaAgency\AccessControlBundle\Controller\Crud\TokenCrudControllerInterface;
use LaVestima\HannaAgency\AccessControlBundle\Entity\Tokens;
use LaVestima\HannaAgency\AccessControlBundle\Form\ForgotPasswordType;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudControllerInterface;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;
use RandomLib\Factory;
use Symfony\Component\HttpFoundation\Request;

class ForgotPasswordController extends BaseController
{
    private $userCrudController;
    private $tokenCrudController;

    private $baseUrl;
    private $user;
    private $newPasswordToken;

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
                $this->user = $user;

                $this->baseUrl = $request->getSchemeAndHttpHost();

                $this->createNewPasswordToken();
                $this->sendNewPasswordEmail();

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function emailSentAction()
    {
        return $this->render('@AccessControl/ForgotPassword/emailSent.html.twig');
    }

    /**
     *
     */
    private function createNewPasswordToken()
    {
        $this->newPasswordToken = $this->generateNewPasswordToken();

        $token = new Tokens();
        $token->setIdUsers($this->user);
        $token->setToken($this->newPasswordToken);

        $this->tokenCrudController
            ->createEntity($token);
    }

    /**
     *
     */
    private function sendNewPasswordEmail()
    {
        $email = $this->user->getEmail();

        $message = \Swift_Message::newInstance()
            ->setSubject('Reset Password')
            ->setFrom('lavestima@lavestima.com')
            ->setTo($email)
            ->setBcc('test@lavestima.com')
            ->setBody($this->getNewPasswordEmailBody(), 'text/html');
        $this->get('mailer')->send($message);
    }

    /**
     * @return string
     */
    private function getNewPasswordEmailBody()
    {
        $body  = 'To reset the password, click on the link below:<br>';
        $body .= '<a href="' . $this->baseUrl . '/reset_password/';
        $body .= $this->newPasswordToken;
        $body .= '">Reset password</a>';

        return $body;
    }

    /**
     * @return string
     */
    private function generateNewPasswordToken()
    {
        $factory = new Factory();
        $generator = $factory->getMediumStrengthGenerator();
        $activationToken = $generator->generateString(100, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        return $activationToken;
    }
}