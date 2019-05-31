<?php

namespace App\Controller\Producer;

use App\Controller\Infrastructure\BaseController;
use App\Repository\ProducerRepository;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/producer")
 */
class ProducerController extends BaseController
{
    private $producerRepository;
    private $productRepository;
    private $productImageRepository;

    public function __construct(
        ProducerRepository $producerRepository,
        ProductRepository $productRepository,
        ProductImageRepository $productImageRepository
    ) {
        $this->producerRepository = $producerRepository;
        $this->productRepository = $productRepository;
        $this->productImageRepository = $productImageRepository;
    }

    /**
     * @Route("/show/{pathSlug}", name="producer_show")
     */
    public function show(string $pathSlug)
    {
        $producer = $this->producerRepository
            ->readOneEntityBy([
                'pathSlug' => $pathSlug
            ])
            ->getResult();

        if (!$producer) { throw new NotFoundHttpException(); }

        $this->setView('Producer/show.html.twig');
        $this->setTemplateEntities([
            'producer' => $producer,
            'editable' => ($producer == $this->getProducer())
        ]);

        return $this->baseShow();
    }

    /**
     * @Route("/dashboard", name="producer_dashboard")
     */
    public function dashboard()
    {
        // TODO: finish

        return $this->render('Producer/dashboard.html.twig');
    }
}
