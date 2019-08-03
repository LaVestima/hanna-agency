<?php

namespace App\Controller\Search;

use App\Controller\Infrastructure\BaseController;
use App\Form\AdvancedSearchType;
use App\Form\SearchBarType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    public function home(Request $request, PaginatorInterface $paginator)
    {
        $searchQuery = trim($request->query->get('query'));
        $searchCategory = trim($request->query->get('category'));

        $form = $this->createForm(AdvancedSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsQuery = $this->productRepository->getProductsQueryByAdvancedSearch(
                $form->get('query')->getData(),
                $form->get('category')->getData(),
                $form->get('priceMin')->getData(),
                $form->get('priceMax')->getData(),
                $form->get('sorting')->getData()
            );
        } else {
            $productsQuery = $this->productRepository
                ->getProductsQueryByAdvancedSearch($searchQuery, $searchCategory);

            $form->get('query')->setData($searchQuery);
            $form->get('category')->setData($searchCategory);
        }

        $pagination = $paginator->paginate(
            $productsQuery,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('Search/search.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'searchQuery' => $searchQuery,
            'searchCategory' => $searchCategory
        ]);
    }

    public function searchBar(string $searchQuery = '', string $searchCategory = '')
    {
        $form = $this->createForm(SearchBarType::class, null, [
            'action' => $this->generateUrl('search_home')
        ]);

        $form->get('query')->setData($searchQuery);
        $form->get('category')->setData($searchCategory);

        return $this->render('Search/parts/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
