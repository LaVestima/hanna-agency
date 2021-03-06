<?php

namespace App\Repository;

use App\Entity\ParameterCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ParameterCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParameterCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParameterCategory[]    findAll()
 * @method ParameterCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParameterCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParameterCategory::class);
    }

    // /**
    //  * @return ParameterCategory[] Returns an array of ParameterCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParameterCategory
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
