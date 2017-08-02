<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use LaVestima\HannaAgency\AccessControlBundle\Entity\Tokens;
use LaVestima\HannaAgency\AccessControlBundle\Form\RegisterType;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;
use LaVestima\HannaAgency\UserManagementBundle\Entity\UsersSettings;
use RandomLib\Factory;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends BaseController
{
    private $baseUrl;
	private $activationToken;

	public function indexAction(Request $request)
    {
		$user = new Users();
		$form = $this->createForm(RegisterType::class, $user);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$user = $form->getData();

			$passwordHash = $this->get('security.password_encoder')
				->encodePassword($user, $form->get('password')->getData());

			$user->setPasswordHash($passwordHash);

			$defaultRole = $this->get('role_crud_controller')
				->readOneEntityBy(['code' => 'ROLE_GUEST'])
                ->getResult();

			$user->setIdRoles($defaultRole);

			try {
				$this->get('user_crud_controller')
					->createEntity($user);

				$defaultUsersSettings = new UsersSettings();

				$defaultUsersSettings->setIdUsers($user);

                $this->get('user_setting_crud_controller')
                    ->createEntity($defaultUsersSettings);
			} catch (UniqueConstraintViolationException $e) {
			    $this->addFlash('error', 'User with these credentials already exists!');

				return $this->redirectToRoute('access_control_register');
			}
			
			$this->setActivationToken($this->generateActivationToken());

			$token = new Tokens();
			$token->setIdUsers($user);
			$token->setToken($this->activationToken);

			$this->get('token_crud_controller')
				->createEntity($token);

			$this->baseUrl = $request->getSchemeAndHttpHost();

			$this->sendActivationEmail($form->get('email')->getData());

            return $this->render('@AccessControl/Register/summary.html.twig', [
                'email' => $form->get('email')->getData()
            ]);
		}

		return $this->render('AccessControlBundle:Register:index.html.twig', array(
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