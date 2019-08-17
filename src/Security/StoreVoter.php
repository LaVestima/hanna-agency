<?php


namespace App\Security;

use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class StoreVoter extends Voter
{
    private const APPLY = 'store_apply';
    private const VIEW_DASHBOARD = 'view_dashboard';

    protected function supports($attribute, $subject)
    {
        // TODO: Implement supports() method.

        if (!in_array($attribute, [
            self::APPLY,
            self::VIEW_DASHBOARD
        ], true)) {
            return false;
        }

//        if () {
//
//        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $store = $subject;

        switch ($attribute) {
            case self::APPLY:
                return $this->canApply($user);
            case self::VIEW_DASHBOARD:
                return $this->canViewDashboard($user);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canApply(User $user)
    {
        return $user->getStores()->isEmpty();
    }

    private function canViewDashboard(User $user)
    {
        return !$user->getStores()->isEmpty();
    }
}
