<?php

namespace LaVestima\HannaAgency\OrderBundle\Controller;

use LaVestima\HannaAgency\OrderBundle\Entity\OrdersProducts;
use LaVestima\HannaAgency\OrderBundle\Form\Helper\ProductPlacementHelper;
use LaVestima\HannaAgency\OrderBundle\Form\OrderSummaryType;
use LaVestima\HannaAgency\OrderBundle\Form\PlaceOrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OrderPlacementController extends Controller {
    public function newAction(Request $request) {
        $products = $this->get('product_crud_controller')
            ->readAllEntities();

        $productPlacement = new ProductPlacementHelper();

        $form = $this->createForm(PlaceOrderType::class, $productPlacement, [
            'products' => $products
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($productPlacement->products as $selectedProduct) {
                echo $selectedProduct->getId();
            }

            $request->getSession()->set('productPlacement', $productPlacement);
            return $this->redirectToRoute('order_placement_summary');
        }

        return $this->render('@Order/OrderPlacement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function summaryAction(Request $request) {
        $selectedProducts = $request->getSession()->get('productPlacement')->products;

        $form = $this->createForm(OrderSummaryType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($selectedProducts as $selectedProduct) {
                $orderProduct = new OrdersProducts();
                var_dump($orderProduct);

                $orderProduct->setIdProducts($selectedProduct);
                var_dump($orderProduct);

                $this->get('order_product_crud_controller')
                    ->createEntity($orderProduct);
                var_dump($orderProduct);
            }
        }

        return $this->render('@Order/OrderPlacement/summary.html.twig', [
            'selectedProducts' => $selectedProducts,
            'form' => $form->createView(),
        ]);
    }
}