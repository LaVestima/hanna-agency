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
		$productsSizes = $this->get('product_size_crud_controller')
            ->readAllEntities()
            ->getEntities();

		return $this->render('@Product/Product/list.html.twig', [
            'productsSizes' => $productsSizes
        ]);
	}
	
	public function showAction($pathSlug)
    {
		$product = $this->get('product_crud_controller')
			->readOneEntityBy(['pathSlug' => $pathSlug]);

		$productSize = $this->get('product_size_crud_controller')
            ->readOneEntityBy(['idProducts' => $product]);

		return $this->render('@Product/Product/show.html.twig', [
			'productSize' => $productSize,
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