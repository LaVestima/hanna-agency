<?php

namespace App\Repository;

use App\Controller\Infrastructure\Crud\CrudRepository;
use App\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductRepository extends CrudRepository
{
    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Product::class, $tokenStorage);
    }

    public function readRecommendedProducts(User $user)
    {
        // TODO: read order history, search products similar to those in orders
        // (by some parameters, producers, etc)
        // TODO: some machine learning?
    }

    public function getProductsBySearchQuery(string $searchQuery, $priceMin, $priceMax, $sorting)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.name LIKE :param_query')
            ->andWhere('p.price > ?1')
            ->andWhere('p.price < ?2')
            ->setParameters([
                'param_query' => '%' . $searchQuery . '%',
                1 => $priceMin,
                2 => $priceMax,
            ]);

        switch ($sorting) {
            case 'priceAsc':
                $qb->orderBy('p.price', 'ASC');
                break;
            case 'priceDesc':
                $qb->orderBy('p.price', 'DESC');
                break;
            case 'mostRelevant':
            default:
                // TODO: sort by the most relevant
        }

        return $qb->getQuery()->getResult();
    }
}
