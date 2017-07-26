<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityNotFoundException;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper\CrudHelper;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class CrudController extends BaseController
{
	protected $doctrine;
	protected $manager;
	protected $user;

	protected $entityClass = '';

	protected $entities = [];

	protected $alias = 'ent';

	// TODO: realize the whole connection with custom queries
	protected $query = null;

	public function __construct(
        Registry $doctrine,
        TokenStorageInterface $tokenStorage
    ) {
		$this->doctrine = $doctrine;
		$this->manager = $this->doctrine->getManager();

		if ($tokenStorage->getToken()) {
            $this->user = $tokenStorage->getToken()->getUser();
        }

        $this->clearQuery();
	}

    /**
	 * @param $entity
	 */
	public function createEntity(EntityInterface $entity)
    {
	    if (method_exists($entity, 'setDateCreated')) {
            $entity->setDateCreated(new \DateTime('now'));
        }
        if (method_exists($entity, 'setUserCreated') && !$entity->getUserCreated() && $this->user) {
            $entity->setUserCreated($this->user);
        }
        if (method_exists($entity, 'setPathSlug')) {
	        $entity->setPathSlug(CrudHelper::generatePathSlug());
        }

		$em = $this->manager;

	    // TODO: works, i don't know why o.O
        // TODO: think about it !!!!!!!!!!!!
//        if ($this->user) {
//            $entity = $em->merge($entity);
//        }

		$em->persist($entity);
		$em->flush();

		return $entity;
	}

	/**
	 * @param $entity
     * @param array $keyValueArray
	 */
	public function updateEntity(EntityInterface $oldEntity, $newEntity)
    {
        if (!$oldEntity) {
            throw new EntityNotFoundException();
        }

        if ($newEntity instanceof EntityInterface) {
            // TODO: test it !!!
            $this->manager->merge($newEntity);
        } elseif (is_array($newEntity)) {
            foreach ($newEntity as $key => $value) {
                $methodName = 'set' . $key;

                if (!method_exists($oldEntity, $methodName)) {
                    throw new \InvalidArgumentException();
                }

                $oldEntity->$methodName($value);
            }
        } else {
            throw new \InvalidArgumentException();
        }

        $this->manager->flush();

        return $oldEntity;

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

        if (method_exists($entity, 'setDateDeleted')) {
            $entity->setDateDeleted(new \DateTime('now'));
        }
        if (method_exists($entity, 'setUserDeleted')) {
            $entity->setUserDeleted($this->user);
        }

		$this->manager->flush();
	}

	/**
	 * @param array $keyValueArray
	 * @return mixed
	 */
	public function readEntitiesBy(array $keyValueArray)
    {
        $this->query->select($this->alias)
            ->from($this->entityClass, $this->alias);

        foreach ($keyValueArray as $key => $value) {
            $this->query->andWhere($this->alias . '.' . $key . ' = :param_' . $key)
                ->setParameter('param_' . $key, ''.$value);
        }

//		$this->entities = $this->doctrine
//			->getRepository($this->entityClass)
//			->findBy($keyValueArray);

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

		return $entity;
	}

    /**
     * @return object
     */
    public function readAllEntities()
    {
        $this->query = '
            SELECT ent 
            FROM ' . $this->entityClass . ' ent
            ';

        $this->executeQuery();

        return $this;
    }

    public function readAllUndeletedEntities()
    {
        $this->query = $this->query->select($this->alias)
            ->from($this->entityClass, $this->alias)
            ->where($this->alias . '.dateDeleted IS NULL');

        return $this;
    }

    public function readAllDeletedEntities()
    {
        $this->query = $this->query->select('ent')
            ->from($this->entityClass, 'ent')
            ->where('ent.dateDeleted IS NOT NULL');

        return $this;
    }

    public function by(array $keyValueArray)
    {
        $this->query .= 'WHERE ';

        foreach ($keyValueArray as $key => $value) {
            $key = ucfirst(preg_replace_callback(
                '/[A-Z]/',
                function ($matches) {
//                    foreach ($matches as $key => $match) {
//                        $matches[$key] = '_'. strtolower($match);
//                    }

                    return '_' . $matches[0];
                },
                $key
            ));

            $this->query .= $key . '=' . $value;
        }

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

                $entities[] = $entity;
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

        if (
            method_exists($entity, 'setDateDeleted') &&
            method_exists($entity, 'setUserDeleted')
        ) {
            $entity->setDateDeleted(null);
            $entity->setUserDeleted(null);
        } else {
            if ($this->isDevEnvironment()) {
                throw new \BadMethodCallException('Entity ' . $this->entityClass . ' cannot be restored');
            }
        }


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

    public function clearQuery()
    {
        $this->query = $this->manager->createQueryBuilder('ent');

        return $this;
    }

    public function setAlias(string $alias)
    {
        $this->alias = $alias;

        return $this;
    }

    public function join($otherEntity, $alias)
    {
        $this->query->join(
            $this->alias . '.' . $otherEntity,
            $alias,
            'WITH',
            $this->alias . '.' . $otherEntity . '=' . $alias . '.id'
        );

        return $this;
    }

	public function getEntities()
    {
	    // TODO: finish SELECT query here
        return $this->entities;
//	    return count($this->entities) > 1 ? $this->entities : $this->entities[0];
    }

    public function orderBy(string $field, string $order = 'ASC')
    {
        $this->query->orderBy(
            $this->alias . '.' . $field,
            strtoupper($order)
        );

        return $this;
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

    public function getQuery()
    {
//        var_dump($this->query->getQuery());die;
//        var_dump($this->query->getQuery()->getDql());die;
        return $this->query->getQuery();
    }

    public function getResult()
    {
//        var_dump($this->query->getQuery()->getDql());die;
//        var_dump($this->getQuery()->getResult());die;
        return $this->getQuery()->getResult();
    }

    private function executeQuery()
    {
//        var_dump($this->manager
//            ->createQuery($this->query));die;


        $this->entities = $this->manager
            ->createQuery($this->query)
            ->getResult();
    }

    public function countRows()
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