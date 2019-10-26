<?php

namespace App\Controller\AccessControl;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Token;
use App\Entity\User;
use App\Entity\UserSetting;
use App\Form\RegisterType;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Repository\UserSettingRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use RandomLib\Factory;
use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends BaseController
{
    /** @var MailerInterface */
    private $mailer;
    private $userPasswordEncoder;

    private $tokenRepository;
    private $userRepository;
    private $userSettingRepository;

    private $baseUrl;
    private $activationToken;

    public function __construct(
        MailerInterface $mailer,
        UserPasswordEncoderInterface $userPasswordEncoder,
        TokenRepository $tokenRepository,
        UserRepository $userRepository,
        UserSettingRepository $userSettingRepository
    ) {
        $this->mailer = $mailer;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->tokenRepository = $tokenRepository;
        $this->userRepository = $userRepository;
        $this->userSettingRepository = $userSettingRepository;
    }

    /**
     * @Route("/register", name="access_control_register")
     */
    public function index(Request $request, ReCaptcha $reCaptcha)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resp = $reCaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                ->verify($request->request->get('g-recaptcha-response'), $_SERVER['REMOTE_ADDR']);

            if (!$resp->isSuccess()) {
                $this->addFlash('error', 'ReCAPTCHA error! Try again.');

                return $this->redirectToRoute('access_control_register');
            }

            $user = $form->getData();

            $user->setPasswordHash(
                $this->userPasswordEncoder
                    ->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
            );

            try {
                $this->userRepository
                    ->createEntity($user);

                $defaultUsersSettings = new UserSetting();

                $defaultUsersSettings->setUser($user);

                $this->userSettingRepository
                    ->createEntity($defaultUsersSettings);
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'User with these credentials already exists!');

                return $this->redirectToRoute('access_control_register');
            }

            $this->activationToken = $this->generateActivationToken();

            $token = new Token();
            $token->setUser($user);
            $token->setToken($this->activationToken);
            $token->setDateExpired(new \DateTime('now +1 day'));

            $this->tokenRepository
                ->createEntity($token);

            $this->baseUrl = $request->getSchemeAndHttpHost();

            $this->sendActivationEmail($form->get('email')->getData());

            return $this->render('AccessControl/register_summary.html.twig', [
                'email' => $form->get('email')->getData()
            ]);
        }

        return $this->render('AccessControl/registration.html.twig', [
            'form' => $form->createView(),
            'google_recaptcha_site_key' => $_ENV['GOOGLE_RECAPTCHA_SITE_KEY']
        ]);
    }

    private function generateActivationToken()
    {
        $factory = new Factory();
        $generator = $factory->getMediumStrengthGenerator();
        $activationToken = $generator->generateString(100, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        return $activationToken;
    }

    protected function sendActivationEmail($emailAddress)
    {
        $email = new Email();
        $email->from('lavestima@lavestima.com')
            ->to($emailAddress)
            ->bcc('lavestima@lavestima.com')
//            ->priority(Email::PRIORITY_HIGH)
            ->subject('EuBuy - Registration confirmation')
//            ->text($this->getActivationMessageBody())
            ->html($this->getActivationMessageBody());

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // TODO: show error and log
        }
    }

    // TODO: correct message
    private function getActivationMessageBody()
    {
        $body  = 'User has just been registered with this email.' . '<br>';
        $body .= 'To confirm it click the following button (active for 24h):' . '<br>';
        $body .= '<a href="' . $this->baseUrl . '/account_activation/';
        $body .= $this->activationToken;
        $body .= '">Confirm</a>';

        return $body;
    }
}
