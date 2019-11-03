<?php

namespace App\Repository;

use App\Controller\Infrastructure\Crud\CrudRepository;
use App\Entity\MLModel;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MLModelRepository extends CrudRepository
{
    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, MLModel::class, $tokenStorage);
    }
}
