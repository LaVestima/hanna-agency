<?php

namespace App\Controller\Shipment;

use App\Controller\Infrastructure\BaseController;
use App\Repository\ShipmentOptionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShipmentOptionController extends BaseController
{
    private $shipmentOptionRepository;

    public function __construct(
        ShipmentOptionRepository $shipmentOptionRepository
    ) {
        $this->shipmentOptionRepository = $shipmentOptionRepository;
    }

    /**
     * @Route("/shipment_option/list")
     *
     * @return Response
     */
    public function list()
    {
        $shipmentOptions = $this->shipmentOptionRepository->readAllEntities()->getResult();
        var_dump($shipmentOptions);

        return new Response();
    }
}
