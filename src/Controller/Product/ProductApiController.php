<?php

namespace App\Controller\Product;

use App\Repository\ProductRepository;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\RouteResource("Product", pluralize=false)
 */
class ProductApiController extends AbstractFOSRestController implements ClassResourceInterface
{
    private $productRepository;

    public function __construct(
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function cgetAction()
    {
        $context = new Context();
        $context->setGroups(['api']);

        $view = $this->view($this->productRepository->findAll());

        return $this->handleView($view->setContext($context));
    }

    public function getAction(string $id)
    {
        return $this->view($this->productRepository->readOneEntityBy(['id' => $id])->getResult());
    }

    public function postAction(Request $request)
    {

    }
}
