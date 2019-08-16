<?php

namespace App\Repository;

use App\Controller\Infrastructure\Crud\CrudRepository;
use App\Entity\OrderProductVariant;
use App\Entity\Store;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderProductVariantRepository extends CrudRepository
{
    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, OrderProductVariant::class, $tokenStorage);
    }

    public function getByStore(Store $store)
    {
        $qb = $this->createQueryBuilder('opv')
            ->join('opv.productVariant', 'pv')
            ->join('pv.product', 'p')
            ->where('p.store = :store')
            ->setParameters([
                'store' => $store
            ]);

        return $qb->getQuery()->getResult();
    }
}
