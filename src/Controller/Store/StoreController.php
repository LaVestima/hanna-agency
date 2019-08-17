<?php

namespace App\Controller\Store;

use App\Controller\Infrastructure\BaseController;
use App\Entity\OrderStatus;
use App\Entity\Store;
use App\Entity\StoreSubuser;
use App\Entity\User;
use App\Form\StoreApplyType;
use App\Form\StoreLoginType;
use App\Repository\OrderProductVariantRepository;
use App\Repository\OrderStatusRepository;
use App\Repository\ProductRepository;
use App\Repository\StoreRepository;
use App\Repository\StoreSubuserRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @Route("/store")
 */
class StoreController extends BaseController
{
    private $orderProductVariantRepository;
    private $orderStatusRepository;
    private $storeRepository;
    private $storeSubuserRepository;
    private $productRepository;

    public function __construct(
        OrderProductVariantRepository $orderProductVariantRepository,
        OrderStatusRepository $orderStatusRepository,
        StoreRepository $storeRepository,
        StoreSubuserRepository $storeSubuserRepository,
        ProductRepository $productRepository
    ) {
        $this->orderProductVariantRepository = $orderProductVariantRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->storeRepository = $storeRepository;
        $this->storeSubuserRepository = $storeSubuserRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/show/{identifier}", name="store_show")
     */
    public function show(Store $store)
    {
        // TODO: check access

        return $this->render('Store/show.html.twig', [
            'store' => $store,
            'editable' => $store === $this->getStore()
        ]);
    }

    /**
     * @Route("/login/{identifier}", name="store_login")
     */
    public function login(Store $store, Request $request)
    {
        $user = $this->getUser();

        $storeSubuser = $this->storeSubuserRepository->findOneBy([
            'user' => $user,
            'store' => $store
        ]);

        if (!$storeSubuser) {
            var_dump('error!!!!!!!!!!!!');
            die();
            // TODO: show error page
        }


        // TODO: check access (only user with this store assigned to? otherwise, error message and no login form)

        $form = $this->createForm(StoreLoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (password_verify($form->get('password')->getData(), $storeSubuser->getPasswordHash())) {
                $user->addRoles(array_merge(['ROLE_STORE_SUBUSER'], $storeSubuser->getRoles()));

                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());

                $this->container->get('security.token_storage')->setToken($token);

                $this->addFlash('success', 'Successfully logged in');

                // TODO: redirect to dashboard???
            } else {
                $this->addFlash('error', 'Wrong password');
            }
        }

        return $this->render('Store/login.html.twig', [
            'store' => $store,
            'user' => $this->getUser(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="store_logout")
     */
    public function logout()
    {
        $user = $this->getUser();

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());

        $this->container->get('security.token_storage')->setToken($token);

        $this->addFlash('success', 'Successfully logged out');

        return $this->redirectToRoute('homepage_homepage');
    }

    /**
     * @Route("/apply", name="store_apply")
     */
    public function apply(Request $request)
    {
        // TODO: access control: logged in
        $this->denyAccessUnlessGranted('store_apply', $this->getUser());

        $store = new Store();

        $form = $this->createForm(StoreApplyType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $store = $form->getData();
            $store->setActive(false);
            $store->setOwner($this->getUser());

            $this->getDoctrine()->getRepository(Store::class)
                ->createEntity($store);


        }

        return $this->render('Store/apply.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/dashboard", name="store_dashboard")
     */
    public function dashboard()
    {
        $this->denyAccessUnlessGranted('view_dashboard');

        $store = $this->getStore();

        $orderProductVariants = $this->orderProductVariantRepository
            ->getByStore($store);

        $chartOrderProductVariants = $this->generateOrderProductVariantsChartData($orderProductVariants);

        $ordersPending = $this->storeRepository->findOrdersByStatus(
            $this->orderStatusRepository->findOneBy(['name' => OrderStatus::PAID]),
            $store
        );
//        var_dump($ordersPending);

        return $this->render('Producer/dashboard.html.twig', [
            'chartOrderProductVariants' => $chartOrderProductVariants,
            'ordersPending' => $ordersPending
        ]);
    }

    private function generateOrderProductVariantsChartData($orderProductVariants)
    {
        $quantityOrderProductVariants = [];
        $chartOrderProductVariants = [];

        $period = new DatePeriod(
            new DateTime('now - 30 days'),
            new DateInterval('P1D'),
            new DateTime('now + 1 day')
        );

        foreach ($period as $item) {
            $quantityOrderProductVariants[$item->format('Y-m-d')] = 0;
        }

        foreach ($orderProductVariants as $orderProductVariant) {
            $orderDateCreated = $orderProductVariant->getOrder()->getDateCreated();

            if ($orderDateCreated >= $period->getStartDate() && $orderDateCreated <= $period->getEndDate()) {
                $quantityOrderProductVariants[$orderDateCreated->format('Y-m-d')] += $orderProductVariant->getQuantity();
            }
        }

        $dateCounter = 0;

        foreach ($quantityOrderProductVariants as $quantityDate => $quantityOrderProductVariant) {
            $chartOrderProductVariants[$dateCounter]['label'] = $quantityDate;
            $chartOrderProductVariants[$dateCounter]['y'] = $quantityOrderProductVariant;

            $dateCounter++;
        }

        return $chartOrderProductVariants;
    }
}
