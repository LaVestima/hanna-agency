<?php

namespace App\Repository;

use App\Controller\Infrastructure\Crud\CrudRepository;
use App\Entity\Store;
use App\Entity\StoreOpinion;
use App\Helper\CrudHelper;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\Order;

class StoreRepository extends CrudRepository
{
    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Store::class, $tokenStorage);
    }

    public function createEntity($entity)
    {
        $entity->setIdentifier(CrudHelper::generatePathSlug($entity->getFullName()));

        parent::createEntity($entity);
    }

    public function findTop(int $count)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s AS store')
            ->addSelect('AVG(so.rating) AS averageRating')
            ->join(StoreOpinion::class, 'so', 'WITH', 'so.store = s.id')
            ->where('s.active = true')
            ->groupBy('s.id')
            ->orderBy('AVG(so.rating)', 'DESC')
            ->setMaxResults($count);

        return $qb->getQuery()->getResult();
    }

    public function findOrdersByStatus(string $orderStatus, Store $store)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('o')
            ->from(Order::class, 'o')
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
