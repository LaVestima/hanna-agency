<?php

namespace App\Repository;

use App\Controller\Infrastructure\Crud\CrudRepository;
use App\Entity\Cart;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CartRepository extends CrudRepository
{
    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Cart::class, $tokenStorage);
    }

    public function getTotalSessionQuantity(string $sessionId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('sum(cpv.quantity)')
            ->join('c.cartProductVariants', 'cpv')
            ->where('c.sessionId = :sessionId')
            ->setParameters([
                'sessionId' => $sessionId
            ]);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
