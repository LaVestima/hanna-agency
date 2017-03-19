<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use LaVestima\HannaAgency\AccessControlBundle\Form\RegisterType;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Roles;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends Controller {
	public function indexAction(Request $request) {
		$user = new Users();
		$form = $this->createForm(RegisterType::class, $user);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$doctrine = $this->getDoctrine();
			$user = $form->getData();

			$passwordHash = $this->get('security.password_encoder')
				->encodePassword($user, $form->get('password')->getData());

			$user->setDateCreated(new \DateTime('now'));
			$user->setPasswordHash($passwordHash);
			$defaultRole = $doctrine->getRepository('UserManagementBundle:Roles')
				->findOneBy(['code' => 'ROLE_USER']);
			// TODO: first ROLE_GUEST then when authenticated through he email ROLE_USER

			$user->setIdRoles($defaultRole);

			$em = $doctrine->getManager();
			$em->persist($user);
			try {
				$em->flush();
//				return $this->redirectToRoute('homepage_homepage');
			} catch (UniqueConstraintViolationException $e) {
				// TODO: add a flash
				var_dump('User with these credentials already exists!');
				die();
			} finally {
				$message = \Swift_Message::newInstance()
					->setSubject('Registration confirmation')
					->setFrom('lavestima@lavestima.com')
					->setTo($form->get('email')->getData())
//					->setTo('test@lavestima.com')
//					->setBody('aaaaaaaa');
					->setBody('
						User has just been registered with this email.<br>
						To confirm it click the following link:<br>
						<a href="http://127.0.0.1:8000/app_dev.php/homepage">Confirm</a>
					', 'text/html');
				$this->get('mailer')->send($message);
				var_dump('Sent to '.$form->get('email')->getData());
//				die();
			}
		}

		return $this->render('AccessControlBundle:Register:index.html.twig', array(
			'form' => $form->createView()
		));
	}
}