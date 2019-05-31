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

        $form = $this->createForm(AdvancedSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            var_dump($form->getData());

            // TODO: custom query?
            $products = $this->productRepository->getProductsBySearchQuery(
                $form->get('query')->getData(),
                $form->get('priceMin')->getData(),
                $form->get('priceMax')->getData(),
                $form->get('sorting')->getData()
            );
        } else {
            $products = $this->productRepository
                ->readEntitiesBy([
                    'name' => [$searchQuery, 'LIKE'],
                    'active' => 1
                ])
                ->getResultAsArray();

            $form->get('query')->setData($searchQuery);
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
