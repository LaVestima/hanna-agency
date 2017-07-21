<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\ProductBundle\Entity\Products;
use LaVestima\HannaAgency\ProductBundle\Entity\ProductsSizes;
use LaVestima\HannaAgency\ProductBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends BaseController
{
	public function listAction()
    {
//        $products = $this->get('product_crud_controller')
//            ->readAllEntities()
//            ->by(['priceCustomer' => 33])
//            ->getEntities();

//        var_dump($products);die;


        // ................
		$products = $this->get('product_crud_controller')
            ->readAllUndeletedEntities()
            ->getEntities();

		return $this->render('@Product/Product/list.html.twig', [
            'products' => $products
        ]);
	}
	
	public function showAction($pathSlug)
    {
		$product = $this->get('product_crud_controller')
			->readOneEntityBy(['pathSlug' => $pathSlug])
            ->getEntities();

		$productSizes = $this->get('product_size_crud_controller')
            ->readEntitiesBy(['idProducts' => $product])
            ->getEntities();

		return $this->render('@Product/Product/show.html.twig', [
            'product' => $product,
            'productSizes' => $productSizes
		]);
	}

	public function newAction(Request $request)
    {
        $product = new Products();

        $form = $this->createForm(ProductType::class, $product, [
            'isAdmin' => $this->isAdmin(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            // TODO: delete
            $product->setQRCodePath('-n--' . random_int(0, 1000000));
            // ENDTODO

            $productSize = new ProductsSizes(
                $product,
                $form->get('idSizes')->getData(),
                $form->get('availability')->getData()
            );

            try {
                $this->get('product_crud_controller')
                    ->createEntity($product);

                $this->get('product_size_crud_controller')
                    ->createEntity($productSize);
            } catch (\Exception $e) {

            }

            $this->addFlash('success', 'Product added!');

            return $this->redirectToRoute('product_list');
        }

        return $this->render('@Product/Product/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}