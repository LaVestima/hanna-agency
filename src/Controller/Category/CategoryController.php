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
     * @Route("/category/list", name="category_list")
     */
    public function list()
    {
        $categories = $this->categoryRepository
            ->readAllEntities()
            ->getResult();

//        var_dump($categories);
//        var_dump(sizeof($categories[1]->getProducts()));
//        var_dump($categories[1]->getProducts());

        return $this->render('Category/list.html.twig', [
            'categories' => $categories
        ]);
    }
}
