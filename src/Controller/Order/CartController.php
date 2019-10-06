<?php

namespace App\Controller\Order;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Cart;
use App\Entity\CartProductVariant;
use App\Form\CartSummaryType;
use App\Form\PaymentType;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends BaseController
{
    private $cartRepository;
    private $productRepository;
    private $productVariantRepository;

    public function __construct(
        CartRepository $cartRepository,
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
    }

    /**
     * @Route("/cart", name="cart_home")
     */
    public function home(Request $request)
    {
        $cartProductVariants = $this->cartRepository->findOneBy([
            'sessionId' => $request->getSession()->getId()
        ]);

        return $this->render('Order/cart.html.twig', [
            'cartProductVariants' => $cartProductVariants ? $cartProductVariants->getCartProductVariants() : [],
        ]);
    }

    /**
     * @Route("/cart_summary", name="cart_summary")
     */
    public function summary(Request $request)
    {
        $form = $this->createForm(PaymentType::class, null, [
            'paymentChargeRoute' => $this->generateUrl('payment_charge')
        ]);

        $cartSummaryForm = $this->createForm(CartSummaryType::class, null, [
            'user' => $this->getUser(),
            'cartProductVariants' => $this->cartRepository->findOneBy([
                'sessionId' => $request->getSession()->getId()
            ])->getCartProductVariants()
        ]);

        return $this->render('Order/cart_summary.html.twig', [
            'form' => $form->createView(),
            'cartSummaryForm' => $cartSummaryForm->createView(),
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
            'addresses' => $this->getUser()->getAddresses()
        ]);
    }

    /**
     * @Route("/remove_from_cart/{identifier}", name="remove_from_cart")
     */
    public function removeProduct(string $identifier, Request $request)
    {
        // TODO: correct to new approach

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
     * @Route("/add_to_cart", name="add_product_to_cart",
     *     methods={"POST"},
     *     options={"expose"=true},
     *     condition="request.isXmlHttpRequest()"
     * )
     */
    public function addProduct(Request $request)
    {
        // TODO: check if product not from user's store

        $addToCartData = $request->request->get('add_to_cart');

        $productVariantIdentifier = $addToCartData['productVariant'] ?? null;
        $quantity = $addToCartData['quantity'];

        if ($productVariantIdentifier) {
            if ($this->isCsrfTokenValid('add_to_cart', $request->request->get('_csrf_token'))) {
                $productVariant = $this->productVariantRepository->findOneBy([
                    'identifier' => $productVariantIdentifier
                ]);

                if (!$productVariant) {
                    return $this->json('Product variant does not exist', 404);
                }

                if ($productVariant->getAvailability() < $addToCartData['quantity']) {
                    return $this->json('Not enough products available!', 409);
                }

                $session = $request->getSession();
                $sessionId = $session->getId();

                $cart = $this->cartRepository
                    ->findOneBy(['sessionId' => $sessionId]);

                if (!$cart) {
                    $cart = new Cart();
                    $cart->setSessionId($sessionId);
                    $cart->setUser($this->getUser());

                    $this->entityManager->persist($cart);
                    $this->entityManager->flush();
                }

                $cartProductVariant = $this->getDoctrine()
                    ->getRepository(CartProductVariant::class)
                    ->findOneBy([
                        'cart' => $cart,
                        'productVariant' => $productVariant
                    ]);

                if (!$cartProductVariant) {
                    $cartProductVariant = new CartProductVariant();
                    $cartProductVariant->setCart($cart);
                    $cartProductVariant->setProductVariant($productVariant);
                    $cartProductVariant->setQuantity($quantity);
                } else {
                    $cartProductVariant->setQuantity(
                        $cartProductVariant->getQuantity() + $quantity
                    );
                }

                $this->entityManager->persist($cartProductVariant);
                $this->entityManager->flush();

                return new Response(
                    $this->cartRepository->getTotalSessionQuantity($sessionId),
                    200
                );
            } else {
                // TODO: return error
            }
        } else {
            // TODO: return error
        }
    }
}
