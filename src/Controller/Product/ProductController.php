<?php

namespace App\Controller\Product;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Product;
use App\Entity\ProductSize;
use App\Form\ProductType;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductSizeRepository;
use App\Repository\SizeRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
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
    private $productImageRepository;
    private $sizeRepository;

    public function __construct(
        ProductRepository $productRepository,
        ProductSizeRepository $productSizeRepository,
        ProductImageRepository $productImageRepository,
        SizeRepository $sizeRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productSizeRepository = $productSizeRepository;
        $this->productImageRepository = $productImageRepository;
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

        $productImages = $this->productImageRepository
            ->readEntitiesBy(['idProducts' => $product->getId()])
            ->orderBy('sequencePosition')
            ->getResultAsArray();

        $this->setView('Product/show.html.twig');
        $this->setTemplateEntities([
            'product' => $product,
            'productSizes' => $productSizes,
            'productImages' => $productImages,
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
//            'action' =>
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

        var_dump($form->getData());
        var_dump($request->files->get('file'));
//        die;

        if ($form->isSubmitted() && $form->isValid()) {
            var_dump($_FILES);
            var_dump($request->files->get('file'));
            var_dump($form);
            die;

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
    }

    /**
     * @Route("/product/edit/{pathSlug}", name="product_edit")
     *
     * @param Request $request
     * @param string $pathSlug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, string $pathSlug)
    {
        $product = $this->productRepository
            ->readOneEntityBy([
                'pathSlug' => $pathSlug
            ])
            ->getResult();

        if (!$product) {
            $this->addFlash('warning', 'No product found!');

            return $this->redirectToRoute('product_list');
        }

        $sizes = $this->sizeRepository
            ->readAllEntities()
            ->getResult();

        $sizesChoices = [];
        foreach ($sizes as $s) {
            $sizesChoices[$s->getName()] = $s->getId();
        }

        $productSizes = $this->productSizeRepository
            ->readEntitiesBy(['idProducts' => $product->getId()])
            ->getResultAsArray();

        $form = $this->createForm(ProductType::class, $product, [
            'isAdmin' => $this->isAdmin(),
        ]);

        foreach ($productSizes as $key => $productSize) {
//            var_dump($productSize);
//            var_dump(get_object_vars($productSize));
//            var_dump($productSize->getIdProducts());
            $size = $productSize->getIdSizes();
//            var_dump($size);

            $form->get('sizes')
                ->add($productSize->getId(), ChoiceType::class, [
                    'label' => 'Size',
                    'choices' => $sizesChoices,
                    'data' => $size->getId(),
//                    'choice_label' => 'name',
                    'placeholder' => 'Choose a size',
                    'required' => true,
                ]);

            $form->get('availabilities')
                ->add($productSize->getId(), NumberType::class, [
                    'label' => 'Availability',
                    'data' => $productSize->getAvailability(),
                    'empty_data' => 0,
                    'required' => true,
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            var_dump($product);
//            var_dump($form->getData());
//            var_dump($request->request);
//            var_dump($request->request->get('product')['availabilities']);

            foreach ($productSizes as $key => $productSize) {
//                var_dump($productSize->getId());
//                var_dump($request->request);

                $newSize = $this->sizeRepository
                    ->readOneEntityBy([
                        'id' => $request->request->get('product')['sizes'][$productSize->getId()]
                    ])->getResult();

                $this->productSizeRepository->updateEntity($productSize, [
                    'idSizes' => $newSize,
                    'availability' => $request->request->get('product')['availabilities'][$productSize->getId()]
                ]);
            }

            $this->productRepository->updateEntity($product, $form->getData());

//            return $this->redirectToRoute('product_show', [
//                'pathSlug' => $pathSlug,
//            ]);
        }

        return $this->render('Product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

//    public function ()
//    {
//
//    }
}
