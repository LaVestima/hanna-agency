<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class CrudController extends Controller {
	protected $doctrine;
	protected $manager;

	private $userClass = 'LaVestima\\HannaAgency\\UserManagementBundle\\Entity\\Users';
	protected $entityClass;

	public function __construct($doctrine) {
		$this->doctrine = $doctrine;
		$this->manager = $this->doctrine->getManager();
	}

	/**
	 * @param $entity
	 */
	public function createEntity($entity) {
		$entity->setDateCreated(new \DateTime('now'));
		// TODO: add user and date created
		$em = $this->manager;
		$em->persist($entity);
		$em->flush();
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
		return $this->doctrine
			->getRepository($this->entityClass)
			->findBy($keyValueArray);
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
		$em = $this->manager;
		$em->remove($entity);
		$em->flush();
	}

	/**
	 * @return array
	 */
	public function readAllEntities() {
		return $this->doctrine
			->getRepository($this->entityClass)
			->findAll();
	}

//	private function getCurrentUser() {
//		return $this->get('security.token_storage')
//			->getToken()->getUser();
//	}
}