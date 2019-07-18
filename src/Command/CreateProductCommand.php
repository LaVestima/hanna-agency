<?php

namespace App\Command;

use App\Entity\Product;
use App\Entity\ProductReview;
use App\Entity\ProductVariant;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductReviewRepository;
use App\Repository\ProductVariantRepository;
use App\Repository\StoreRepository;
use App\Repository\VariantRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProductCommand extends BaseCreateCommand
{
    private $categoryRepository;
    private $storeRepository;
    private $productRepository;
    private $productReviewRepository;
    private $productVariantRepository;
    private $variantRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        StoreRepository $storeRepository,
        ProductRepository $productRepository,
        ProductReviewRepository $productReviewRepository,
        ProductVariantRepository $productVariantRepository,
        VariantRepository $variantRepository,
        $name = null
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->storeRepository = $storeRepository;
        $this->productRepository = $productRepository;
        $this->productReviewRepository = $productReviewRepository;
        $this->productVariantRepository = $productVariantRepository;
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

                $variantCount = random_int(1, $this->variantRepository->countRows());

                for ($j = 0; $j < $variantCount; $j++) {
                    $this->createProductVariants($product);

                    $output->writeln('Product Variant');
                }

                for ($j = 0; $j < rand(1, 100); $j++) {
                    $this->createProductReview($product);

                    $output->writeln('Product Review');
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

        $randomProducer = $this->storeRepository
            ->readRandomEntities(1)->getResult();

        $product->setName($this->faker->text(50));
        $product->setPrice($this->faker->numberBetween(100, 99999)/100);
        $product->setCategory($randomCategory);
        $product->setStore($randomProducer);
        $product->setActive(random_int(0, 1));

        $this->productRepository
            ->createEntity($product);

        return $product;
    }

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

    private function createProductReview(Product $product)
    {
        $productReview = new ProductReview();
        $productReview
            ->setProduct($product)
            ->setRating(random_int(1, 5))
            ->setContent($this->faker->text);

        $this->productReviewRepository->createEntity($productReview);
    }
}
