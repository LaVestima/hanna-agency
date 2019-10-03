<?php

namespace App\Repository;

use App\Controller\Infrastructure\Crud\CrudRepository;
use App\Entity\StoreOpinionVote;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StoreOpinionVoteRepository extends CrudRepository
{
    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, StoreOpinionVote::class, $tokenStorage);
    }
}
