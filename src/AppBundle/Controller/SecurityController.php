<?php
/**
 * Created by PhpStorm.
 * User: Szymon
 * Date: 02-12-2016
 * Time: 21:24
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class SecurityController extends Controller {
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction(Request $request) {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

		if (!$error) {
			$this->addFlash('notice', 'Logged in!');
			$this->addFlash('noticeType', 'positive');
		}

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

	/**
	 * @Route("/register", name="security_register")
	 */
	public function registerAction(Request $request) {
		$user = new Users();
		$form = $this->createForm(UsersType::class, $user);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$password = $this->get('security.password_encoder')
				->encodePassword($user, $user->getPlainPassword());
			$user->setPasswordHash($password);
			$dateCreated = new \DateTime("now");
			$user->setDateCreated($dateCreated);
			$user->setIsAdmin(0);

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			$this->addFlash('notice', 'Registered successfully!');
			$this->addFlash('noticeType', 'positive');

			// add some other action

			return $this->redirectToRoute('homepage');
		}

		return $this->render('security/register.html.twig', array(
			'form' => $form->createView()
		));
	}
}