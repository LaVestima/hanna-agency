<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations;

/**
 * @Route("/api")
 */
class PingApiController extends AbstractFOSRestController
{
    /**
     * @Annotations\Get(path="/ping", name="api_ping")
     */
    public function getAction()
    {
        return new JsonResponse('pong');
    }
}
