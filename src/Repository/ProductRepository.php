<?php

namespace App\Repository;

use App\Controller\Infrastructure\Crud\CrudRepository;
use App\Entity\MLModel;
use App\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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

    public function readRecommendedProducts(MLModel $model = null)
    {
        if ($model) {
            $qb = $this->createQueryBuilder('p');

            $parameters = [];

            foreach ($model->getContent() as $i => $productId) {
                $qb->orWhere('p.id = :productId' . $i);

                $parameters[('productId' . $i)] = $productId;
            }

            $qb->setParameters($parameters);

            return $qb->getQuery()->getResult();
        } else {
            $dql = '
                SELECT p.*
                FROM product p
                INNER JOIN product_variant pv ON pv.product_id = p.id
                INNER JOIN order_product_variant opv ON opv.product_variant_id = pv.id
                INNER JOIN
                    (SELECT p.id, AVG(pr.rating) as ratingAvg
                    FROM product p
                    INNER JOIN product_review pr ON pr.product_id = p.id
                    WHERE p.active = true
                    GROUP BY p.id) r ON r.id = p.id
                GROUP BY p.id
                ORDER BY (SUM(opv.quantity) * r.ratingAvg) DESC
                LIMIT 10
            ';
            
            $rsm = new ResultSetMappingBuilder($this->getEntityManager());
            $rsm->addRootEntityFromClassMetadata(Product::class, 'p');


            $query = $this->getEntityManager()->createNativeQuery($dql, $rsm);
            return $query->getResult();
        }

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
