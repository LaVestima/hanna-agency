<?php

namespace App\Repository;

use App\Entity\CartProductVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CartProductVariant|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartProductVariant|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartProductVariant[]    findAll()
 * @method CartProductVariant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartProductVariantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CartProductVariant::class);
    }

    // /**
    //  * @return CartProductVariant[] Returns an array of CartProductVariant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CartProductVariant
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
