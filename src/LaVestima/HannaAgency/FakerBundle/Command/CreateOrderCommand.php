<?php

namespace LaVestima\HannaAgency\FakerBundle\Command;

use Faker\Factory;
use LaVestima\HannaAgency\OrderBundle\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateOrderCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('faker:create:order')
            ->setDescription('Creates a fake order')
            ->addOption('number', null, InputOption::VALUE_OPTIONAL, 'Number of orders to create');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $orderNumber = $input->getOption('number');

        for ($i = 0; $i < $orderNumber; $i++) {
            $this->createFakeOrder();
        }

        $output->writeln($orderNumber . ' order' . ($orderNumber == 1 ? '' : 's') . ' created!');
    }

    private function createFakeOrder()
    {
        $faker = Factory::create();

        $order = new Orders();

        $randomCustomer = $this->getContainer()->get('customer_crud_controller')
            ->readRandomEntities(1);

        $randomUser = $this->getContainer()->get('user_crud_controller')
            ->readRandomEntities(1);

        $order->setDateCreated($faker->dateTime('now'));
        $order->setDatePlaced($faker->dateTime('now'));
        $order->setIdCustomers($randomCustomer);
        $order->setUserCreated($randomUser);

        $this->getContainer()->get('order_crud_controller')
            ->createEntity($order);

        // TODO: add OrdersProducts
    }
}