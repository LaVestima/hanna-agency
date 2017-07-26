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

class OrderPlacementController extends BaseController
{
    public function newAction(Request $request)
    {
        $productPlacement = new ProductPlacementHelper();

        $isAdmin = $this->isAdmin();

        $form = $this->createForm(PlaceOrderType::class, $productPlacement, [
            'isAdmin' => $isAdmin,
        ]);

        $products = $this->get('product_crud_controller')
            ->readAllEntities()
            ->sortBy(['name' => 'ASC'])
            ->getEntities();

        foreach ($products as $key => $product) {
            $form->get('quantities')
                ->add('quantity_' . ($key + 1) , IntegerType::class, [
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

                $productPlacement->quantities = array_values(
                    array_filter($productPlacement->quantities, function ($var) {
                        return ($var > 0);
                }));

                $request->getSession()->set('productPlacement', $productPlacement);

                return $this->redirectToRoute('order_placement_summary');
            }
            else {
                $this->addFlash('warning', 'Order cannot be empty!');
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
            die;
        }

        $form = $this->createForm(OrderSummaryType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = new Orders();
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

            $this->addFlash('success', 'Order placed successfully!');

            return $this->redirectToRoute('order_list');
        }

        return $this->render('@Order/OrderPlacement/summary.html.twig', [
            'productPlacement' => $productPlacement,
            'form' => $form->createView(),
        ]);
    }
}