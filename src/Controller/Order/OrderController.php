<?php

namespace App\Controller\Order;

use App\Controller\Infrastructure\BaseController;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
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
//            ->fetchJoin('orderProductVariants')
            ->getResultAsArray();


        return $this->render('Order/list.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/order/{code}", name="order_show")
     */
    public function show(string $code)
    {
        $order = $this->orderRepository->readOneEntityBy([
            'code' => $code,
        ])->getResult();

        if (!$order) { throw new HttpException(404); }
        if ($order->getUser() != $this->getUser()) { throw new HttpException(401); }

        return $this->render('Order/show.html.twig', [
            'order' => $order
        ]);
    }
}
