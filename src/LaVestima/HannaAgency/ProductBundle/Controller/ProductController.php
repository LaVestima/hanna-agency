<?php

namespace LaVestima\HannaAgency\ProductBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\ProductCrudControllerInterface;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\ProductSizeCrudControllerInterface;
use LaVestima\HannaAgency\ProductBundle\Entity\Products;
use LaVestima\HannaAgency\ProductBundle\Entity\ProductsSizes;
use LaVestima\HannaAgency\ProductBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends BaseController
{
    protected $productCrudController;
    protected $productSizeCrudController;

    protected $entityName = 'product';

    /**
     * ProductController constructor.
     *
     * @param ProductCrudControllerInterface $productCrudController
     * @param ProductSizeCrudControllerInterface $productSizeCrudController
     */
    public function __construct(
        ProductCrudControllerInterface $productCrudController,
        ProductSizeCrudControllerInterface $productSizeCrudController
    ) {
        $this->productCrudController = $productCrudController;
        $this->productSizeCrudController = $productSizeCrudController;
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
            ->getQuery()
        );
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
        $this->setView('@Product/Product/deletedList.html.twig');
        $this->setActionBar([
            [
                'label' => '< Back',
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
		$product = $this->productCrudController
			->readOneEntityBy(['pathSlug' => $pathSlug])
            ->getResult();

		$productSizes = $this->productSizeCrudController
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

    /**
     * Product New Action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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

            $product = $this->productCrudController
                ->createEntity($product);

            $productSize = new ProductsSizes(
                $product,
                $form->get('idSizes')->getData(),
                $form->get('availability')->getData()
            );

            $this->productSizeCrudController
                ->createEntity($productSize);

            $this->addFlash('success', 'Product added!');

            return $this->redirectToRoute('product_list');
        }

        $this->setView('@Product/Product/new.html.twig');
        $this->setForm($form);
        $this->setActionBar([
           [
               'label' => '< List',
               'path' => 'product_list'
           ]
        ]);

        return parent::newAction($request);
    }
}