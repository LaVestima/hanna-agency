<?php

namespace App\Repository;

use App\Controller\Infrastructure\Crud\CrudRepository;
use App\Entity\OrderStatus;
use App\Entity\Store;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StoreRepository extends CrudRepository
{
    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Store::class, $tokenStorage);
    }

    public function findOrdersByStatus(OrderStatus $orderStatus, Store $store)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('o')
            ->from('App\Entity\Order', 'o')
            ->join('s.products', 'p')
            ->join('p.productVariants', 'pv')
            ->join('pv.orderProductVariants', 'opv')
            ->where('s = :store')
            ->andWhere('opv.status = :status')
            ->setParameters([
                'store' => $store,
                'status' => $orderStatus,
            ]);

        return $qb->getQuery()->getResult();
    }
}
