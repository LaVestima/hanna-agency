<?php

namespace App\Controller\Product;

use App\Controller\Infrastructure\BaseController;
use App\Entity\ProductImage;
use App\Form\AddToCartType;
use App\Form\ProductType;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
{
    private $kernel;
    private $productRepository;
    private $productImageRepository;

    public function __construct(
        KernelInterface $kernel,
        ProductRepository $productRepository,
        ProductImageRepository $productImageRepository
    ) {
        $this->kernel = $kernel;
        $this->productRepository = $productRepository;
        $this->productImageRepository = $productImageRepository;
    }

    /**
     * @Route("/product/list", name="product_list")
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
     */
    public function show(string $pathSlug)
    {
        $product = $this->productRepository
            ->readOneEntityBy(['pathSlug' => $pathSlug])
            ->getResult();

        if (!$product) { // TODO: or product not active (except assigned producer)
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(AddToCartType::class, $product, [
//            'edit' => true,
//            'isAdmin' => $this->isAdmin(),
//            'isProducer' => $this->isProducer()
        ]);

        $this->setView('Product/show.html.twig');
        $this->setTemplateEntities([
            'product' => $product,
            'form' => $form->createView()
        ]);

        return parent::baseShow();
    }

    /**
     * @Route("/product/new", name="product_new")
     */
    public function new(Request $request)
    {

    }

    /**
     * @Route("/product/edit/{pathSlug}", name="product_edit")
     */
    public function edit(Request $request, string $pathSlug)
    {
        $product = $this->productRepository
            ->readOneEntityBy([
                'pathSlug' => $pathSlug
            ])
            ->getResult();

        if (!$product) { throw new NotFoundHttpException(); }

        $form = $this->createForm(ProductType::class, $product, [
            'edit' => true,
            'isAdmin' => $this->isAdmin(),
            'isProducer' => $this->isProducer()
        ]);

        $form->handleRequest($request);

        // TODO: doesn't work if middle/first images are removed
        if ($form->isSubmitted() && $form->isValid()) {
            $productImages = $product->getProductImages();
            echo 'Count productImages: ' . count($productImages) . '<br>'; // total in form; old + new
            $hashedImages = [];
//var_dump(array_keys($productImages));
            $productImageIndex = 0;

            foreach ($productImages as &$productImage) {
//                echo ($productImageIndex + 1) . '<br>';
                echo $productImage->getSequencePosition() . '<br>';
                echo ($productImage->getFilePath() ?? 'NO') . '<br>';

                if ($productImage->getFilePath() && file_exists($productImage->getFilePath())) {
                    echo md5_file($productImage->getFilePath()) . '<br>';
                    $hashedImages[] = md5_file($productImage->getFilePath());
                    $productImage->setSequencePosition($productImageIndex + 1);
//                    $productImages[$productImageIndex]->setSequencePosition($productImageIndex + 1);
                } else {
                    $productImages->removeElement($productImage);
                }

                $productImageIndex++;
                unset($productImage);
            }

            $newProduct = $form->getData();

            $images = $form->get('images');
            echo 'Count images ' . count($images) . '<br>';

            foreach ($images as $imageIndex => $image) { // New files added in the form
                $uploadedProductImage = $image->get('file')->getData();

//                echo ($imageIndex + 1) . '<br>';
                echo ($uploadedProductImage ? $uploadedProductImage->getRealPath() : 'NO') . '<br>';

                if ($uploadedProductImage) {
                    $tmpFilePath = $uploadedProductImage->getRealPath();
                    $fileName = md5(uniqid()) . '.' . $uploadedProductImage->guessExtension();

                    if (!in_array(md5_file($tmpFilePath), $hashedImages)) {
                        $uploadDir = $this->kernel->getProjectDir() . '/public/uploads/images/';

                        try {
                            $uploadedProductImage->move(
                                $uploadDir,
                                $fileName
                            );
                        } catch (FileException $e) {
                            // TODO: handle exception if something happens during file upload
                        }

                        $addedProductImage = new ProductImage();
                        $addedProductImage->setFilePath('uploads/images/' . $fileName);
                        $addedProductImage->setProduct($product);
                        $addedProductImage->setSequencePosition($imageIndex + 1);

                        $productImages->add($addedProductImage);
                    }
                }
            }

            foreach ($productImages as $productImage) {
//                echo ($productImage->getFilePath() ?? 'NO') . '<br>';
                var_dump($productImage->getFilePath(), $productImage->getSequencePosition());
                echo '<br>';
            }

            $newProduct->setProductImages($productImages);
//            var_dump($newProduct->getProductImages());

            foreach ($newProduct->getProductImages() as $pi) {
                echo $pi->getId() . '<br>';
            }

//            die;

//            var_dump($form->getData());die;

            $this->productRepository->updateEntity($product, $newProduct);

            if ($this->isProducer()) {
                return $this->redirectToRoute('inventory_home');
            } else {
                return $this->redirectToRoute('product_show', [
                    'pathSlug' => $pathSlug,
                ]);
            }
        }

        return $this->render('Product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
