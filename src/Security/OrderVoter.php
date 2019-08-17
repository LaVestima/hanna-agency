<?php

namespace App\Security;

use App\Entity\Order;
use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OrderVoter extends Voter
{
    const LIST_VIEW = 'order_list_view';
    const VIEW = 'order_view';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::LIST_VIEW, self::VIEW], true)) {
            return false;
        }

        if ((!$subject instanceof Order) && ($attribute != self::LIST_VIEW)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $order = $subject;

        switch ($attribute) {
            case self::LIST_VIEW:
                return $this->canViewList($user);
            case self::VIEW:
                return $this->canView($user, $order);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canViewList(User $user)
    {
        return true;
    }

    private function canView(User $user, Order $order)
    {
//        if ($order->get) {
//            return true;
//        }

        if ($order->getUser() !== $user) {
            return false;
        }

        // TODO: ADD: store subuser with permission to view store orders

        return true;
    }
}
