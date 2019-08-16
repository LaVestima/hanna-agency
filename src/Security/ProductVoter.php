<?php

namespace App\Security;

use App\Entity\Product;
use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create_product';
    const DELETE = 'delete';

    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::CREATE, self::DELETE], true)) {
            return false;
        }

        if ((!$subject instanceof Product) && ($attribute !== self::CREATE)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $product = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($product, $user);
            case self::EDIT:
                return $this->canEdit($product, $user);
            case self::CREATE:
                return $this->canCreate($user);
            case self::DELETE:
                return $this->canDelete();
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canView(Product $product, User $user): bool
    {
        if ($this->canEdit($product, $user)) { return true; }

        if (!$product->getActive() && !$user->getStores()->contains($product->getStore())) {
            return false;
        }

        return true;
    }

    private function canEdit(Product $product, User $user): bool
    {
        // TODO: other condition, including store moderator
        return $user === $product->getStore()->getOwner();
    }

    private function canCreate(User $user): bool
    {
        return !$user->getStores()->isEmpty();
    }

    private function canDelete()
    {
        // TODO: finish

        // TODO: more restrictive than EDIT, only store owner(?)
    }
}
