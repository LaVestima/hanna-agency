<?php

namespace App\Controller\Product;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Product;
use App\Entity\ProductSize;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\ProductSizeRepository;
use App\Repository\SizeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
{
    private $productRepository;
    private $productSizeRepository;
    private $sizeRepository;

    public function __construct(
        ProductRepository $productRepository,
        ProductSizeRepository $productSizeRepository,
        SizeRepository $sizeRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productSizeRepository = $productSizeRepository;
        $this->sizeRepository = $sizeRepository;
    }

    /**
     * @Route("/product/list", name="product_list")
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function list(Request $request)
    {
        $this->setView('Product/list.html.twig');
        $this->setActionBar([
            [
                'label' => 'New Product',
                'path' => 'product_new',
                'role' => 'ROLE_ADMIN',
                'icon' => 'fa-plus'
            ],
//            [
//                'label' => 'Deleted Products',
//                'path' => 'product_deleted_list',
//                'role' => 'ROLE_ADMIN',
//                'icon' => 'fa-close'
//            ]
        ]);

        return parent::baseList($request);
    }

    /**
     * @Route("/product/show/{pathSlug}", name="product_show")
     *
     * @param string $pathSlug
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function show(string $pathSlug)
    {
        $product = $this->productRepository
            ->readOneEntityBy(['pathSlug' => $pathSlug])
            ->getResult();

        if (!$product) {
            throw new NotFoundHttpException();
        }

        $productSizes = $this->productSizeRepository
            ->readEntitiesBy(['idProducts' => $product->getId()])
            ->getResultAsArray();

        $this->setView('Product/show.html.twig');
        $this->setTemplateEntities([
            'product' => $product,
            'productSizes' => $productSizes,
        ]);

        return parent::baseShow();
    }

    /**
     * @Route("/product/new", name="product_new")
     *
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product, [
            'isAdmin' => $this->isAdmin(),
        ]);

        $sizes = $this->sizeRepository
            ->readAllEntities()
            ->getResult();

        $form->get('sizes')
            ->add('0', ChoiceType::class, [
                'label' => 'Size',
                'choices' => $sizes,
                'choice_label' => 'name',
                'placeholder' => 'Choose a size',
                'required' => true,
            ]);

        $form->get('availabilities')
            ->add('0', NumberType::class, [
                'label' => 'Availability',
                'data' => 0,
                'empty_data' => 0,
                'required' => true,
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sizes = $form->get('sizes')->getData();
            $availabilities = $form->get('availabilities')->getData();
            $countSizes = count($sizes);
            $countAvailabilities = count($availabilities);

            if ($countSizes !== $countAvailabilities) {
                // TODO: error
            }

            try {
                $product = $form->getData();

                $product = $this->productRepository
                    ->createEntity($product);

                foreach ($sizes as $key => $size) {
                    $productSize = new ProductSize(
                        $product,
                        $size,
                        $availabilities[$key]
                    );

                    $this->productSizeRepository
                        ->createEntity($productSize);
                }
            } catch (\Exception $e) {
                if ($this->isDevEnvironment()) {
                    var_dump($e->getMessage());die;
                }
            }

            $this->addFlash('success', 'Product added!');

            return $this->redirectToRoute('product_list');
        }

        $this->setView('Product/new.html.twig');
        $this->setForm($form);
        $this->setActionBar([
            [
                'label' => 'List',
                'path' => 'product_list',
                'icon' => 'fa-chevron-left'
            ]
        ]);

//        return parent::newAction($request);
        return parent::baseNew($request);

//        $product = new Product();
//        $product->setName('aaaaaaaaaaaaaaaaaaa');
//        $product->setPathSlug('ffffffffff');
//        $product->setPriceProducer(12);
//        $product->setPriceCustomer(24);
//
//        $this->productRepository->createEntity($product);
//
//        return new Response('Saved new product with id '.$product->getId());
    }
}
