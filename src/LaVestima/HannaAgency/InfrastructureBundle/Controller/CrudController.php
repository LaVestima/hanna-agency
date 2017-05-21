<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper\CrudHelper;
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
            TokenStorageInterface $tokenStorage) {
		$this->doctrine = $doctrine;
		$this->manager = $this->doctrine->getManager();

		if ($tokenStorage->getToken()) {
            $this->user = $tokenStorage->getToken()->getUser();
        }
	}

    /**
	 * @param $entity
	 */
	public function createEntity($entity) {
	    if (method_exists($entity, 'setDateCreated')) {
            $entity->setDateCreated(new \DateTime('now'));
        }
        if (method_exists($entity, 'setUserCreated')) {
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
	 * @param $entityId
	 * @return object
	 */
	public function readEntity($entityId) {
		return $this->doctrine
			->getRepository($this->entityClass)
			->find($entityId);
	}

	/**
	 * @param $entity
     * @param array $keyValueArray
	 */
	public function updateEntity($entity, array $keyValueArray) {
        if (!$entity) {
            // TODO: throw error
        }

        foreach ($keyValueArray as $key => $value) {
            $methodName = 'set' . $key;
            $entity->$methodName($value);
        }

        $this->manager->flush();

		// TODO: add user and date updated
		// TODO: ...
	}

	/**
	 * @param $entity
	 */
	public function deleteEntity($entity) {
		if (!$entity) {
			// TODO: throw error
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
	public function readEntitiesBy(array $keyValueArray) {
		$this->entities = $this->doctrine
			->getRepository($this->entityClass)
			->findBy($keyValueArray);
		return $this;
	}

	/**
	 * @param array $keyValueArray
	 * @return object
	 */
	public function readOneEntityBy(array $keyValueArray) {
		return $this->doctrine
			->getRepository($this->entityClass)
			->findOneBy($keyValueArray);
	}

    /**
     * @return object
     */
    public function readAllEntities() {
        // TODO: if dateDeleted and userDeleted are null
        $this->entities = $this->doctrine
            ->getRepository($this->entityClass)
            ->findAll();
        return $this;
    }

    public function readAllDeletedEntities() {

    }

	/**
	 * @param $entity
	 */
	public function restoreEntity($entity) {
        if (!$entity) {
            // TODO: throw error
        }

        // TODO: add method_exists ??
		$entity->setDateDeleted(null);
		$entity->setUserDeleted(null);

		$this->manager->flush();
	}

	/**
	 * @param $entity
	 */
	protected function purgeEntity($entity) {
		$em = $this->manager;
		$em->remove($entity);
		$em->flush();
	}

	// setEntities()

    // addEntity()

	public function getEntities() {
	    // TODO: finish SELECT query here
	    return $this->entities;
    }

	public function sortBy(array $keyValueArray) {
	    if (is_array($this->entities)) {
            foreach ($keyValueArray as $key => $item) {
                $methodName = 'get' . $key;

                usort($this->entities, function ($a, $b) use ($key, $item, $methodName) {
                    if ($item == 'ASC') {
                        return $a->$methodName() <=> $b->$methodName();
                    } else if ($item == 'DESC') {
                        return $b->$methodName() <=> $a->$methodName();
                    } else {
                        // TODO: throw exception
                    }
                });
            }
        }
	    return $this;
    }
}