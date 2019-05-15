<?php

namespace App\Command;

use App\Entity\Order;
use App\Entity\OrderProductVariant;
use App\Entity\ProductSize;
use App\Helper\RandomHelper;
use App\Repository\CustomerRepository;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStatusRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductSizeRepository;
use App\Repository\ProductVariantRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateOrderCommand extends BaseCreateCommand
{
    private $customerRepository;
    private $orderProductRepository;
    private $orderRepository;
    private $orderStatusRepository;
    private $productRepository;
//    private $productSizeRepository;
    private $productVariantRepository;
    private $userRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        OrderProductRepository $orderProductRepository,
        OrderRepository $orderRepository,
        OrderStatusRepository $orderStatusRepository,
        ProductRepository $productRepository,
//        ProductSizeRepository $productSizeRepository,
        ProductVariantRepository $productVariantRepository,
        UserRepository $userRepository,
        $name = null
    ) {
        $this->customerRepository = $customerRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->orderRepository = $orderRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->productRepository = $productRepository;
//        $this->productSizeRepository = $productSizeRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->userRepository = $userRepository;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('faker:create:order')
            ->setDescription('Creates fake orders')
            ->addArgument('number', InputArgument::OPTIONAL, 'Number of orders to create', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $orderNumber = (int)$input->getArgument('number') ?: 1;

        if ($orderNumber < 1) {
            $output->writeln('Wrong argument!');
        } else {
            for ($i = 0; $i < $orderNumber; $i++) {
                $order = $this->createFakeOrder();

                $output->writeln('Order');

                $productNumber = $this->productRepository
                    ->countRows();

                for ($j = 0; $j < rand(1, $productNumber); $j++) {
                    $this->createFakeOrderProduct($order);

                    $output->writeln('OrderProductVariant');
                }

                $output->writeln('Created: ' . ($i+1));
            }
        }
    }

    private function createFakeOrder()
    {
        $order = new Order();

        $randomUser = $this->userRepository
            ->readRandomEntities(1)
            ->getResult();

        $order->setDateCreated($this->faker->dateTimeBetween('-7 days', 'now'));
        $order->setCode(RandomHelper::generateString(24, 'N'));
        $order->setUser($randomUser);
        $order->setUserCreated($randomUser);

        $this->orderRepository
            ->createEntity($order);

        return $order;
    }

    private function createFakeOrderProduct(Order $order)
    {
        $orderProductVariant = new OrderProductVariant();

        $randomProductVariant = $this->productVariantRepository
            ->readRandomEntities(1)
            ->getResult();

//        do {
//            $randomProductSize = $this->productSizeRepository
//                ->readRandomEntities(1)
//                ->getResult();
//        } while (!$this->isProductUniqueForOrder($randomProductSize, $order));

        $randomStatus = $this->orderStatusRepository
            ->readRandomEntities(1)->getResult();

        $orderProductVariant->setOrder($order);
        $orderProductVariant->setProductVariant($randomProductVariant);
        $orderProductVariant->setStatus($randomStatus);
        $orderProductVariant->setQuantity($this->faker->numberBetween(1, 10));
        $orderProductVariant->setDiscount($this->faker->numberBetween(0, 100));

        $this->orderProductRepository->createEntity($orderProductVariant);

        return $orderProductVariant;
    }

    private function isProductUniqueForOrder(ProductSize $productSize, Order $order)
    {
        $orderProduct = $this->orderProductRepository
            ->readOneEntityBy([
                'idProductsSizes' => $productSize->getId(),
                'idOrders' => $order->getId(),
            ])->getResult();

        return $orderProduct ? false : true;
    }
}
