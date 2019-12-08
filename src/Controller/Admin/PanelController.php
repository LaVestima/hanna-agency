<?php

namespace App\Controller\Admin;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Category;
use App\Entity\Store;
use App\Form\Admin\CategoriesType;
use App\Repository\CategoryRepository;
use App\Repository\CouponRepository;
use App\Repository\StoreRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/panel")
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class PanelController extends BaseController
{
    private $categoryRepository;
    private $couponRepository;
    private $storeRepository;
    private $userRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        CouponRepository $couponRepository,
        StoreRepository $storeRepository,
        UserRepository $userRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->couponRepository = $couponRepository;
        $this->storeRepository = $storeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="admin_panel_index")
     */
    public function index()
    {
        return $this->render('Admin/panel/index.html.twig');
    }

    /**
     * @Route("/categories", name="admin_panel_categories")
     */
    public function categories(Request $request)
    {
        $form = $this->createForm(CategoriesType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $treeJson = $form->get('treeJson')->getData();

            $allCategories = $this->categoryRepository->findAll();

            $newCategories = $this->saveTreeCategories(json_decode($treeJson, true));

            foreach ($allCategories as $category) {
                if (!in_array($category, $newCategories)) {
                    $this->deleteTreeCategory($category);
                }
            }
        }

        $mainCategories = $this->categoryRepository->findBy([
            'parent' => null
        ]);

        return $this->render('Admin/panel/categories.html.twig', [
            'mainCategories' => $mainCategories,
            'form' => $form->createView()
        ]);
    }

    private function saveTreeCategories($categories, $parent = null)
    {
        $newCategories = [];

        foreach ($categories as $categoryData) {
            $category = $this->categoryRepository->findOneBy([
                'identifier' => $categoryData['identifier']
            ]);

            if (!$category) {
                $category = new Category();
                $category->setName($categoryData['name']);
                $category->setIdentifier($categoryData['identifier']);
                $category->setParent($parent);

                $this->categoryRepository->createEntity($category);
            } else {
                $this->categoryRepository->updateEntity($category, [
                    'name' => $categoryData['name'],
                    'identifier' => $categoryData['identifier'],
                    'parent' => $parent
                ]);
            }

            $newCategories[] = $category;

            if (!empty($categoryData['children'])) {
                $newCategories = array_merge(
                    $newCategories,
                    $this->saveTreeCategories($categoryData['children'], $category)
                );
            }
        }

        return $newCategories;
    }

    private function deleteTreeCategory(Category $category)
    {
        if (!empty($category->getChildren())) {
            foreach ($category->getChildren() as $child) {
                $this->deleteTreeCategory($child);
            }
        }

        $this->categoryRepository->deleteEntity($category);
    }

    /**
     * @Route("/coupons", name="admin_panel_coupons")
     */
    public function coupons()
    {
        $coupons = $this->couponRepository->findAll();

        return $this->render('Admin/panel/coupons.html.twig', [
            'coupons' => $coupons
        ]);
    }

    /**
     * @Route("/stores", name="admin_panel_stores")
     */
    public function stores()
    {
        return $this->render('Admin/panel/stores.html.twig', [
            'stores' => $this->storeRepository->findAll()
        ]);
    }

    /**
     * @Route("/users", name="admin_panel_users")
     */
    public function users()
    {
        return $this->render('Admin/panel/users.html.twig', [
            'users' => $this->userRepository->findAll()
        ]);
    }

    /**
     * @Route("/store/activate/{identifier}", name="admin_panel_store_activate")
     */
    public function activateStore(Store $store)
    {
        $store->setActive(true);

        $this->entityManager->persist($store);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_panel_stores');
    }

    /**
     * @Route("/store/deactivate/{identifier}", name="admin_panel_store_deactivate")
     */
    public function deactivateStore(Store $store)
    {
        $store->setActive(false);

        $this->entityManager->persist($store);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_panel_stores');
    }
}
