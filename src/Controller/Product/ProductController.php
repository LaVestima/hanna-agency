<?php

namespace App\Controller\Product;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Product;
use App\Form\AddToCartType;
use App\Form\ProductType;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
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
     * @Route("/{pathSlug}", name="product_show")
     */
    public function show(Product $product)
    {
        $this->denyAccessUnlessGranted('view', $product);

        $form = $this->createForm(AddToCartType::class, $product);

        return $this->render('Product/show.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="product_new")
     */
    public function new(Request $request)
    {

    }

    /**
     * @Route("/edit/{pathSlug}", name="product_edit")
     */
    public function edit(Request $request, Product $product)
    {
        $this->denyAccessUnlessGranted('edit', $product);

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

            if ($this->isStore()) {
                return $this->redirectToRoute('inventory_home');
            } else {
                return $this->redirectToRoute('product_show', [
                    'pathSlug' => $product->getPathSlug(),
                ]);
            }
        }

        return $this->render('Product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/activate/{pathSlug}", name="product_activate")
     */
    public function activate(Product $product)
    {
        $this->denyAccessUnlessGranted('edit', $product);

        $product->setActive(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('inventory_home');
    }

    /**
     * @Route("/deactivate/{pathSlug}", name="product_deactivate")
     */
    public function deactivate(Product $product)
    {
        $this->denyAccessUnlessGranted('edit', $product);

        $product->setActive(false);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('inventory_home');
    }

    /**
     * @Route("/delete/{pathSlug}", name="product_delete")
     */
    public function delete(Product $product)
    {
        $this->denyAccessUnlessGranted('delete', $product);

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('inventory_home');
    }
}
