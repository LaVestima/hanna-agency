<?php

namespace LaVestima\HannaAgency\FakerBundle\Command;

use LaVestima\HannaAgency\ProductBundle\Entity\Products;
use LaVestima\HannaAgency\ProductBundle\Entity\ProductsSizes;
use LaVestima\HannaAgency\ProductBundle\Entity\Sizes;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProductCommand extends BaseCreateCommand
{
    public function configure()
    {
        $this
            ->setName('faker:create:product')
            ->setDescription('Creates fake products')
            ->addArgument('number', InputArgument::OPTIONAL, 'Number of products to create', 1);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $productNumber = (int)$input->getArgument('number') ?? 1;

        if ($productNumber < 1) {
            $output->writeln('Wrong argument!');
        } else {
            for ($i = 0; $i < $productNumber; $i++) {
                $product = $this->createFakeProduct();

                $sizeNumber = $this->getContainer()->get('size_crud_controller')
                    ->countRows();

                for ($j = 0; $j < rand(1, $sizeNumber); $j++) {
                    $this->createFakeProductSize($product);

                    $output->writeln('Product Size');
                }

                $output->writeln('Product');
            }

            $output->writeln('Created: ' . $i);
        }
    }

    private function createFakeProduct()
    {
        $product = new Products();

        $randomCategory = $this->getContainer()->get('category_crud_controller')
            ->readRandomEntities(1)->getResult();

        $randomProducer = $this->getContainer()->get('producer_crud_controller')
            ->readRandomEntities(1)->getResult();

        $product->setName($this->faker->text(50));
        $product->setPriceProducer($this->faker->numberBetween(100, 99999)/100);
        $product->setPriceCustomer($this->faker->numberBetween(100, 99999)/100);
        $product->setIdCategories($randomCategory);
        $product->setIdProducers($randomProducer);

        $this->getContainer()->get('product_crud_controller')
            ->createEntity($product);

        return $product;
    }

    private function createFakeProductSize(Products $product)
    {
        $productSize = new ProductsSizes();

        // TODO: check uniqueness, doesn't work
        do {
            $randomSize = $this->getContainer()->get('size_crud_controller')
                ->readRandomEntities(1)->getResult();
        } while (!$this->isSizeUniqueForProduct($randomSize, $product));

        $productSize->setIdProducts($product);
        $productSize->setAvailability($this->faker->numberBetween(0, 200));
        $productSize->setIdSizes($randomSize);

        $this->getContainer()->get('product_size_crud_controller')
            ->createEntity($productSize);
    }

    private function isSizeUniqueForProduct(Sizes $size, Products $product)
    {
        $productSize = $this->getContainer()->get('product_size_crud_controller')
            ->readOneEntityBy([
                'idSizes' => $size->getId(),
                'idProducts' => $product->getId(),
            ])->getResult();

        return $productSize ? false : true;
    }
}