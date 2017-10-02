<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\ProductCrudControllerInterface;
use LaVestima\HannaAgency\ProductBundle\Entity\Products;
use LaVestima\HannaAgency\ProductBundle\Entity\ProductsSizes;
use LaVestima\HannaAgency\ProductBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends BaseController
{
    private $productCrudController;

    // TODO: DI

    public function __construct(
        ProductCrudControllerInterface $productCrudController
    ) {
        $this->productCrudController = $productCrudController;
    }

    /**
     * Product List Action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function listAction(Request $request)
    {
        $this->setQuery($this->productCrudController->setAlias('p')
            ->readAllUndeletedEntities()
            ->join('idCategories', 'c')
            ->join('idProducers', 'pr')
            ->orderBy('name')
            ->getQuery());
        $this->setView('@Product/Product/list.html.twig');
        $this->setActionBar([
            [
                'label' => 'New Product',
                'path' => 'product_new'
            ],
            [
                'label' => 'Deleted Products',
                'path' => 'product_deleted_list'
            ]
        ]);

        return parent::listAction($request);
	}

    /**
     * Product Deleted List Action.
     *
     * @param Request $request
     *
     * @return mixed
     */
	public function deletedListAction(Request $request)
    {
        $this->setQuery($this->productCrudController->setAlias('p')
            ->readAllDeletedEntities()
            ->join('idCategories', 'c')
            ->join('idProducers', 'pr')
            ->orderBy('name')
            ->getQuery());
        $this->setView('@Product/Product/list.html.twig');
        $this->setActionBar([
            [
                'label' => '< Product List',
                'path' => 'product_list'
            ]
        ]);

        return parent::listAction($request);
    }

    /**
     * Product Show Action.
     *
     * @param $pathSlug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function showAction($pathSlug)
    {
		$product = $this->get('product_crud_controller')
			->readOneEntityBy(['pathSlug' => $pathSlug])
            ->getResult();

		$productSizes = $this->get('product_size_crud_controller')
            ->readEntitiesBy(['idProducts' => $product->getId()])
            ->getResult();

		if (!is_array($productSizes) && $productSizes !== null) {
            $productSizes = [$productSizes];
        }

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

            $product = $this->get('product_crud_controller')
                ->createEntity($product);

            $productSize = new ProductsSizes(
                $product,
                $form->get('idSizes')->getData(),
                $form->get('availability')->getData()
            );

//            var_dump($productSize);die;

            $this->get('product_size_crud_controller')
                ->createEntity($productSize);

            $this->addFlash('success', 'Product added!');

            return $this->redirectToRoute('product_list');
        }

        return $this->render('@Product/Product/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}