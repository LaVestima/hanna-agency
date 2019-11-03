<?php

namespace App\Repository;

use App\Controller\Infrastructure\Crud\CrudRepository;
use App\Entity\MLModel;
use App\Entity\Product;
use App\Entity\User;
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

    public function readRecommendedProducts(MLModel $model)
    {
        $qb = $this->createQueryBuilder('p');

        $parameters = [];

        foreach ($model->getContent() as $i => $productId) {
            $qb->orWhere('p.id = :productId' . $i);

            $parameters[('productId' . $i)] = $productId;
        }

        $qb->setParameters($parameters);

        return $qb->getQuery()->getResult();

        // TODO: read order history, search products similar to those in orders
        // (by some parameters, producers, etc)
        // TODO: some machine learning?
    }

    public function getProductsQueryByAdvancedSearch($searchQuery, $categoryIdentifier, $priceMin = 0, $priceMax = 99999999999, $sorting = null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.name LIKE :paramQuery')
            ->andWhere('p.price >= :paramPriceMin')
            ->andWhere('p.price <= :paramPriceMax')
            ->andWhere('p.active = 1');

        $parameters = [
            'paramQuery' => '%' . $searchQuery . '%',
            'paramPriceMin' => $priceMin,
            'paramPriceMax' => $priceMax,
        ];

        if ($categoryIdentifier !== '') {
            $qb->andWhere('p.category = :paramCategory');

            $parameters['paramCategory'] = $this->categoryRepository->findOneBy([
                'identifier' => $categoryIdentifier
            ]);
        }

        $qb->setParameters($parameters);

        switch ($sorting) {
            case 'priceAsc':
                $qb->orderBy('p.price', 'ASC');
                break;
            case 'priceDesc':
                $qb->orderBy('p.price', 'DESC');
                break;
            case 'reviewDesc':
                $qb->addSelect('AVG(pr.rating) AS HIDDEN averageProductReviews');
                $qb->leftJoin('p.productReviews', 'pr');
                $qb->groupBy('p.id');
                $qb->orderBy('averageProductReviews', 'DESC');
                break;
            case 'mostRelevant':
            default:
                // TODO: sort by the most relevant
                // TODO: consider: user orders, user product browsing, products from similar categories/stores/regions
        }

        return $qb->getQuery();
    }
}
