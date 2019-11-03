<?php

namespace App\Command;

use App\Entity\Order;
use App\Entity\OrderProductVariant;
use App\Enum\OrderStatus;
use App\Helper\RandomHelper;
use App\Repository\AddressRepository;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use App\Repository\ShipmentOptionRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateOrderCommand extends BaseCreateCommand
{
    private $addressRepository;
    private $orderProductRepository;
    private $orderRepository;
    private $productRepository;
    private $productVariantRepository;
    private $shipmentOptionRepository;
    private $userRepository;

    public function __construct(
        AddressRepository $addressRepository,
        OrderProductRepository $orderProductRepository,
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        ShipmentOptionRepository $shipmentOptionRepository,
        UserRepository $userRepository,
        $name = null
    ) {
        $this->addressRepository = $addressRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->shipmentOptionRepository = $shipmentOptionRepository;
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

                $productNumber = $this->productRepository
                    ->countRows();

                $orderProductNumber = random_int(1, $productNumber);

                for ($j = 0; $j < $orderProductNumber; $j++) {
                    $this->createFakeOrderProduct($order);
                }

                $output->writeln('Order ' .  ($i+1));
            }

            $output->writeln('Created: ' . $i);
        }
    }

    private function createFakeOrder()
    {
        $order = new Order();

        $randomUser = $this->userRepository
            ->readRandomEntities(1)[0];

        $order->setDateCreated($this->faker->dateTimeBetween('-30 days', 'now'));
        $order->setCode(RandomHelper::generateString(24, 'N'));
        $order->setUser($randomUser);
        $order->setShipmentOption(
            $this->shipmentOptionRepository->readRandomEntities(1)[0]
        );
        $order->setAddress(
            $this->addressRepository->readRandomEntities(1)[0]
        );
        $order->setUserCreated($randomUser);

        $this->orderRepository
            ->createEntity($order);

        return $order;
    }

    private function createFakeOrderProduct(Order $order)
    {
        $orderProductVariant = new OrderProductVariant();

        $randomProductVariant = $this->productVariantRepository
            ->readRandomEntities(1)[0];

        $randomStatus = OrderStatus::getConstants()[array_rand(OrderStatus::getConstants())];

        $orderProductVariant->setOrder($order);
        $orderProductVariant->setProductVariant($randomProductVariant);
        $orderProductVariant->setStatus($randomStatus);
        $orderProductVariant->setQuantity($this->faker->numberBetween(1, 10));
        $orderProductVariant->setDiscount($this->faker->numberBetween(0, 100));

        $this->orderProductRepository->createEntity($orderProductVariant);

        return $orderProductVariant;
    }
}
