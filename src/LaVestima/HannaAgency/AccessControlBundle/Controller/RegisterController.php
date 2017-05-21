<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use LaVestima\HannaAgency\AccessControlBundle\Entity\Tokens;
use LaVestima\HannaAgency\AccessControlBundle\Form\RegisterType;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper\CrudHelper;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Roles;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;
use RandomLib\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends Controller {
	private $activationToken;

	public function indexAction(Request $request) {
		$user = new Users();
		$form = $this->createForm(RegisterType::class, $user);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
//			$doctrine = $this->getDoctrine();
			$user = $form->getData();

			$passwordHash = $this->get('security.password_encoder')
				->encodePassword($user, $form->get('password')->getData());

			$user->setDateCreated(new \DateTime('now'));
			$user->setPasswordHash($passwordHash);

//			$defaultRole = $doctrine->getRepository('UserManagementBundle:Roles')
//				->findOneBy(['code' => 'ROLE_GUEST']);
			$defaultRole = $this->get('role_crud_controller')
				->readOneEntityBy(['code' => 'ROLE_GUEST']);

			$user->setIdRoles($defaultRole);
			$user->setPathSlug((new CrudHelper())->generatePathSlug());

			try {
				$this->get('user_crud_controller')
					->createEntity($user);
//				return $this->redirectToRoute('homepage_homepage');
			} catch (UniqueConstraintViolationException $e) {
				// TODO: add a flash
				var_dump('User with these credentials already exists!');
				var_dump($e->getMessage());
				die();
//				return $this->redirectToRoute('access_control_register');
				// TODO: redirect to error page
			}
			
			$this->setActivationToken($this->generateActivationToken());

			$token = new Tokens();
			$token->setIdUsers($user);
			$token->setDateCreated(new \DateTime('now'));
			$token->setDateExpired(new \DateTime('now +1 day'));
			$token->setToken($this->activationToken);

			$this->get('token_crud_controller')
				->createEntity($token);

			$this->sendActivationEmail($form->get('email')->getData());
			var_dump('Sent to '.$form->get('email')->getData());
		}

		return $this->render('AccessControlBundle:Register:index.html.twig', array(
			'form' => $form->createView()
		));
	}

	private function setActivationToken(string $activationToken) {
		$this->activationToken = $activationToken;

		return $this;
	}

	private function generateActivationToken() {
		$factory = new Factory();
		$generator = $factory->getMediumStrengthGenerator();
		$activationToken = $generator->generateString(100, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

		return $activationToken;
	}

	protected function sendActivationEmail($email) {
		$message = \Swift_Message::newInstance()
			->setSubject('Registration confirmation')
			->setFrom('lavestima@lavestima.com')
			->setTo($email)
			->setBcc('test@lavestima.com')
			->setBody($this->getActivationMessageBody(), 'text/html');
		$this->get('mailer')->send($message);
	}
	
//	protected function sendConfirmationEmail() {
//
//	}

	private function getActivationMessageBody() {
		$body  = 'User has just been registered with this email.' . '<br>';
		$body .= 'To confirm it click the following link:' . '<br>';
		$body .= '<a href="http://127.0.0.1:8000/app_dev.php/account_activation/';
		$body .= $this->activationToken;
		$body .= '">Confirm</a>';

		return $body;
	}
}