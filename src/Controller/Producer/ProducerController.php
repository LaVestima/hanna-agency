<?php

namespace App\Controller\Producer;

use App\Controller\Infrastructure\BaseController;
use App\Repository\ProducerRepository;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/producer/show/{pathSlug}", name="producer_show")
     *
     * @param string $pathSlug
     * @return mixed
     * @throws \Exception
     */
    public function show(string $pathSlug)
    {
        $producer = $this->producerRepository->readOneEntityBy(['pathSlug' => $pathSlug])->getResult();

        if (!$producer) {
            throw new NotFoundHttpException();
        }

        $products = $this->productRepository->readEntitiesBy([
            'idProducers' => $producer->getId()
        ])->onlyUndeleted()->getResultAsArray();

        $productsImages = [];
        foreach ($products as $product) {
            $image = $this->productImageRepository
                ->readOneEntityBy([
                    'idProducts' => $product->getId()
                ])->orderBy('sequencePosition')
                ->getResult();

            if ($image && file_exists($image->getFilePath())) {
                $productsImages[$product->getId()] = $image;
            }
        }

        $this->setView('Producer/show.html.twig');
        $this->setTemplateEntities([
            'producer' => $producer,
            'products' => $products,
            'productsImages' => $productsImages
        ]);

        return $this->baseShow();
    }
}
