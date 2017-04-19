<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 19/04/17
 * Time: 11:21
 */

namespace LaVestima\HannaAgency\OrderBundle\Controller;


use LaVestima\HannaAgency\OrderBundle\Form\Helper\ProductPlacementHelper;
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
            $selectedProducts = $form->getData();

            foreach ($productPlacement->products as $selectedProduct) {
                echo $selectedProduct->getId();
            }
        }

        return $this->render('@Order/OrderPlacement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function summaryAction() {

        return $this->render('@Order/OrderPlacement/summary.html.twig');
    }
}