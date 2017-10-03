<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\QueryBuilder;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper\CrudHelper;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class CrudController extends BaseController implements CrudControllerInterface
{
	protected $doctrine;
	protected $manager;
	protected $user;

	protected $entityClass = '';

	protected $alias = 'ent';

    /**
     * @var QueryBuilder $query
     */
	protected $query;

    /**
     * CrudController constructor.
     *
     * @param ManagerRegistry $doctrine
     * @param TokenStorageInterface $tokenStorage
     */
	public function __construct(
        ManagerRegistry $doctrine,
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
     * Create new Entity in DB (INSERT).
     *
	 * @param $entity
     * @return EntityInterface
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

        if ($this->user) {
            $entity = $em->merge($entity);
        }

		$em->persist($entity);
		$em->flush();

		return $entity;
	}

	/**
     * Update Entity in DB (UPDATE).
     *
	 * @param EntityInterface $entity
     * @param EntityInterface|array $newEntity
     *
     * @return EntityInterface
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

		// TODO: add user and date updated ??
	}

    /**
     * Read Entities from DB with given value (SELECT + WHERE).
     *
     * @param array $keyValueArray
     * @return mixed
     */
    public function readEntitiesBy(array $keyValueArray)
    {
        $this->clearQuery();

        $this->query->select($this->alias)
            ->from($this->entityClass, $this->alias);

        foreach ($keyValueArray as $key => $condition) {
            if (is_array($condition)) {
                if (count($condition) === 2) {
                    $operator = $condition[1];
                    $value = $condition[0];
                } else {
                    throw new \Exception('Two elements required in condition array');
                }
            } else {
                $operator = '=';
                $value = $condition;
            }

            $this->query->andWhere($this->alias . '.' . $key . ' ' . $operator . ' :param_' . $key)
                ->setParameter('param_' . $key, '' . $value);
        }

        return $this;
    }

    /**
     * Read one Entity from DB with given value (SELECT + WHERE).
     *
     * @param array $keyValueArray
     * @return object
     */
    public function readOneEntityBy(array $keyValueArray)
    {
        $this->readEntitiesBy($keyValueArray);
        $this->query->setMaxResults(1);

        return $this;
    }

    /**
     * Read all Entities from DB (SELECT).
     *
     * @return object
     */
    public function readAllEntities()
    {
        $this->clearQuery();

        $this->query->select($this->alias)
            ->from($this->entityClass, $this->alias);

        return $this;
    }

    /**
     * Read all not deleted Entities from DB (SELECT).
     *
     * @return $this
     */
    public function readAllUndeletedEntities()
    {
        $this->readAllEntities();

        $this->query->where($this->alias . '.dateDeleted IS NULL');

        return $this;
    }

    /**
     * Read all deleted Entities from DB (SELECT).
     *
     * @return $this
     */
    public function readAllDeletedEntities()
    {
        $this->readAllEntities();

        $this->query->where($this->alias . '.dateDeleted IS NOT NULL');

        return $this;
    }

	/**
     * Mark the Entity in DB as deleted.
     *
	 * @param EntityInterface $entity
	 */
	public function deleteEntity(EntityInterface $entity)
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
     * Mark the Entity in DB as not deleted.
     *
     * @param EntityInterface $entity
     */
    public function restoreEntity(EntityInterface $entity)
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
     * Delete Entity from DB (DELETE)
     *
     * @param EntityInterface $entity
     */
    protected function purgeEntity(EntityInterface $entity)
    {
        if (!$entity) {
            throw new EntityNotFoundException();
        }

        $em = $this->manager;
        $em->remove($entity);
        $em->flush();
    }

    /**
     * Read random Entities.
     *
     * @param int|null $numberOfEntities
     * @param bool $entitiesCanRepeat
     *
     * @return array|null|object
     */
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
     * Clear the SQL Query.
     *
     * @return $this
     */
    public function clearQuery()
    {
        $this->query = $this->manager->createQueryBuilder('ent');

        return $this;
    }

    /**
     * Set the alias for the Entity.
     *
     * @param string $alias
     * @return $this
     */
    public function setAlias(string $alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Join Entity with other Entity (JOIN)
     *
     * @param $otherEntity
     * @param $alias
     *
     * @return $this
     */
    public function join(string $otherEntity, string $alias) : CrudController
    {
        $this->query->join(
            $this->alias . '.' . $otherEntity,
            $alias,
            'WITH',
            $this->alias . '.' . $otherEntity . '=' . $alias . '.id'
        );

        return $this;
    }

    /**
     * Join Entity with other Entity (LEFT JOIN)
     *
     * @param string $otherEntity
     * @param string $alias
     *
     * @return CrudController
     */
    public function leftJoin(string $otherEntity, string $alias) : CrudController
    {
        $this->query->leftJoin(
            $this->alias . '.' . $otherEntity,
            $alias,
            'WITH',
            $this->alias . '.' . $otherEntity . '=' . $alias . '.id'
        );

        return $this;
    }

    /**
     * Sort the read Entities (ORDER BY)
     *
     * @param string $field
     * @param string $order
     *
     * @return $this
     */
    public function orderBy(string $field, string $order = 'ASC')
    {
        if (!in_array(strtoupper($order), ['ASC', 'DESC'])) {
            throw new \InvalidArgumentException('Sorting order must be ASC or DESC');
        }

        $this->query->orderBy(
            $this->alias . '.' . $field,
            strtoupper($order)
        );

        return $this;
    }

    /**
     * Get the SQL Query.
     *
     * @return \Doctrine\ORM\Query
     */
    public function getQuery()
    {
        return $this->query->getQuery();
    }

    /**
     * Get the result of SQL Query.
     *
     * @return array|null
     */
    public function getResult()
    {
        $result = $this->getQuery()->getResult();

        return count($result) === 1 ? $result[0] :
            (count($result) === 0 ? null :
            $result);
    }

    /**
     * Count all the rows in DB (COUNT())
     *
     * @return int
     */
    public function countRows()
    {
        $this->clearQuery();

        $this->query->select('count(' . $this->alias . '.id)')
            ->from($this->entityClass, $this->alias);

        return (int)$this->getQuery()->getSingleScalarResult();
    }

    /**
     * Get the last id of Entity in DB
     *
     * @return int
     */
    private function getLastId()
    {
        $this->clearQuery();

        $this->query->select($this->alias . '.id')
            ->from($this->entityClass, $this->alias)
            ->orderBy($this->alias . '.id', 'DESC')
            ->setMaxResults(1);

        return (int)$this->getQuery()->getSingleScalarResult();
    }
}