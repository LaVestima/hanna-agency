<?php

namespace App\Repository;

use App\Controller\Infrastructure\Crud\CrudRepository;
use App\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductRepository extends CrudRepository
{
    private $categoryRepository;

    public function __construct(
        ManagerRegistry $registry,
        TokenStorageInterface $tokenStorage,
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;

        parent::__construct($registry, Product::class, $tokenStorage);
    }

    public function readRecommendedProducts(User $user)
    {
        // TODO: read order history, search products similar to those in orders
        // (by some parameters, producers, etc)
        // TODO: some machine learning?
    }

    public function getProductsByAdvancedSearch($searchQuery, $categoryIdentifier, $priceMin = 0, $priceMax = 999999999999, $sorting = null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.name LIKE :paramQuery')
            ->andWhere('p.category = :paramCategory')
            ->andWhere('p.price >= :paramPriceMin')
            ->andWhere('p.price <= :paramPriceMax')
            ->andWhere('p.active = 1')
            ->setParameters([
                'paramQuery' => '%' . $searchQuery . '%',
                'paramCategory' => $this->categoryRepository->findOneBy([
                    'identifier' => $categoryIdentifier
                ]),
                'paramPriceMin' => $priceMin,
                'paramPriceMax' => $priceMax,
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
