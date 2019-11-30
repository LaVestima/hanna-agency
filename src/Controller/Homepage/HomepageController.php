<?php

namespace App\Controller\Homepage;

use App\Controller\Infrastructure\BaseController;
use App\Entity\MLModel;
use App\Repository\MLModelRepository;
use App\Repository\ProductRepository;
use App\Repository\StoreRepository;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends BaseController
{
    private $modelRepository;
    private $productRepository;
    private $storeRepository;

    public function __construct(
        MLModelRepository $modelRepository,
        ProductRepository $productRepository,
        StoreRepository $storeRepository
    ) {
        $this->modelRepository = $modelRepository;
        $this->productRepository = $productRepository;
        $this->storeRepository = $storeRepository;
    }

    /**
     * @Route("/", name="homepage_homepage")
     */
    public function homepage()
    {
        if ($this->getUser()) {
            // TODO: get the most recent one
            /** @var MLModel $recommendedProductsMLM */
            $recommendedProductsMLM = $this->modelRepository->findOneBy([
                'user' => $this->getUser()
            ]);

            $products = $this->productRepository->readRecommendedProducts($recommendedProductsMLM);
        } else {
            $products = $this->productRepository->readRecommendedProducts();
        }

        $stores = $this->storeRepository->findTop(3);

        return $this->render('Homepage/homepage.html.twig', [
            'products' => $products,
            'stores' => $stores
        ]);
    }

    /**
     * @Route("/contact", name="homepage_contact")
     */
    public function contact()
    {
        return $this->render('Homepage/contact.html.twig');
    }
}
