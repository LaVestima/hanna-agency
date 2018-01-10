<?php

namespace LaVestima\HannaAgency\MessageBundle\Sender;

use LaVestima\HannaAgency\MessageBundle\Sender\Helper\PasswordResetEmailHelper;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudControllerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PasswordResetEmailSender
{
    private $requestStack;
    private $mailer;
    private $userCrudController;
    private $passwordResetEmailHelper;

    private $user;

    /**
     * PasswordResetEmailSender constructor.
     *
     * @param RequestStack $requestStack
     * @param \Swift_Mailer $mailer
     * @param UserCrudControllerInterface $userCrudController
     * @param PasswordResetEmailHelper $passwordResetEmailHelper
     */
    public function __construct(
        RequestStack $requestStack,
        \Swift_Mailer $mailer,
        UserCrudControllerInterface $userCrudController,
        PasswordResetEmailHelper $passwordResetEmailHelper
    ) {
        $this->requestStack = $requestStack;
        $this->mailer = $mailer;
        $this->userCrudController = $userCrudController;
        $this->passwordResetEmailHelper = $passwordResetEmailHelper;
    }

    /**
     * Send Password Reset Email.
     *
     * @param string $email
     */
    public function sendEmail(string $email)
    {
        $this->user = $this->userCrudController
            ->readOneEntityBy([
                'email' => $email
            ])
            ->getResult();

        $message = \Swift_Message::newInstance()
            ->setSubject('Reset Password')
            ->setFrom('lavestima@lavestima.com')
            ->setTo($email)
            ->setBcc('test@lavestima.com')
            ->setBody($this->getEmailBody(), 'text/html');
        $this->mailer->send($message);
    }

    /**
     * Get Password Reset Email Body.
     *
     * @return string
     */
    private function getEmailBody()
    {
        $baseUrl = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $passwordResetToken = $this->passwordResetEmailHelper
            ->setUser($this->user)
            ->createToken();

        $body  = 'To reset the password, click on the link below:<br>';
        $body .= '<a href="' . $baseUrl . '/reset_password/';
        $body .= $passwordResetToken;
        $body .= '">Reset password</a>';

        return $body;
    }
}
