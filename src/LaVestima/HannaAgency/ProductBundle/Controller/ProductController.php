<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\ProductBundle\Entity\Products;
use LaVestima\HannaAgency\ProductBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends BaseController
{
	public function listAction()
    {
		$products = $this->get('product_crud_controller')
            ->readAllUndeletedEntities()
            ->getEntities();

		return $this->render('@Product/Product/list.html.twig', [
		    'products' => $products,
        ]);
	}
	
	public function showAction($pathSlug)
    {
		$product = $this->get('product_crud_controller')
			->readOneEntityBy(['pathSlug' => $pathSlug]);

		return $this->render('@Product/Product/show.html.twig', [
			'product' => $product,
		]);
	}

	public function newAction(Request $request)
    {
        $product = new Products();

        $categories = $this->get('category_crud_controller')
            ->readAllEntities()
            ->getEntities();
        $sizes = $this->get('size_crud_controller')
            ->readAllEntities()
            ->getEntities();
        $producers = $this->get('producer_crud_controller')
            ->readAllEntities()
            ->getEntities();
        // TODO: more ??

        $form = $this->createForm(ProductType::class, $product, [
            'categories' => $categories,
            'sizes' => $sizes,
            'producers' => $producers,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            // TODO: delete
            $product->setQRCodePath('-n--' . random_int(0, 1000000));
            // ENDTODO

            $this->get('product_crud_controller')
                ->createEntity($product);

            $this->addFlash('success', 'Product added!');

            return $this->redirectToRoute('product_list');
        }

        return $this->render('@Product/Product/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}