<?php

namespace App\Controller\AccessControl;


use App\Controller\Infrastructure\BaseController;
use App\Entity\Token;
use App\Entity\User;
use App\Entity\UserSetting;
use App\Form\RegisterType;
use App\Repository\RoleRepository;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Repository\UserSettingRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use RandomLib\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends BaseController
{
    private $roleRepository;
    private $tokenRepository;
    private $userRepository;
    private $userSettingRepository;

    private $baseUrl;
    private $activationToken;

    public function __construct(
        RoleRepository $roleRepository,
        TokenRepository $tokenRepository,
        UserRepository $userRepository,
        UserSettingRepository $userSettingRepository
    ) {
        $this->roleRepository = $roleRepository;
        $this->tokenRepository = $tokenRepository;
        $this->userRepository = $userRepository;
        $this->userSettingRepository = $userSettingRepository;
    }

    /**
     * @Route("/register", name="access_control_register")
     */
    public function index(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $passwordHash = $this->get('security.password_encoder')
                ->encodePassword($user, $form->get('password')->getData());

            $user->setPasswordHash($passwordHash);

            $defaultRole = $this->roleRepository
                ->readOneEntityBy(['code' => 'ROLE_GUEST'])
                ->getResult();

            $user->setIdRoles($defaultRole);

            try {
                $this->userRepository
                    ->createEntity($user);

                $defaultUsersSettings = new UserSetting();

                $defaultUsersSettings->setIdUsers($user);

                $this->userSettingRepository
                    ->createEntity($defaultUsersSettings);
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'User with these credentials already exists!');

                return $this->redirectToRoute('access_control_register');
            }

            $this->setActivationToken($this->generateActivationToken());

            $token = new Token();
            $token->setIdUsers($user);
            $token->setToken($this->activationToken);

            $this->tokenRepository
                ->createEntity($token);

            $this->baseUrl = $request->getSchemeAndHttpHost();

            $this->sendActivationEmail($form->get('email')->getData());

            return $this->render('AccessControl/register_summary.html.twig', [
                'email' => $form->get('email')->getData()
            ]);
        }

        return $this->render('AccessControl/register.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function setActivationToken(string $activationToken)
    {
        $this->activationToken = $activationToken;

        return $this;
    }

    private function generateActivationToken()
    {
        $factory = new Factory();
        $generator = $factory->getMediumStrengthGenerator();
        $activationToken = $generator->generateString(100, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        return $activationToken;
    }

    protected function sendActivationEmail($email)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Registration confirmation')
            ->setFrom('lavestima@lavestima.com')
            ->setTo($email)
            ->setBcc('test@lavestima.com')
            ->setBody($this->getActivationMessageBody(), 'text/html');
        $this->get('mailer')->send($message);
    }

    // TODO: correct message
    private function getActivationMessageBody()
    {
        $body  = 'User has just been registered with this email.' . '<br>';
        $body .= 'To confirm it click the following link:' . '<br>';
        $body .= '<a href="' . $this->baseUrl . '/account_activation/';
        $body .= $this->activationToken;
        $body .= '">Confirm</a>';

        return $body;
    }
}