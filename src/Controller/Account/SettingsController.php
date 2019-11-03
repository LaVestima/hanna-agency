<?php

namespace App\Controller\Account;

use App\Controller\Infrastructure\BaseController;
use App\Form\AddressesType;
use App\Form\ChangePasswordType;
use App\Form\SettingsType;
use App\Form\UserInformationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/settings")
 */
class SettingsController extends BaseController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("", name="settings_index")
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $userInformationForm = $this->createForm(UserInformationType::class, $this->getUser());
        $userInformationForm->handleRequest($request);

        if ($userInformationForm->isSubmitted() && $userInformationForm->isValid()) {
            $em->persist($userInformationForm->getData());
            $em->flush();
        }

        $form = $this->createForm(SettingsType::class, $this->getUser()->getUserSettings()[0]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
        }

        $addressesForm = $this->createForm(AddressesType::class, $this->getUser());
        $addressesForm->handleRequest($request);

        if ($addressesForm->isSubmitted() && $addressesForm->isValid()) {
            $em->persist($addressesForm->getData());
            $em->flush();
        }

        $changePasswordForm = $this->createForm(ChangePasswordType::class);
        $changePasswordForm->handleRequest($request);

        if (
            $changePasswordForm->isSubmitted() &&
            $changePasswordForm->isValid() &&
            $this->passwordEncoder->isPasswordValid(
                $this->getUser(),
                $changePasswordForm->get('oldPassword')->getData()
            )
        ) {
            $user = $this->getUser();
            $user->setPasswordHash(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $changePasswordForm->get('newPassword')->getData()
                )
            );

            $em->persist($user);
            $em->flush();
        }

        return $this->render('Account/Settings/index.html.twig', [
            'userInformationForm' => $userInformationForm->createView(),
            'form' => $form->createView(),
            'addressesForm' => $addressesForm->createView(),
            'changePasswordForm' => $changePasswordForm->createView()
        ]);
    }
}
