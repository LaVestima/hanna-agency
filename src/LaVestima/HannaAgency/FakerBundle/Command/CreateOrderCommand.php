<?php

namespace LaVestima\HannaAgency\FakerBundle\Command;

use Faker\Factory;
use LaVestima\HannaAgency\OrderBundle\Entity\Orders;
use LaVestima\HannaAgency\OrderBundle\Entity\OrdersProducts;
use LaVestima\HannaAgency\ProductBundle\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateOrderCommand extends ContainerAwareCommand
{
    private $faker;

    public function __construct($name = null)
    {
        $this->faker = Factory::create();

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('faker:create:order')
            ->setDescription('Creates fake orders')
            ->addOption('number', null, InputOption::VALUE_OPTIONAL, 'Number of orders to create');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $orderNumber = (int)$input->getOption('number') ?: 1;

        if ($orderNumber < 1) {
            $output->writeln('Wrong argument!');
        } else {
            for ($i = 0; $i < $orderNumber; $i++) {
                $order = $this->createFakeOrder();

                $output->writeln('Order');

                $productNumber = $this->getContainer()->get('product_crud_controller')
                    ->countRows();

                for ($j = 0; $j < rand(1, $productNumber); $j++) {
                    $this->createFakeOrderProduct($order);

                    $output->writeln('OrderProduct');
                }

                $output->writeln('Created: ' . ($i+1));
            }
        }
    }

    private function createFakeOrder()
    {
        $order = new Orders();

        $randomCustomer = $this->getContainer()->get('customer_crud_controller')
            ->readRandomEntities(1);

        $randomUser = $this->getContainer()->get('user_crud_controller')
            ->readRandomEntities(1);

        $order->setDateCreated($this->faker->dateTime('now'));
        $order->setIdCustomers($randomCustomer);
        $order->setUserCreated($randomUser);

        $this->getContainer()->get('order_crud_controller')
            ->createEntity($order);

        return $order;
    }

    private function createFakeOrderProduct(Orders $order)
    {
        $orderProduct = new OrdersProducts();

        do {
            $randomProduct = $this->getContainer()->get('product_crud_controller')
                ->readRandomEntities(1);
        } while (!$this->isProductUniqueForOrder($randomProduct, $order));

        $randomStatus = $this->getContainer()->get('order_status_crud_controller')
            ->readRandomEntities(1);

        $orderProduct->setIdOrders($order);
        $orderProduct->setIdProducts($randomProduct);
        $orderProduct->setIdStatuses($randomStatus);
        $orderProduct->setQuantity($this->faker->numberBetween(1, 200));
        $orderProduct->setDiscount($this->faker->numberBetween(0, 100));

        $this->getContainer()->get('order_product_crud_controller')
            ->createEntity($orderProduct);

        return $orderProduct;
    }

    private function isProductUniqueForOrder(Products $product, Orders $order)
    {
        $orderProduct = $this->getContainer()->get('order_product_crud_controller')
            ->readOneEntityBy([
                'idProducts' => $product,
                'idOrders' => $order,
            ]);

        return $orderProduct ? false : true;
    }
}