<?php

namespace App\Repository;

use App\Controller\Infrastructure\Crud\CrudRepository;
use App\Entity\ProductVariant;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductVariantRepository extends CrudRepository
{
    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, ProductVariant::class, $tokenStorage);
    }

    public function readCartProductVariants(array $cart = [])
    {
        $this->clearQuery();

        $this->query->select($this->alias)
            ->from($this->entityClass, $this->alias);

        $cartCounter = 0;

        foreach ($cart as $productVariantIdentifier => $q) {
            $this->query->orWhere($this->alias . '.identifier = :identifier' . $cartCounter)
                ->setParameter('identifier' . $cartCounter, $productVariantIdentifier);
            $cartCounter++;
        }

        return $this;
    }
}
