<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\OrderBundle\Entity\Orders;
use LaVestima\HannaAgency\OrderBundle\Entity\OrdersProducts;
use LaVestima\HannaAgency\OrderBundle\Form\Helper\ProductPlacementHelper;
use LaVestima\HannaAgency\OrderBundle\Form\OrderSummaryType;
use LaVestima\HannaAgency\OrderBundle\Form\PlaceOrderType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;

class OrderPlacementController extends BaseController {
    public function newAction(Request $request) {
        $products = $this->get('product_crud_controller')
            ->readAllEntities()->getEntities();

        $customers = $this->get('customer_crud_controller')
            ->readAllEntities()->getEntities();

        $productPlacement = new ProductPlacementHelper();

        $isAdmin = $this->isAdmin();

        $form = $this->createForm(PlaceOrderType::class, $productPlacement, [
            'customers' => $isAdmin ? $customers : null,
            'products' => $products
        ]);

        foreach ($products as $product) {
            $form->get('quantities')
                ->add('quantity_' . $product->getId(), IntegerType::class, [
                    'data' => 0,
                    'empty_data' => 0,
                ]);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($productPlacement->products)) {
                if (!$isAdmin) {
                    $productPlacement->customers = $this->getCustomer();
                }

                $productPlacement->quantities = array_values(array_filter($productPlacement->quantities, function ($var) {
                    return ($var > 0);
                }));

                $request->getSession()->set('productPlacement', $productPlacement);

                return $this->redirectToRoute('order_placement_summary');
            }
            else {
                // TODO: Add "Order cannot be empty" flash
            }
        }

        return $this->render('@Order/OrderPlacement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function summaryAction(Request $request) {
        $productPlacement = $request->getSession()->get('productPlacement');
        $selectedProducts = $productPlacement->products;
        $selectedQuantities = $productPlacement->quantities;

        if (count($selectedProducts) != count($selectedQuantities)) {
            // TODO: products and quantities must be the same length
            var_dump('No!!!!');
        }

        $form = $this->createForm(OrderSummaryType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $this->createNewOrder();
            $order->setIdCustomers($productPlacement->customers);

            $order = $this->get('order_crud_controller')
                ->createEntity($order);

            foreach ($selectedProducts as $key => $selectedProduct) {
                $orderProduct = new OrdersProducts();

                $orderProduct->setIdOrders($order);
                $orderProduct->setIdProducts($selectedProduct);
                $orderProduct->setIdStatuses(
                    $this->get('order_status_crud_controller')
                        ->readOneEntityBy(['name' => 'Queued'])
                );
                $orderProduct->setQuantity($selectedQuantities[$key]);

                $this->get('order_product_crud_controller')
                    ->createEntity($orderProduct);
            }

            // TODO: success flash
            return $this->redirectToRoute('order_list');
        }

        return $this->render('@Order/OrderPlacement/summary.html.twig', [
            'productPlacement' => $productPlacement,
            'form' => $form->createView(),
        ]);
    }

    private function createNewOrder() {
        $order = new Orders();

        $order->setDatePlaced(new \DateTime('now'));

        return $order;
    }
}