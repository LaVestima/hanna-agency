<?php

namespace App\Controller\Order;

use App\Controller\Infrastructure\BaseController;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends BaseController
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/cart", name="cart_home")
     *
     * @param Request $request
     * @return Response
     */
    public function home(Request $request)
    {
        $cart = $request->getSession()->get('cart');

        $products = $this->productRepository
            ->readCartProducts($cart)
            ->getResult();

//        var_dump($products);

        return $this->render('Order/cart.html.twig', [
            'products' => $products,
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/cart_summary", name="cart_summary")
     *
     * @param Request $request
     * @return Response
     */
    public function summary(Request $request)
    {
        return $this->render('Order/cart_summary.html.twig');
    }

    /**
     * @Route("/add_to_cart", name="add_product_to_cart", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function addProduct(Request $request)
    {
        $this->request = $request;
        $this->denyNonXhrs();

        $productPathSlug = $request->query->get('product');

//        var_dump($request->query->get('product'));
//        var_dump($request->request);

        $session = $request->getSession();

        $cart = $session->get('cart');
//        var_dump($cart);

        var_dump(array_key_exists($productPathSlug, $cart ?? []));

        if (array_key_exists($productPathSlug, $cart ?? [])) {
            $cart[$productPathSlug]['quantity']++;
        } else {
            $cart[$productPathSlug] = [
                'quantity' => 1
            ];
        }

        $session->set('cart', $cart);

//        var_dump($session->get('cart'));

        return new Response('ok');
    }
}
