<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityNotFoundException;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper\CrudHelper;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class CrudController extends Controller {
	protected $doctrine;
	protected $manager;
	protected $user;

	protected $entityClass = '';

	protected $entities = [];

	// TODO: realize the whole connection with custom queries
	protected $query = '';

	public function __construct(
        Registry $doctrine,
        TokenStorageInterface $tokenStorage
    ) {
		$this->doctrine = $doctrine;
		$this->manager = $this->doctrine->getManager();

		if ($tokenStorage->getToken()) {
            $this->user = $tokenStorage->getToken()->getUser();
        }
	}

    /**
	 * @param $entity
	 */
	public function createEntity(EntityInterface $entity)
    {
	    if (method_exists($entity, 'setDateCreated')) {
            $entity->setDateCreated(new \DateTime('now'));
        }
        if (method_exists($entity, 'setUserCreated') && !$entity->getUserCreated()) {
            $entity->setUserCreated($this->user);
        }
        if (method_exists($entity, 'setPathSlug')) {
	        $entity->setPathSlug(CrudHelper::generatePathSlug());
        }

		$em = $this->manager;
	    $entity = $em->merge($entity);
		$em->persist($entity);
		$em->flush();

		return $entity;
	}

	/**
	 * @param $entity
     * @param array $keyValueArray
	 */
	public function updateEntity($entity, array $keyValueArray)
    {
        if (!$entity) {
            throw new EntityNotFoundException();
        }

        foreach ($keyValueArray as $key => $value) {
            $methodName = 'set' . $key;

            if (!method_exists($entity, $methodName)) {
                throw new \InvalidArgumentException();
            }

            $entity->$methodName($value);
        }

        $this->manager->flush();

		// TODO: add user and date updated
		// TODO: ...
	}

	/**
	 * @param $entity
	 */
	public function deleteEntity($entity)
    {
		if (!$entity) {
		    throw new EntityNotFoundException();
		}

		// TODO: add method_exists ??
		$entity->setDateDeleted(new \DateTime('now'));
		$entity->setUserDeleted($this->user);

		$this->manager->flush();
	}

	/**
	 * @param array $keyValueArray
	 * @return mixed
	 */
	public function readEntitiesBy(array $keyValueArray)
    {
		$this->entities = $this->doctrine
			->getRepository($this->entityClass)
			->findBy($keyValueArray);
		return $this;
	}

	/**
	 * @param array $keyValueArray
	 * @return object
	 */
	public function readOneEntityBy(array $keyValueArray)
    {
	    $entity = $this->doctrine
            ->getRepository($this->entityClass)
            ->findOneBy($keyValueArray);

	    if (!$entity) {
//	        throw new EntityNotFoundException();
        }

		return $entity;
	}

    /**
     * @return object
     */
    public function readAllEntities()
    {
        $this->query = 'SELECT ent FROM ' . $this->entityClass . ' ent';

        $this->executeQuery();

//        $this->entities = $this->doctrine
//            ->getRepository($this->entityClass)
//            ->findAll();
        return $this;
    }

    public function readAllUndeletedEntities()
    {
        $this->query = '
            SELECT ent 
            FROM ' . $this->entityClass . ' ent
            WHERE ent.dateDeleted IS NULL
            ';

        $this->executeQuery();

        return $this;
    }

    public function readAllDeletedEntities()
    {
        $this->query = '
            SELECT ent 
            FROM ' . $this->entityClass . ' ent
            WHERE ent.dateDeleted IS NOT NULL
            ';

        $this->executeQuery();

        return $this;
    }

    public function readRandomEntities(int $numberOfEntities = null, bool $entitiesCanRepeat = false)
    {
        if (!$entitiesCanRepeat && $numberOfEntities > $this->countRows()) {
            throw new \InvalidArgumentException('Number of randomized entities cannot exceed the row number!');
        }

        if ($numberOfEntities === null) {
            $numberOfEntities = rand(1, $this->countRows());
        }

        if ($numberOfEntities === 1) {
            do {
                $entity = $this->readOneEntityBy(['id' => rand(1, $this->getLastId())]);
            } while (!$entity);

            return $entity;
        } elseif ($numberOfEntities > 1) {
            $entities = [];

            for ($i = 0; $i < $numberOfEntities; $i++) {
                do {
                    $entity = $this->readOneEntityBy(['id' => rand(1, $this->getLastId())]);

                    if (!$entitiesCanRepeat && in_array($entity, $entities)) {
                        $entity = null;
                    }
                } while (!$entity);
            }

            return $entities;
        } else {
            throw new \InvalidArgumentException();
        }
    }

	/**
	 * @param $entity
	 */
	public function restoreEntity($entity)
    {
        if (!$entity) {
            throw new EntityNotFoundException();
        }

        // TODO: add method_exists ??
		$entity->setDateDeleted(null);
		$entity->setUserDeleted(null);

		$this->manager->flush();
	}

	/**
	 * @param $entity
	 */
	protected function purgeEntity($entity)
    {
        if (!$entity) {
            throw new EntityNotFoundException();
        }

		$em = $this->manager;
		$em->remove($entity);
		$em->flush();
	}

	// setEntities()

    // addEntity()

	public function getEntities()
    {
//        $this->executeQuery();
	    // TODO: finish SELECT query here
	    return $this->entities;
    }

	public function sortBy(array $keyValueArray)
    {
	    if (is_array($this->entities)) {
            foreach ($keyValueArray as $key => $item) {
                $methodName = 'get' . $key;

                usort($this->entities, function ($a, $b) use ($key, $item, $methodName) {
                    if ($item == 'ASC') {
                        return $a->$methodName() <=> $b->$methodName();
                    } else if ($item == 'DESC') {
                        return $b->$methodName() <=> $a->$methodName();
                    } else {
                        throw new \InvalidArgumentException();
                    }
                });
            }
        }
	    return $this;
    }

    private function executeQuery()
    {
        $this->entities = $this->manager
            ->createQuery($this->query)
            ->getResult();
    }

    private function countRows()
    {
        $this->query = '
            SELECT COUNT(ent)
            FROM ' . $this->entityClass . ' ent
        ';

        return (int)$this->manager
            ->createQuery($this->query)
            ->getSingleScalarResult();
    }

    private function getLastId()
    {
        $this->query = '
            SELECT ent.id
            FROM ' . $this->entityClass . ' ent
            ORDER BY ent.id DESC
        ';

        return (int)$this->manager
            ->createQuery($this->query)
            ->setMaxResults(1)
            ->getSingleScalarResult();
    }
}