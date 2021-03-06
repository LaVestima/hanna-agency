<?php

namespace App\Helper;

use App\Entity\User;
use App\Repository\UserSettingRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionHelper
{
    private $userSettingRepository;
    private $session;

    /**
     * SessionHelper constructor.
     *
     * @param UserSettingRepository $userSettingRepository
     * @param SessionInterface $session
     */
    public function __construct(
        UserSettingRepository $userSettingRepository,
        SessionInterface $session
    ) {
        $this->userSettingRepository = $userSettingRepository;
        $this->session = $session;
    }

    /**
     * @param User $user
     */
    public function loadUserSettingsToSession(User $user)
    {
        // TODO: finish !!!

        $userSettings = $this->userSettingRepository
            ->readOneEntityBy([
                'user' => $user
            ])->getResult();

        $this->session->set('configuration', [
            'locale' => $userSettings ? $userSettings->getLocale() : 'en',
            // TODO: more !!!
        ]);
    }
}
