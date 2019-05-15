<?php

namespace App\Repository;

use App\Controller\Infrastructure\Crud\CrudRepository;
use App\Entity\Category;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CategoryRepository extends CrudRepository
{
    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Category::class, $tokenStorage);
    }

    public function onlyActiveProducts()
    {
        $this->query
            ->join($this->alias . '.products', 'pro', 'WITH', 'pro.category=ent.id')
            ->where('pro.active = 1');

        return $this;
    }
}