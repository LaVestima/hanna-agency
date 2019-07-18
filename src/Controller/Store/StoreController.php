<?php

namespace App\Controller\Store;

use App\Controller\Infrastructure\BaseController;
use App\Entity\OrderStatus;
use App\Entity\Store;
use App\Repository\OrderProductVariantRepository;
use App\Repository\OrderStatusRepository;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use App\Repository\StoreRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/store")
 */
class StoreController extends BaseController
{
    private $orderProductVariantRepository;
    private $orderStatusRepository;
    private $storeRepository;
    private $productRepository;
    private $productImageRepository;

    public function __construct(
        OrderProductVariantRepository $orderProductVariantRepository,
        OrderStatusRepository $orderStatusRepository,
        StoreRepository $storeRepository,
        ProductRepository $productRepository,
        ProductImageRepository $productImageRepository
    ) {
        $this->orderProductVariantRepository = $orderProductVariantRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->storeRepository = $storeRepository;
        $this->productRepository = $productRepository;
        $this->productImageRepository = $productImageRepository;
    }

    /**
     * @Route("/show/{pathSlug}", name="store_show")
     */
    public function show(Store $store)
    {
        $this->setView('Store/show.html.twig');
        $this->setTemplateEntities([
            'producer' => $store,
            'editable' => $store === $this->getStore()
        ]);

        return $this->baseShow();
    }

    /**
     * @Route("/dashboard", name="store_dashboard")
     */
    public function dashboard()
    {

        // TODO: check if user is a store

        $store = $this->getStore();

        $orderProductVariants = $this->orderProductVariantRepository
            ->getByStore($store);

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

            $quantityOrderProductVariants[$orderDateCreated->format('Y-m-d')] += $orderProductVariant->getQuantity();
        }

        $dateCounter = 0;

        foreach ($quantityOrderProductVariants as $quantityDate => $quantityOrderProductVariant) {
            $chartOrderProductVariants[$dateCounter]['label'] = $quantityDate;
            $chartOrderProductVariants[$dateCounter]['y'] = $quantityOrderProductVariant;

            $dateCounter++;
        }

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
}
