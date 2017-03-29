<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller {
	public function listAction() {
		
	}
	
	public function showAction($pathSlug) {
		$product = $this->get('product_crud_controller')
			->readOneEntityBy(['pathSlug' => $pathSlug]);

		return $this->render('@Product/Product/show.html.twig', [
			'product' => $product,
		]);
	}
}