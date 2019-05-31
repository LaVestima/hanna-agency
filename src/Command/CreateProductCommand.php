<?php

namespace App\Command;

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Entity\Size;
use App\Repository\CategoryRepository;
use App\Repository\ProducerRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use App\Repository\SizeRepository;
use App\Repository\VariantRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProductCommand extends BaseCreateCommand
{
    private $categoryRepository;
    private $producerRepository;
    private $productRepository;
    private $productVariantRepository;
    private $sizeRepository;
    private $variantRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        ProducerRepository $producerRepository,
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        SizeRepository $sizeRepository,
        VariantRepository $variantRepository,
        $name = null
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->producerRepository = $producerRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->sizeRepository = $sizeRepository;
        $this->variantRepository = $variantRepository;

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

                $variantCount = rand(1, $this->variantRepository->countRows());

                for ($j = 0; $j < $variantCount; $j++) {
                    $this->createProductVariants($product);

                    $output->writeln('Product Variant');
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
        $product->setPrice($this->faker->numberBetween(100, 99999)/100);
        $product->setCategory($randomCategory);
        $product->setProducer($randomProducer);
        $product->setActive(rand(0, 1));

        $this->productRepository
            ->createEntity($product);

        return $product;
    }

//    private function createFakeProductSize(Product $product)
//    {
//        $productSize = new ProductSize();
//
//        // TODO: check uniqueness, doesn't work
//        do {
//            $randomSize = $this->sizeRepository
//                ->readRandomEntities(1)->getResult();
//        } while (!$this->isSizeUniqueForProduct($randomSize, $product));
//
//        $productSize->setIdProducts($product);
//        $productSize->setAvailability($this->faker->numberBetween(0, 200));
//        $productSize->setIdSizes($randomSize);
//
//        $this->productSizeRepository
//            ->createEntity($productSize);
//    }

    private function createProductVariants(Product $product)
    {
        $productVariant = new ProductVariant();

        $randomVariant = $this->variantRepository
            ->readRandomEntities(1)->getResult();

        $productVariant
            ->setProduct($product)
            ->setVariant($randomVariant)
            ->setAvailability($this->faker->numberBetween(0, 2000));

        $this->productVariantRepository->createEntity($productVariant);
    }

//    private function isSizeUniqueForProduct(Size $size, Product $product)
//    {
//        $productSize = $this->productSizeRepository
//            ->readOneEntityBy([
//                'idSizes' => $size->getId(),
//                'idProducts' => $product->getId(),
//            ])->getResult();
//
//        return $productSize ? false : true;
//    }
}