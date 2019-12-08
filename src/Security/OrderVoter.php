<?php

namespace App\Security;

use App\Entity\Order;
use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class OrderVoter extends Voter
{
    const LIST_VIEW = 'order_list_view';
    const VIEW = 'order_view';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

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
        $store = $order->getOrderProductVariants()[0]->getProductVariant()->getProduct()->getStore();

        foreach ($user->getStoreSubusers() as $storeSubuser) {
            if ($store->getStoreSubusers()->contains($storeSubuser) && $this->security->isGranted('ROLE_ORDER_VIEW')) {
                return true;
            }
        }

        if ($order->getUser() !== $user) {
            return false;
        }

        return true;
    }
}
