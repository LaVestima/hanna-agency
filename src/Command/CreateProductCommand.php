<?php

namespace App\Command;

use App\Entity\Product;
use App\Entity\ProductSize;
use App\Entity\Size;
use App\Repository\CategoryRepository;
use App\Repository\ProducerRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductSizeRepository;
use App\Repository\SizeRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProductCommand extends BaseCreateCommand
{
    private $categoryRepository;
    private $producerRepository;
    private $productRepository;
    private $productSizeRepository;
    private $sizeRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        ProducerRepository $producerRepository,
        ProductRepository $productRepository,
        ProductSizeRepository $productSizeRepository,
        SizeRepository $sizeRepository,
        $name = null
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->producerRepository = $producerRepository;
        $this->productRepository = $productRepository;
        $this->productSizeRepository = $productSizeRepository;
        $this->sizeRepository = $sizeRepository;

        parent::__construct($name);
    }

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

                $sizeNumber = $this->sizeRepository
//                $sizeNumber = $this->getContainer()->get('size_crud_controller')
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
        $product = new Product();

        $randomCategory = $this->categoryRepository
            ->readRandomEntities(1)->getResult();

        $randomProducer = $this->producerRepository
            ->readRandomEntities(1)->getResult();

        $product->setName($this->faker->text(50));
        $product->setPriceProducer($this->faker->numberBetween(100, 99999)/100);
        $product->setPriceCustomer($this->faker->numberBetween(100, 99999)/100);
        $product->setIdCategories($randomCategory);
        $product->setIdProducers($randomProducer);

        $this->productRepository
            ->createEntity($product);

        return $product;
    }

    private function createFakeProductSize(Product $product)
    {
        $productSize = new ProductSize();

        // TODO: check uniqueness, doesn't work
        do {
//            $randomSize = $this->getContainer()->get('size_crud_controller')
            $randomSize = $this->sizeRepository
                ->readRandomEntities(1)->getResult();
        } while (!$this->isSizeUniqueForProduct($randomSize, $product));

        $productSize->setIdProducts($product);
        $productSize->setAvailability($this->faker->numberBetween(0, 200));
        $productSize->setIdSizes($randomSize);

        $this->productSizeRepository
            ->createEntity($productSize);
    }

    private function isSizeUniqueForProduct(Size $size, Product $product)
    {
        $productSize = $this->productSizeRepository
            ->readOneEntityBy([
                'idSizes' => $size->getId(),
                'idProducts' => $product->getId(),
            ])->getResult();

        return $productSize ? false : true;
    }
}