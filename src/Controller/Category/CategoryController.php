<?php

namespace App\Controller\Category;

use App\Controller\Infrastructure\BaseController;
use App\Repository\CategoryRepository;

class CategoryController extends BaseController
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function waterfallList()
    {
        $categories = $this->categoryRepository
            ->readAllEntities()
            ->getResultAsArray();

        return $this->render('Category/waterfallList.html.twig', [
            'categories' => $categories
        ]);
    }
}
