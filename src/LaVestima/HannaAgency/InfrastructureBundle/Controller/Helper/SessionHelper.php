<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper;

use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserSettingCrudControllerInterface;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionHelper
{
    private $userSettingCrudController;
    private $session;

    /**
     * SessionHelper constructor.
     *
     * @param UserSettingCrudControllerInterface $userSettingCrudController
     * @param SessionInterface $session
     */
    public function __construct(
        UserSettingCrudControllerInterface $userSettingCrudController,
        SessionInterface $session
    ) {
        $this->userSettingCrudController = $userSettingCrudController;
        $this->session = $session;
    }

    /**
     * @param Users $user
     */
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