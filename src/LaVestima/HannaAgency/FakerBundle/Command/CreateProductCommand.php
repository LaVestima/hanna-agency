<?php

namespace LaVestima\HannaAgency\FakerBundle\Command;

use Faker\Factory;
use LaVestima\HannaAgency\ProductBundle\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProductCommand extends ContainerAwareCommand
{
    private $faker;

    public function __construct($name = null)
    {
        $this->faker = Factory::create();

        parent::__construct($name);
    }

    public function configure()
    {
        $this
            ->setName('faker:create:product')
            ->setDescription('Creates fake products')
            ->addOption('number', null, InputOption::VALUE_OPTIONAL, 'Number of products to create');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $productNumber = (int)$input->getOption('number') ?: 1;

        if ($productNumber < 1) {
            $output->writeln('Wrong argument!');
        } else {
            for ($i = 0; $i < $productNumber; $i++) {
                $this->createFakeProduct();

                $output->writeln('Product');

                $output->writeln('Created: ' . ($i+1));
            }
        }
    }

    private function createFakeProduct()
    {
        $product = new Products();

        $randomCategory = $this->getContainer()->get('category_crud_controller')
            ->readRandomEntities(1);

        $randomProducer = $this->getContainer()->get('producer_crud_controller')
            ->readRandomEntities(1);

        $product->setName($this->faker->text(50));
        $product->setPriceProducer($this->faker->numberBetween(100, 99999)/100);
        $product->setPriceCustomer($this->faker->numberBetween(100, 99999)/100);
        $product->setQrCodePath($this->faker->text(20));
        $product->setIdCategories($randomCategory);
        $product->setIdProducers($randomProducer);

        $this->getContainer()->get('product_crud_controller')
            ->createEntity($product);
    }
}