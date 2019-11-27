<?php

namespace App\Controller\Product;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Product;
use App\Form\AddToCartType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends BaseController
{
    private $productRepository;

    public function __construct(
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/show/{pathSlug}", name="product_show")
     */
    public function show(Product $product)
    {
        $this->denyAccessUnlessGranted('view', $product);

        $similarProducts = $this->productRepository->readRandomEntities(7);

        $addToCartForm = $this->createForm(AddToCartType::class, $product);

        return $this->render('Product/show.html.twig', [
            'product' => $product,
            'similarProducts' => $similarProducts,
            'addToCartForm' => $addToCartForm->createView()
        ]);
    }

    /**
     * @Route("/new", name="product_new")
     */
    public function new(Request $request)
    {
        $this->denyAccessUnlessGranted('create_product');

        $form = $this->createForm(ProductType::class, null, [
            'edit' => false,
            'isAdmin' => $this->isAdmin(),
            'isProducer' => $this->isStore()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product->setStore($this->getStore());

            $this->productRepository->createEntity($product);

            return $this->redirectToRoute('inventory_home');
        }

        return $this->render('Product/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{pathSlug}", name="product_edit")
     */
    public function edit(Request $request, Product $product)
    {
        $this->denyAccessUnlessGranted('edit', $product);

        $backUrl = $this->generateBackUrl($request);

        $form = $this->createForm(ProductType::class, $product, [
            'edit' => true,
            'isAdmin' => $this->isAdmin(),
            'isProducer' => $this->isStore()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirect($backUrl);
        }

        return $this->render('Product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'backUrl' => $backUrl
        ]);
    }

    /**
     * @Route("/activate/{pathSlug}", name="product_activate")
     */
    public function activate(Product $product, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $product);

        $product->setActive(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateBackUrl($request));
    }

    /**
     * @Route("/deactivate/{pathSlug}", name="product_deactivate")
     */
    public function deactivate(Product $product, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $product);

        $product->setActive(false);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateBackUrl($request));
    }

    /**
     * @Route("/delete/{pathSlug}", name="product_delete")
     */
    public function delete(Product $product, Request $request)
    {
        $this->denyAccessUnlessGranted('delete', $product);

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirect($this->generateBackUrl($request));
    }

    private function generateBackUrl(Request $request): string
    {
        $backUrlMap = [
            'show' => [
                'route' => 'product_show',
                'params' => [
                    'pathSlug' => $request->attributes->get('product')->getPathSlug()
                ]
            ],
            'inv' => [
                'route' => 'inventory_home',
                'params' => [
                    'page' => $request->query->get('page') ?? 1
                ]
            ]
        ];

        $routeInfo = $backUrlMap[$request->query->get('back')];

        return $this->generateUrl($routeInfo['route'], $routeInfo['params']);
    }
}
