<?php

namespace App\Controller\Payment;

use App\Client\StripeClient;
use App\Controller\Infrastructure\BaseController;
use App\Entity\Order;
use App\Entity\OrderProductVariant;
use App\Form\PaymentType;
use App\Helper\RandomHelper;
use App\Repository\OrderProductVariantRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use Stripe\Error\Base;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payment")
 */
class PaymentController extends BaseController
{
    private $orderProductVariantRepository;
    private $orderRepository;
    private $productRepository;
    private $productVariantRepository;
    private $stripeClient;

    public function __construct(
        OrderProductVariantRepository $orderProductVariantRepository,
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        StripeClient $stripeClient
    ) {
        $this->orderProductVariantRepository = $orderProductVariantRepository;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->stripeClient = $stripeClient;
    }

    /**
     * @Route("/charge", name="payment_charge", methods={"POST"})
     */
    public function charge(Request $request)
    {
        $form = $this->createForm(PaymentType::class);

        $form->handleRequest($request);

        $redirect = null;

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $cart = $request->getSession()->get('cart');

                if (empty($cart)) {
                    throw new \Exception('Cart is empty');
                }

                $productVariants = (!empty($cart)) ? $this->productVariantRepository
                    ->readCartProductVariants($cart ?? [])
                    ->getResultAsArray() : [];

                $amount = 0;

                foreach ($productVariants as $productVariant) {
                    $amount += $productVariant->getProduct()->getPriceCustomer();
                }

                $orderCode = RandomHelper::generateString(24, 'N');

                $this->stripeClient->createCharge(
                    $orderCode,
                    $this->getUser(),
                    $amount, // TODO: how to get amount?
                    $form->get('token')->getData()
                );

                $order = new Order();
                $order->setCode($orderCode);
                $order->setUser($this->getUser());
                $this->orderRepository->createEntity($order);

                foreach ($productVariants as $productVariant) {
                    $orderProductVariant = new OrderProductVariant();
                    $orderProductVariant->setOrder($order);
                    $orderProductVariant->setProductVariant($productVariant);
                    // TODO: ...
                    $this->orderProductVariantRepository->createEntity($orderProductVariant);
                }

                // TODO: add productVariants to order

                // TODO: update order status to PAID

                // TODO: if user wants it, store the charge id

                $request->getSession()->remove('cart');

                $redirect = $this->generateUrl('payment_success');
            } catch (Base $e) {
                if ($this->isEnvDev()) {
                    var_dump($e);
                }

                // TODO: maybe also store failed payments?

                $this->addFlash('warning', sprintf(
                        'Unable to take payment, %s',
                        $e instanceof \Stripe\Error\Card ? lcfirst($e->getMessage()) : 'please try again.'
                    )
                );

                $redirect = $this->generateUrl('payment_failure');
            } catch (\Exception $e) {
                if ($this->isEnvDev()) {
                    var_dump($e);
                }
            } finally {

            }
        } else {
            $redirect = $this->generateUrl('payment_failure');
        }

        if (!$redirect) { throw new HttpException(400); }

        return $this->redirect($redirect);
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
