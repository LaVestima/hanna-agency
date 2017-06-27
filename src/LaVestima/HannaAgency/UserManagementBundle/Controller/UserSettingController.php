<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\UserManagementBundle\Form\UserSettingsType;
use Symfony\Component\HttpFoundation\Request;

class UserSettingController extends BaseController
{
    public function indexAction(Request $request)
    {
        $userSettings = $this->get('user_setting_crud_controller')
            ->readOneEntityBy(['idUsers' => $this->getUser()]);

        $form = $this->createForm(UserSettingsType::class, $userSettings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('user_setting_crud_controller')
                ->updateEntity($userSettings, $form->getData());

            $this->get('session_helper')->loadUserSettingsToSession($this->getUser());

            $this->addFlash('success', 'Settings updated!');
        }

        return $this->render('@UserManagement/UserSetting/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}