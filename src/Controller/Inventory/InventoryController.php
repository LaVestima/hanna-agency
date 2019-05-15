<?php

namespace App\Controller\Inventory;

use App\Controller\Infrastructure\BaseController;
use App\Repository\ProductRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class InventoryController extends BaseController
{
    private $productRepository;

    public function __construct(
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/inventory", name="inventory_home")
     */
    public function home()
    {
        if (!$producer = $this->getProducer()) {
            throw new AccessDeniedHttpException();
        }

        $products =  $this->productRepository->readEntitiesBy([
            'producer' => $producer->getId()
        ])->getResultAsArray() ?? [];

        $productOrders = [];
        $chartProductOrders = [];

        $period = new DatePeriod(
            new DateTime('now - 6 days'),
            new DateInterval('P1D'),
            new DateTime('now + 1 day')
        );

        foreach ($products as $product) {
            $productOrders[$product->getId()] = [];
            foreach ($product->getProductVariants() as $productVariant) {
                foreach ($productVariant->getOrderProductVariants() as $orderProductVariant) {
                    $orderDate = $orderProductVariant->getOrder()->getDateCreated()->format('Y-m-d');

                    if (!array_key_exists($orderDate, $productOrders[$product->getId()])) {
                        $productOrders[$product->getId()][$orderDate] = 0;
                    }

                    $productOrders[$product->getId()][$orderDate] += $orderProductVariant->getQuantity();
                }
            }

            foreach ($period as $item) {
                if (!array_key_exists($item->format('Y-m-d'), $productOrders[$product->getId()])) {
                    $productOrders[$product->getId()][$item->format('Y-m-d')] = 0;
                }
            }

            ksort($productOrders[$product->getId()]);

            $dateCounter = 0;

            foreach ($productOrders[$product->getId()] as $productOrderDate => $productOrder) {
                $chartProductOrders[$product->getId()][$dateCounter]['label'] = $productOrderDate;
                $chartProductOrders[$product->getId()][$dateCounter]['y'] = $productOrder;

                $dateCounter++;
            }
        }

        $this->setActionBar([
            [
                'label' => 'New Product',
                'path' => 'product_new',
//                'role' => 'ROLE_ADMIN',
                'icon' => 'fa-plus'
            ]
        ]);

        return $this->render('Inventory/inventory.html.twig', [
            'products' => $products,
            'productOrders' => $chartProductOrders
        ]);
    }
}
