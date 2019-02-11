<?php

namespace App\Controller\Api;

use App\Controller\Infrastructure\BaseController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends BaseController
{
    /**
     * @Route("/", name="api_index")
     */
    public function index()
    {
        return $this->render('Api/index.html.twig');
    }
}
