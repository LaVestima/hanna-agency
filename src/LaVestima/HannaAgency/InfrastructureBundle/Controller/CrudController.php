<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper\CrudHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class CrudController extends Controller {
	protected $doctrine;
	protected $manager;

	protected $user;

	protected $entityClass;

	protected $entities = [];

	public function __construct(/*$entityClass, */$doctrine, $tokenStorage) {
//	    var_dump($entityClass);
//	    $this->entityClass = $entityClass;
		$this->doctrine = $doctrine;
		$this->manager = $this->doctrine->getManager();
		$this->user = $tokenStorage->getToken()->getUser();
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
	 * @param $entityId
	 */
	public function updateEntity($entityId) {
		// TODO: add user and date updated
		$entity = $this->readEntity($entityId);
		// TODO: ...
	}

	/**
	 * @param $entityId
	 */
	public function deleteEntity($entityId = 0) {
		if ($entityId === 0) {
			// TODO: throw error
		}
		$entity = $this->doctrine
			->getRepository($this->entityClass)
			->find($entityId);
		$entity->setDateDeleted(new \DateTime('now'));
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
        $this->entities = $this->doctrine
            ->getRepository($this->entityClass)
            ->findAll();
        return $this;
    }

	/**
	 * @param $entityId
	 */
	public function restoreEntity($entityId) {
		$entity = $this->doctrine
			->getRepository($this->entityClass)
			->find($entityId);
		$entity->setDateDeleted(null);
	}

	/**
	 * @param $entity
	 */
	protected function purgeEntity($entity) {
	    // TODO: add user and date deleted
		$em = $this->manager;
		$em->remove($entity);
		$em->flush();
	}

	public function getEntities() {
	    return $this->entities;
    }

	public function sortBy(array $keyValueArray) { // TODO: finish
        foreach ($keyValueArray as $key => $item) {
            $func = 'get' . $key;

            usort($this->entities, function($a, $b) use ($key, $item, $func) {
                if ($item == 'ASC') {
                    return $a->$func() <=> $b->$func();
                }
                else if ($item == 'DESC') {
                    return $b->$func() <=> $a->$func();
                }
                else {
                    // TODO: throw exception
                }

//                return $a->getDatePlaced() <=> $b->getDatePlaced();
            });
        }
	    return $this;
    }
}