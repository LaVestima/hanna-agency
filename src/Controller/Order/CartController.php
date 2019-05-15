<?php

namespace App\Controller\Order;

use App\Controller\Infrastructure\BaseController;
use App\Form\PaymentType;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends BaseController
{
    private $productRepository;
    private $productVariantRepository;

    public function __construct(
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
    }

    /**
     * @Route("/cart", name="cart_home")
     */
    public function home(Request $request)
    {
        $cart = $request->getSession()->get('cart');

        $productVariants = (!empty($cart)) ? $this->productVariantRepository
            ->readCartProductVariants($cart ?? [])
            ->getResultAsArray() : [];

        if ($productVariants && count($productVariants) !== count($cart)) {
            foreach ($cart as $cartItemIdentifier => $cartItem) {
                $productVariantOk = false;

                foreach ($productVariants as $productVariant) {
                    if ($cartItemIdentifier == $productVariant->getIdentifier()) {
                        $productVariantOk = true;
                    }
                }

                if (!$productVariantOk) {
                    unset($cart[$cartItemIdentifier]);
                }
            }

            $request->getSession()->set('cart', $cart);
        }

        return $this->render('Order/cart.html.twig', [
            'productVariants' => $productVariants,
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/cart_summary", name="cart_summary")
     */
    public function summary(Request $request)
    {
        // TODO: get products from session
        // TODO: save products from cart to finalCart in session (block the changes in cart)
        // or make the user select the products they want to buy

        $form = $this->createForm(PaymentType::class, null, [
            'paymentChargeRoute' => $this->generateUrl('payment_charge')
        ]);

        return $this->render('Order/cart_summary.html.twig', [
            'form' => $form->createView(),
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
        ]);
    }

    /**
     * @Route("/remove_from_cart/{identifier}", name="remove_product_from_cart")
     */
    public function removeProduct(string $identifier, Request $request)
    {
        $productVariant = $this->productVariantRepository
            ->readOneEntityBy([
                'identifier' => $identifier
            ])->getResult();

        if (!$productVariant) {
            // TODO: redirect to /cart with error


            return $this->redirectToRoute('cart_home');
        }

        $cart = $request->getSession()->get('cart');

        if (array_key_exists($productVariant->getIdentifier(), $cart)) {
            unset($cart[$productVariant->getIdentifier()]);

            $request->getSession()->set('cart', $cart);
        }

        return $this->redirectToRoute('cart_home');
    }

    /**
     * @Route("/add_to_cart", name="add_product_to_cart", options={"expose"=true})
     */
    public function addProduct(Request $request)
    {
        $addToCartData = $request->query->get('add_to_cart');

        $this->request = $request;
        $this->denyNonXhrs();

        $productVariantIdentifier = $addToCartData['productVariant'] ?? null;

        if ($productVariantIdentifier) {
            if ($this->isCsrfTokenValid('add_to_cart', $request->query->get('_csrf_token'))) {
                $session = $request->getSession();

                $cart = $session->get('cart');

                if (array_key_exists($productVariantIdentifier, $cart ?? [])) {
                    $cart[$productVariantIdentifier]['quantity'] += $addToCartData['quantity'];
                } else {
                    $cart[$productVariantIdentifier] = [
                        'quantity' => (int)$addToCartData['quantity']
                    ];
                }

                $session->set('cart', $cart);

                // TODO: add session data to DB, for further retrieval
            }
        }

        return new Response('ok');
    }
}
