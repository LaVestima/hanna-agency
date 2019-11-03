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
use App\Repository\UserRepository;
use App\Repository\VariantRepository;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateProductCommand extends BaseCreateCommand
{
    private $categoryRepository;
    private $storeRepository;
    private $productRepository;
    private $productReviewRepository;
    private $productVariantRepository;
    private $userRepository;
    private $variantRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        StoreRepository $storeRepository,
        ProductRepository $productRepository,
        ProductReviewRepository $productReviewRepository,
        ProductVariantRepository $productVariantRepository,
        UserRepository $userRepository,
        VariantRepository $variantRepository,
        $name = null
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->storeRepository = $storeRepository;
        $this->productRepository = $productRepository;
        $this->productReviewRepository = $productReviewRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->userRepository = $userRepository;
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
        $io = new SymfonyStyle($input, $output);
        $progressBar = new ProgressBar($output, 50);

        $productNumber = (int)$input->getArgument('number') ?? 1;

        $progressBar->setFormat('debug');

        $progressBar->start();
        $progressBar->setMaxSteps($productNumber);

        if ($productNumber < 1) {
            $output->writeln('Wrong argument!');
        } else {
            for ($i = 0; $i < $productNumber; $i++) {
                $product = $this->createFakeProduct();

                $variantCount = random_int(1, 3);

                for ($j = 0; $j < $variantCount; $j++) {
                    $this->createProductVariants($product);
                }

                $productReviewNumber = random_int(1, 10);

                for ($j = 0; $j < $productReviewNumber; $j++) {
                    $this->createProductReview($product);
                }

                unset($product, $variantCount, $productReviewNumber);

                $progressBar->advance();
            }
        }

        $progressBar->finish();
    }

    private function createFakeProduct(): Product
    {
        $product = new Product();

        $randomCategory = $this->categoryRepository
            ->readRandomEntities(1)[0];

        $randomProducer = $this->storeRepository
            ->readRandomEntities(1)[0];

        $product->setName($this->faker->text(50));
        $product->setPrice($this->faker->numberBetween(100, 99999)/100);
        $product->setCategory($randomCategory);
        $product->setStore($randomProducer);
        $product->setDescription($this->faker->text);
        $product->setActive(random_int(0, 1));

        $this->productRepository
            ->createEntity($product);

        unset($randomCategory, $randomProducer);

        return $product;
    }

    private function createProductVariants(Product $product): void
    {
        $productVariant = new ProductVariant();

        $randomVariant = $this->variantRepository
            ->readRandomEntities(1)[0];

        $productVariant
            ->setProduct($product)
            ->setVariant($randomVariant)
            ->setAvailability($this->faker->numberBetween(0, 2000));

        $this->productVariantRepository->createEntity($productVariant);

        unset($productVariant);
    }

    private function createProductReview(Product $product): void
    {
        $randomUser = $this->userRepository->readRandomEntities(1)[0];

        $productReview = $this->productReviewRepository->findOneBy([
            'product' => $product,
            'user' => $randomUser
        ]);

        if (!$productReview) {
            $productReview = new ProductReview();
            $productReview
                ->setProduct($product)
                ->setUser($randomUser)
                ->setRating(random_int(1, 5))
                ->setContent($this->faker->text);

            $this->productReviewRepository->createEntity($productReview);
        }

        unset($productReview);
    }
}
