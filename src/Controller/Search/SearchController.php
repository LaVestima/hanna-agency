<?php

namespace App\Controller\Search;

use App\Controller\Infrastructure\BaseController;
use App\Form\AdvancedSearchType;
use App\Form\SearchBarType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends BaseController
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/search", name="search_home")
     */
    public function home(Request $request)
    {
        $searchQuery = trim($request->query->get('query'));
        $searchCategory = trim($request->query->get('category'));

        $form = $this->createForm(AdvancedSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->productRepository->getProductsByAdvancedSearch(
                $form->get('query')->getData(),
                $form->get('category')->getData(),
                $form->get('priceMin')->getData(),
                $form->get('priceMax')->getData(),
                $form->get('sorting')->getData()
            );
        } else {
            $products = $this->productRepository
                ->getProductsByAdvancedSearch($searchQuery, $searchCategory);

            $form->get('query')->setData($searchQuery);
            $form->get('category')->setData($searchCategory);
        }

        return $this->render('Search/search.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'searchQuery' => $searchQuery
        ]);
    }

    public function searchBar(string $searchQuery = '')
    {
        $form = $this->createForm(SearchBarType::class, null, [
            'action' => $this->generateUrl('search_home')
        ]);

        $form->get('query')->setData($searchQuery);

        return $this->render('Search/parts/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
