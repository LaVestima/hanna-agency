<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper;

use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserSettingCrudController;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionHelper
{
    private $userSettingCrudController;
    private $session;

    public function __construct(
        UserSettingCrudController $userSettingCrudController,
        SessionInterface $session
    ) {
        $this->userSettingCrudController = $userSettingCrudController;
        $this->session = $session;
    }

    public function loadUserSettingsToSession(Users $user)
    {
        // TODO: finish !!!

        $userSettings = $this->userSettingCrudController
            ->readOneEntityBy([
                'idUsers' => $user
            ])->getResult();

        $this->session->set('configuration', [
            'locale' => $userSettings->getLocale(),
            // TODO: more !!!
        ]);
    }
}