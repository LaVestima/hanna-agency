<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller {
	public function listAction() {
		$products = $this->get('product_crud_controller')
            ->readAllEntities()
            ->getEntities();

		return $this->render('@Product/Product/list.html.twig', [
		    'products' => $products,
        ]);
	}
	
	public function showAction($pathSlug) {
		$product = $this->get('product_crud_controller')
			->readOneEntityBy(['pathSlug' => $pathSlug]);

		return $this->render('@Product/Product/show.html.twig', [
			'product' => $product,
		]);
	}
}