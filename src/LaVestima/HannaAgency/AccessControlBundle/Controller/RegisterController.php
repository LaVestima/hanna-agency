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

		if ($form->isValid()) {
			$doctrine = $this->getDoctrine();
			$user = $form->getData();

			$passwordHash = $this->get('security.password_encoder')
				->encodePassword($user, $form->get('password')->getData());

			$user->setDateCreated(new \DateTime('now'));
			$user->setPasswordHash($passwordHash);
			$defaultRole = $doctrine->getRepository('UserManagementBundle:Roles')
				->findOneBy(['code' => 'ROLE_GUEST']);

			$user->setIdRoles($defaultRole);

			$em = $doctrine->getManager();
			$em->persist($user);
			try {
				$em->flush();
			} catch (UniqueConstraintViolationException $e) {
				// TODO: add a flash
				var_dump('User with these credentials already exists!');
				die();
			}
		}

		return $this->render('AccessControlBundle:Register:index.html.twig', array(
			'form' => $form->createView()
		));
	}
}