<?php

namespace App\Controller\Payment;

use App\Client\StripeClient;
use App\Controller\Infrastructure\BaseController;
use App\Entity\Order;
use App\Entity\OrderProductVariant;
use App\Entity\OrderStatus;
use App\Form\PaymentType;
use App\Helper\RandomHelper;
use App\Repository\OrderRepository;
use App\Repository\OrderStatusRepository;
use App\Repository\ProductVariantRepository;
use Exception;
use Stripe\Error\Base;
use Stripe\Error\Card;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payment")
 */
class PaymentController extends BaseController
{
    private $orderRepository;
    private $orderStatusRepository;
    private $productVariantRepository;
    private $stripeClient;

    private $cart;
    private $productVariants;

    public function __construct(
        OrderRepository $orderRepository,
        OrderStatusRepository $orderStatusRepository,
        ProductVariantRepository $productVariantRepository,
        StripeClient $stripeClient
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderStatusRepository = $orderStatusRepository;
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

        $redirectUrl = null;

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->cart = $request->getSession()->get('cart');

                if (empty($this->cart)) {
                    throw new Exception('Cart is empty');
                    // TODO; redirect to error page
                }

                $this->productVariants = $this->productVariantRepository
                    ->readCartProductVariants($this->cart ?? [])
                    ->getResultAsArray();

                $order = $this->createNewOrder();

                $this->stripeClient->createCharge(
                    $order->getCode(),
                    $this->getUser(),
                    $this->calculateCartTotal(),
                    $form->get('token')->getData()
                );

                foreach ($this->productVariants as $productVariant) {
                    $this->productVariantRepository->updateEntity($productVariant, [
                        'availability' =>
                            $productVariant->getAvailability() - $this->cart[$productVariant->getIdentifier()]['quantity']
                    ]);
                }

                // TODO: update order status to PAID

                // TODO: if user wants it, store the charge id

                $request->getSession()->remove('cart');

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

        foreach ($this->productVariants as $productVariant) {
            $amount +=
                $this->cart[$productVariant->getIdentifier()]['quantity']
                * $productVariant->getProduct()->getPrice();
        }

        return $amount;
    }

    private function createNewOrder(): Order
    {
        $orderCode = RandomHelper::generateString(24, 'N');

        $order = new Order();
        $order->setCode($orderCode);
        $order->setUser($this->getUser());
//        $order->setStatus(OrderStatus::QUEUED); // TODO: other way?

        $orderProductVariants = [];

        foreach ($this->productVariants as $productVariant) {
            $orderProductVariant = new OrderProductVariant();

            $orderProductVariant->setOrder($order);
            $orderProductVariant->setProductVariant($productVariant);
            $orderProductVariant->setQuantity($this->cart[$productVariant->getIdentifier()]['quantity']);
            $orderProductVariant->setStatus($this->orderStatusRepository->findOneBy(['name' => OrderStatus::PLACED]));

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
