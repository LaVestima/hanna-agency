<?php

namespace App\Controller\Account;

use App\Controller\Infrastructure\BaseController;
use App\Form\ChangePasswordType;
use App\Form\SettingsType;
use App\Form\UserInformationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings")
 */
class SettingsController extends BaseController
{
    /**
     * @Route("", name="settings_index")
     */
    public function index(Request $request)
    {
        // TODO: access control: only logged in

        $userInformationForm = $this->createForm(UserInformationType::class, $this->getUser());

        $form = $this->createForm(SettingsType::class);
        $form->handleRequest($request);

        $changePasswordForm = $this->createForm(ChangePasswordType::class);

        return $this->render('Account/Settings/index.html.twig', [
            'userInformationForm' => $userInformationForm->createView(),
            'form' => $form->createView(),
            'changePasswordForm' => $changePasswordForm->createView()
        ]);
    }
}
