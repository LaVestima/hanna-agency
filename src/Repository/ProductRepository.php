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

    public function readCartProducts(array $cart)
    {
        $this->clearQuery();

        $this->query->select($this->alias)
            ->from($this->entityClass, $this->alias);

        $cartCounter = 0;

        foreach ($cart as $productPathSlug => $q) {
            $this->query->orWhere($this->alias . '.pathSlug = :pathSlug' . $cartCounter)
                ->setParameter('pathSlug' . $cartCounter, $productPathSlug);
            $cartCounter++;
        }

        return $this;
    }
}
