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
	public function createEntity($entity) {
		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();
	}

	public function readEntity() {

	}

	public function updateEntity() {

	}

	public function deleteEntity($entity) {
		$em = $this->getDoctrine()->getManager();
		$em->remove($entity);
		$em->flush();
	}
}