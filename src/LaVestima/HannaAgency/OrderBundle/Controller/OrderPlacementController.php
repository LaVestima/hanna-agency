<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 19/04/17
 * Time: 11:21
 */

namespace LaVestima\HannaAgency\OrderBundle\Controller;


use LaVestima\HannaAgency\OrderBundle\Form\PlaceOrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrderPlacementController extends Controller {
    public function newAction() {
        $products = $this->get('product_crud_controller')
            ->readAllEntities();

        $form = $this->createForm(PlaceOrderType::class, $products, [
            'products' => $products
        ]);

        return $this->render('@Order/OrderPlacement/new.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }

    public function summaryAction() {

        return $this->render('@Order/OrderPlacement/summary.html.twig');
    }
}