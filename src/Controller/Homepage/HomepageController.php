<?php

namespace App\Controller\Homepage;

use App\Controller\Infrastructure\BaseController;
use App\Repository\ProductRepository;
use App\Repository\StoreRepository;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends BaseController
{
    private $productRepository;
    private $storeRepository;

    public function __construct(
        ProductRepository $productRepository,
        StoreRepository $storeRepository
    ) {
        $this->productRepository = $productRepository;
        $this->storeRepository = $storeRepository;
    }

    /**
     * @Route("/", name="homepage_homepage")
     */
    public function homepage()
    {
        // TODO: select the products recommended for the user

        $products = $this->productRepository->readRandomEntities(5);

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
