<?php

namespace App\Controller\Infrastructure\Crud;

use App\Controller\Infrastructure\BaseController;
use App\Helper\CrudHelper;
use App\Helper\RandomHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\QueryBuilder;
use App\Model\Infrastructure\EntityInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class CrudRepository extends ServiceEntityRepository//BaseController
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
     * @param string $entityClass
     * @param TokenStorageInterface $tokenStorage
     */
	public function __construct(
        ManagerRegistry $doctrine,
//        EntityManagerInterface $doctrine,
//        $doctrine,
        $entityClass,
        TokenStorageInterface $tokenStorage
    ) {
	    $this->entityClass = $entityClass;

		$this->doctrine = $doctrine;
//		$this->manager = $this->doctrine->getManager();
        $this->manager = $doctrine->getManagerForClass($entityClass);

		if ($tokenStorage->getToken()) {
            $this->user = $tokenStorage->getToken()->getUser();
            if ($this->user === 'anon.') {
                $this->user = null;
            }
        }

        $this->clearQuery();

        parent::__construct($doctrine, $entityClass);
	}

    /**
     * Create new Entity in DB (INSERT).
     *
	 * @param $entity
     * @return EntityInterface
	 */
	public function createEntity(EntityInterface $entity)
    {
	    if (method_exists($entity, 'setDateCreated') && !$entity->getDateCreated()) {
            $entity->setDateCreated(new \DateTime('now'));
        }
        if (method_exists($entity, 'setUserCreated') && !$entity->getUserCreated() && $this->user) {
            $entity->setUserCreated($this->user);
        }
        if (method_exists($entity, 'setPathSlug') && !$entity->getPathSlug()) {
	        $entity->setPathSlug(RandomHelper::generateString(50));
        }
        if (method_exists($entity, 'setIdentifier') && !$entity->getIdentifier()) {
            $entity->setIdentifier(RandomHelper::generateString(50));
        }

		$em = $this->manager;

        if ($em->contains($entity)) {
            $entity = $em->merge($entity);
        }

		$em->persist($entity);
		$em->flush();

		return $entity;
	}

    /**
     * Update Entity in DB (UPDATE).
     *
     * @param EntityInterface $oldEntity
     * @param EntityInterface|array $newEntity
     *
     * @return EntityInterface
     * @throws EntityNotFoundException
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

        try {
            $this->manager->flush();
        } catch (\Exception $e) {

        }

        return $oldEntity;

		// TODO: add user and date updated ??
	}

    /**
     * Read Entities from DB with given value (SELECT + WHERE).
     *
     * @param array $keyValueArray
     * @return self
     * @throws \Exception
     */
    public function readEntitiesBy(array $keyValueArray)
    {
        $this->clearQuery();

        $this->query->select($this->alias)
            ->from($this->entityClass, $this->alias);

        foreach ($keyValueArray as $key => $condition) {
            $tableFieldName = $this->generateTableFieldName($key);

            [$operator, $value] = $this->extractElementsFromCondition($condition);

            $parameterName = 'param_' . str_replace(['.', '(', ')', ',', '\'', ' '], '', $key);

            if ($value instanceof \DateTime) {
                if ($operator === '>') {
                    $this->query->andWhere($tableFieldName . ' BETWEEN :' . $parameterName . ' AND \'' . (new \DateTime('now'))->format('Y-m-d H:i:s') . '\'')
                        ->setParameter($parameterName, '' . $value->format('Y-m-d H:i:s'));
                } elseif ($operator === '<') {
                    $this->query->andWhere($tableFieldName . ' BETWEEN ' . (new \DateTime('01-01-1970'))->format('Y-m-d H:i:s') . ' AND :' . $parameterName)
                        ->setParameter($parameterName, '' . $value->format('Y-m-d H:i:s'));
                }
            } else {
                if (strtoupper($operator) === 'LIKE') {
                    $value = '%' . $value . '%';
                }

                $this->query->andWhere($tableFieldName . ' ' . $operator . ' :' . $parameterName)
                    ->setParameter($parameterName, '' . $value);
            }
        }

        return $this;
    }

    /**
     * @param $conditionKey
     * @return string
     * @throws \InvalidArgumentException
     */
    private function generateTableFieldName($conditionKey)
    {
        if (strpos($conditionKey, 'CONCAT') !== false) {
            $tableFieldName = $conditionKey;
        } elseif (count($parts = explode('.', $conditionKey)) > 1) {
            if (count($parts) > 2) {
                throw new \InvalidArgumentException('Wrong table field format, should be \'x.y\'');
            }

            $tableFieldName = $conditionKey;
        } else {
            $tableFieldName = $this->alias . '.' . $conditionKey;
        }

        return $tableFieldName;
    }

    /**
     * @param $condition
     * @return array
     * @throws \Exception
     */
    private function extractElementsFromCondition($condition)
    {
        if (is_array($condition)) {
            if (count($condition) === 2) {
                $operator = $condition[1];
                $value = $condition[0];
            } else {
                throw new \Exception('Two elements required in condition array!');
            }
        } else {
            $operator = '=';
            $value = $condition;
        }

        return [$operator, $value];
    }

    /**
     * Read one Entity from DB with given value (SELECT + WHERE).
     *
     * @param array $keyValueArray
     * @return CrudRepository
     * @throws \Exception
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
     * @return CrudRepository
     */
    public function readAllEntities()
    {
        $this->clearQuery();

        $this->query->select($this->alias)
            ->from($this->entityClass, $this->alias);

        return $this;
    }

    /**
     * Only include deleted Entities.
     *
     * @return $this
     */
    public function onlyDeleted()
    {
        $this->query->andWhere($this->alias . '.dateDeleted IS NOT NULL');

        return $this;
    }

    /**
     * Only include not deleted Entities.
     *
     * @return $this
     */
    public function onlyUndeleted()
    {
        $this->query->andWhere($this->alias . '.dateDeleted IS NULL');

        return $this;
    }

    public function addSelect($column)
    {
        $this->query->addSelect($column);

        return $this;
    }

    /**
     * Mark the Entity in DB as deleted.
     *
     * @param EntityInterface $entity
     * @throws EntityNotFoundException
     */
	public function deleteEntity(EntityInterface $entity)
    {
		if (!$entity) {
		    throw new EntityNotFoundException();
		}

        if (
            method_exists($entity, 'setDateDeleted') &&
            method_exists($entity, 'setUserDeleted')
        ) {
            $entity->setDateDeleted(new \DateTime('now'));
            $entity->setUserDeleted($this->user);
        } else {
            $this->purgeEntity($entity);
        }

		$this->manager->flush();
	}

    /**
     * Mark the Entity in DB as not deleted.
     *
     * @param EntityInterface $entity
     * @throws EntityNotFoundException
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
            if ($this->isEnvDev()) {
                throw new \BadMethodCallException('Entity ' . $this->entityClass . ' cannot be restored');
            }
        }

        $this->manager->flush();
    }

    /**
     * Delete Entity from DB (DELETE)
     *
     * @param EntityInterface $entity
     * @throws EntityNotFoundException
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
        $this->clearQuery();

        if (!$entitiesCanRepeat && $numberOfEntities > $this->countRows()) {
            throw new \InvalidArgumentException('Number of randomized entities cannot exceed the row number!');
        }

        if ($numberOfEntities === null) {
            $numberOfEntities = random_int(1, $this->countRows());
        }

        if ($numberOfEntities === 1) {
            do {
                $entity = $this->readOneEntityBy([
                    'id' => random_int(1, $this->getLastId())
                ]);
            } while (!$entity->getResult());

            return [$entity->getResult()];
        } elseif ($numberOfEntities > 1) {
            $entities = [];

            for ($i = 0; $i < $numberOfEntities; $i++) {
                $entity = $this->readOneEntityBy(['id' => random_int(1, $this->getLastId())]);

                if (!$entitiesCanRepeat && in_array($entity, $entities)) {
                    $entity = null;
                }

                if ($entity->getResult()) {
                    $entities[] = $entity->getResult();
                } else {
                    $i--;
                }
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
    public function join(string $otherEntity, string $alias) : CrudRepository
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
     * @return CrudRepository
     */
    public function leftJoin(string $otherEntity, string $alias) : CrudRepository
    {
        $this->query->leftJoin(
            $this->alias . '.' . $otherEntity,
            $alias,
            'WITH',
            $this->alias . '.' . $otherEntity . '=' . $alias . '.id'
        );

        return $this;
    }

//    public function fetchJoin(string $otherEntity, string $otherEntityAlias = '')
//    {
//        $this->query->leftJoin(($otherEntityAlias !== '' ? $otherEntityAlias : $this->alias) . '.' . $otherEntity, $otherEntity);
////        $this->query->leftJoin(($this->alias) . '.' . $otherEntity, $otherEntity);
//
//        return $this;
//    }

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

        $field = $this->generateTableFieldName($field);

        $this->query->orderBy(
            $field,
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
//        var_dump($this->query->getQuery()->getDQL()); die;
        return $this->query->getQuery();
    }

    /**
     * Get the result of SQL Query.
     *
     * @return mixed
     */
    public function getResult()
    {
        $result = $this->getResultAsArray();

        return count($result) === 1 ? reset($result) :
            (count($result) === 0 ? null :
            $result);
    }

    /**
     * Get the result of SQL Query always as array.
     *
     * @return array
     */
    public function getResultAsArray()
    {
        $tmpResult = $this->getQuery()->getResult();
//        var_dump($tmpResult);

        $result = [];

        foreach ($tmpResult as $tr) {
            $result[$tr->getId()] = $tr;
        }

        return $result;
    }

    /**
     * Count all the rows in DB (COUNT())
     *
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
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
     * @throws \Doctrine\ORM\NonUniqueResultException
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
