<?php

namespace App\Controller\Admin;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Store;
use App\Repository\CategoryRepository;
use App\Repository\CouponRepository;
use App\Repository\StoreRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    public function categories()
    {
        $categories = $this->categoryRepository->findAll();

        $categoryTree = $this->buildTree($categories);

        return $this->render('Admin/panel/categories.html.twig', [
            'categoryTree' => $categoryTree
        ]);
    }

    function buildTree(array $elements, $parentId = null) {
        $branch = array();

        foreach ($elements as $category) {
            $c = [
                'name' => $category->getName() . '<a><i class="fas fa-trash-alt"></i></a>',
                'id' => $category->getIdentifier(),
            ];

            if ($category->getParent() == $parentId) {
                $children = $this->buildTree($elements, $category);
                if ($children) {
                    $c['children'] = $children;
                }
                $branch[] = $c;
            }
        }

        return $branch;
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
