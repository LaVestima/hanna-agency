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
    // TODO: DI

    /**
     * Order Placement New Action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $productPlacement = new ProductPlacementHelper();

        $isAdmin = $this->isAdmin();

        $form = $this->createForm(PlaceOrderType::class, $productPlacement, [
            'isAdmin' => $isAdmin,
        ]);

        $productsSizes = $this->get('product_size_crud_controller')
            ->readAllEntities()
            ->getResult();

        foreach ($productsSizes as $key => $productSize) {
            $form->get('quantities')
                ->add($key, IntegerType::class, [
                    'data' => 0,
                    'empty_data' => 0,
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($productPlacement->productsSizes)) {
                if (!$isAdmin) {
                    $productPlacement->customers = $this->getCustomer();
                }

                $productPlacement->quantities = array_values(
                    array_filter($productPlacement->quantities, function ($var) {
                        return ($var > 0);
                }));
                $productPlacement->sizes = [];

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

    /**
     * Order Placement Summary Action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function summaryAction(Request $request) {
        $productPlacement = $request->getSession()->get('productPlacement');
        $selectedProductsSizes = $productPlacement->productsSizes;
        $selectedQuantities = $productPlacement->quantities;

        if (count($selectedProductsSizes) != count($selectedQuantities)) {
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

            foreach ($selectedProductsSizes as $key => $selectedProductSize) {
                $orderProduct = new OrdersProducts();

                $orderProduct->setIdOrders($order);
                $orderProduct->setIdProducts($selectedProductSize->getIdProducts());
                $orderProduct->setIdStatuses(
                    $this->get('order_status_crud_controller')
                        ->readOneEntityBy(['name' => 'Queued'])
                        ->getResult()
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