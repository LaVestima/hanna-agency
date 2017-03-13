<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 12.03.17
 * Time: 17:18
 */

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CrudController extends Controller {
	protected $entityClass;

	/**
	 * @param $entity
	 */
	public function createEntity($entity) {
		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();
	}

	/**
	 * @param $entityId
	 * @return object
	 */
	public function readEntity($entityId) {
		return $this->getDoctrine()
			->getRepository($this->entityClass)
			->find($entityId);
	}

	/**
	 * @param $entityId
	 */
	public function updateEntity($entityId) {
		$entity = $this->readEntity($entityId);
		// TODO: ...
	}

	/**
	 * @param $entityId
	 */
	public function deleteEntity($entityId) {
		$entity = $this->getDoctrine()
			->getRepository($this->entityClass)
			->find($entityId);
		$entity->setDateDeleted(new \DateTime('now'));
	}

	/**
	 * @param $entityId
	 */
	public function undeleteEntity($entityId) {
		$entity = $this->getDoctrine()
			->getRepository($this->entityClass)
			->find($entityId);
		$entity->setDateDeleted(null);
	}

	/**
	 * @param $entity
	 */
	protected function purgeEntity($entity) {
		$em = $this->getDoctrine()->getManager();
		$em->remove($entity);
		$em->flush();
	}

	/**
	 * @return array
	 */
	public function readAllEntities() {
		return $this->getDoctrine()
			->getRepository($this->entityClass)
			->findAll();
	}
}