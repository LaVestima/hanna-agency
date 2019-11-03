<?php

namespace App\Controller\Payment;

use App\Client\StripeClient;
use App\Controller\Infrastructure\BaseController;
use App\Entity\Cart;
use App\Entity\CartProductVariant;
use App\Entity\Order;
use App\Entity\OrderProductVariant;
use App\Enum\OrderStatus;
use App\Form\CartSummaryType;
use App\Helper\RandomHelper;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductVariantRepository;
use Exception;
use Stripe\Error\Base;
use Stripe\Error\Card;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payment")
 */
class PaymentController extends BaseController
{
    private $cartRepository;
    private $orderRepository;
    private $productVariantRepository;
    private $stripeClient;

    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var CartProductVariant[]
     */
    private $cartProductVariants;

    public function __construct(
        CartRepository $cartRepository,
        OrderRepository $orderRepository,
        ProductVariantRepository $productVariantRepository,
        StripeClient $stripeClient
    ) {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->stripeClient = $stripeClient;
    }

    /**
     * @Route("/charge", name="payment_charge", methods={"POST"})
     */
    public function charge(Request $request)
    {
        $form = $this->createForm(CartSummaryType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        $redirectUrl = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $this->cartProductVariants = $form->get('products')->getData();

            try {
                $this->cart = $this->cartRepository->findOneBy([
                    'sessionId' => $request->getSession()->getId()
                ]);

                if (empty($this->cartProductVariants)) {
                    throw new Exception('No products selected');
                    // TODO; redirect to error page
                }

                $order = $this->createNewOrder($form);

                $this->stripeClient->createCharge(
                    $order->getCode(),
                    $this->getUser(),
                    $this->calculateCartTotal(),
                    $form->get('token')->getData()
                );

                foreach ($this->cartProductVariants as $cartProductVariant) {
                    $productVariant = $cartProductVariant->getProductVariant();

                    $productVariant->setAvailability(
                        $productVariant->getAvailability() -
                        $cartProductVariant->getQuantity()
                    );

                    $this->entityManager->persist($productVariant);

                    $this->entityManager->remove($cartProductVariant);
                }

                $this->entityManager->persist($this->cart);

                $this->entityManager->flush();

                // TODO: update order status to PAID
                // TODO: if user wants it, store the charge id

                $redirectUrl = $this->generateUrl('payment_success');
            } catch (Base $e) {
                if ($this->isEnvDev()) {
                    var_dump($e->getMessage());die;

                }

                // TODO: maybe also store failed payments?

                $this->addFlash('warning', sprintf(
                        'Unable to take payment, %s',
                        $e instanceof Card ? lcfirst($e->getMessage()) : 'please try again.'
                    )
                );

                $redirectUrl = $this->generateUrl('payment_failure');
            } catch (Exception $e) {
                if ($this->isEnvDev()) {
                    var_dump($e->getMessage());
                }
            }
        } else {
            $redirectUrl = $this->generateUrl('payment_failure');
        }

        if (!$redirectUrl) { throw new HttpException(400); }

        return $this->redirect($redirectUrl);
    }

    private function calculateCartTotal()
    {
        $amount = 0;

        foreach ($this->cartProductVariants as $cartProductVariant) {
            $amount +=
                $cartProductVariant->getQuantity()
                * $cartProductVariant->getProductVariant()->getProduct()->getPrice();
        }

        return $amount;
    }

    private function createNewOrder(Form $form): Order
    {
        $orderCode = RandomHelper::generateString(24, 'N');

        $order = new Order();
        $order->setCode($orderCode);
        $order->setUser($this->getUser());
//        $order->setStatus(OrderStatus::QUEUED); // TODO: other way?

        $order->setShipmentOption($form->get('shipmentOption')->getData());
        $order->setAddress($form->get('address')->getData());

        $orderProductVariants = [];

        foreach ($this->cartProductVariants as $cartProductVariant) {
            $orderProductVariant = new OrderProductVariant();

            $orderProductVariant->setOrder($order);
            $orderProductVariant->setProductVariant($cartProductVariant->getProductVariant());
            $orderProductVariant->setQuantity($cartProductVariant->getQuantity());
            $orderProductVariant->setStatus(OrderStatus::PLACED);

            $orderProductVariants[] = $orderProductVariant;
        }

        $order->setOrderProductVariants($orderProductVariants);
        $this->orderRepository->createEntity($order);

        return $order;
    }

    /**
     * @Route("/success", name="payment_success")
     */
    public function success()
    {
        return $this->render('Payment/success.html.twig');
    }

    /**
     * @Route("/failure", name="payment_failure")
     */
    public function failure()
    {
        
    }
}
