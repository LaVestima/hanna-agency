<?php

namespace App\Controller\Search;

use App\Controller\Infrastructure\BaseController;
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
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function home(Request $request)
    {
        $searchQuery = trim($request->query->get('query'));

        $products = $this->productRepository
            ->readEntitiesBy([
                'name' => [$searchQuery, 'LIKE'],
                'active' => 1
            ])
            ->getResultAsArray();
//        var_dump($products);

        return $this->render('Search/search.html.twig', [
            'products' => $products,
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
