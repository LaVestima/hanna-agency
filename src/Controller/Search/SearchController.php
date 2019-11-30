<?php

namespace App\Controller\Search;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\AdvancedSearchType;
use App\Form\SearchBarType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends BaseController
{
    private $categoryRepository;
    private $productRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    ) {
        $this->categoryRepository = $categoryRepository;
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

            $searchQuery = $form->get('query')->getData();
            $searchCategory = $form->get('category')->getData();
        } else {
            $productsQuery = $this->productRepository
                ->getProductsQueryByAdvancedSearch($searchQuery, $searchCategory);

            $form->get('query')->setData($searchQuery);
            $form->get('category')->setData($searchCategory);
        }

        /** @var Category $category */
        $category = $this->categoryRepository->findOneBy([
            'identifier' => $searchCategory
        ]);

        $pagination = $paginator->paginate(
            $productsQuery,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('Search/search.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'searchQuery' => $searchQuery,
            'searchCategory' => $searchCategory,
            'category' => $category
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

    /**
     * @Route("/search/suggestions", name="search_bar_suggestions",
     *     methods={"GET"},
     *     options={"expose"=true},
     *     condition="request.isXmlHttpRequest()"
     * )
     */
    public function searchBarSuggestions(RepositoryManagerInterface $manager, Request $request)
    {
        $query = $request->query->all();
        $search = isset($query['q']) && !empty($query['q']) ? $query['q'] : null;

        /** @var \App\Repository\Elastica\ProductRepository $repository */
        $repository = $manager->getRepository(Product::class);

        $products = $repository->search($search);

        $data = [];

        /** @var Product $product */
        foreach ($products as $product) {
            $data[] = [
                'name' => $product->getName(),
                'url' => $this->generateUrl('product_show', [
                    'pathSlug' => $product->getPathSlug()
                ])
            ];
        }

        return $this->json($data);
    }
}
