<?php

namespace App\Controller\Category;

use App\Controller\Infrastructure\BaseController;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends BaseController
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/categories", name="category_list")
     */
    public function list()
    {
        // TODO: only active
        $categories = $this->categoryRepository
            ->readAllEntities()
            ->onlyActiveProducts()
//            ->readEntitiesBy([
//                'products.active' => true
//            ])
            ->getResultAsArray();

        return $this->render('Category/list.html.twig', [
            'categories' => $categories
        ]);
    }
}
