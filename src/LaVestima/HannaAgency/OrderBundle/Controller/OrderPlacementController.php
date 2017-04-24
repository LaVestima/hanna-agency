<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller;

use LaVestima\HannaAgency\CustomerBundle\Entity\Customers;
use LaVestima\HannaAgency\OrderBundle\Entity\Orders;
use LaVestima\HannaAgency\OrderBundle\Entity\OrdersProducts;
use LaVestima\HannaAgency\OrderBundle\Form\Helper\ProductPlacementHelper;
use LaVestima\HannaAgency\OrderBundle\Form\OrderSummaryType;
use LaVestima\HannaAgency\OrderBundle\Form\PlaceOrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;

class OrderPlacementController extends Controller {
    public function newAction(Request $request) {
        $products = $this->get('product_crud_controller')
            ->readAllEntities();

        $productPlacement = new ProductPlacementHelper();

        $form = $this->createForm(PlaceOrderType::class, $productPlacement, [
            'products' => $products
        ]);

        foreach ($products as $product) {
            $form->get('quantities')->add('quantity_' . $product->getId(), IntegerType::class, [
                'data' => 0,
                'empty_data' => 0,
            ]);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($productPlacement->products as $selectedProduct) {
                echo $selectedProduct->getId();
            }

            if (!empty($productPlacement->products)) {
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
//        $productPlacement = $request->getSession()->get('productPlacement');
        $selectedProducts = $request->getSession()->get('productPlacement')->products;
        $selectedQuantities = $request->getSession()->get('productPlacement')->quantities;

        $form = $this->createForm(OrderSummaryType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = new Orders();
            $order->setDatePlaced(new \DateTime('now'));

            $order->setIdCustomers(
                $this->get('customer_crud_controller')
                    ->readOneEntityBy(['lastName' => 'Sanger'])
            );

//            $order->

                $this->get('order_crud_controller')
                    ->createEntity($order);

            foreach ($selectedProducts as $selectedProduct) {
                $orderProduct = new OrdersProducts();
//                var_dump($orderProduct);

                $orderProduct->setIdProducts($selectedProduct);
//                var_dump($orderProduct);

                $this->get('order_product_crud_controller')
                    ->createEntity($orderProduct);
//                var_dump($orderProduct);
            }
        }

        return $this->render('@Order/OrderPlacement/summary.html.twig', [
//            'productPlacement' => $productPlacement,
            'selectedProducts' => $selectedProducts,
            'selectedQuantities' => $selectedQuantities,
            'form' => $form->createView(),
        ]);
    }
}