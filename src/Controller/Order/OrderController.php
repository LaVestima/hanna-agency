<?php

namespace App\Controller\Order;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends BaseController
{
    private $orderRepository;

    public function __construct(
        OrderRepository $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @Route("/orders", name="order_list")
     */
    public function list()
    {
        $orders = $this->orderRepository->readEntitiesBy([
            'user' => $this->getUser()
        ])
        ->getResultAsArray();


        return $this->render('Order/list.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/order/{code}", name="order_show")
     */
//    public function show(string $code)
    public function show(Order $order)
    {
        // TODO: fix access control
        // TODO: either user who placed or store subuser
        if ($order->getUser() != $this->getUser()) { throw new HttpException(401); }

        return $this->render('Order/show.html.twig', [
            'order' => $order
        ]);
    }
}
