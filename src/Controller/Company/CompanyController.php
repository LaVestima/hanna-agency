<?php

namespace App\Controller\Company;

use App\Controller\Infrastructure\BaseController;
use App\Repository\CompanyRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends BaseController
{
    private $companyRepository;

    public function __construct(
        CompanyRepository $companyRepository
    ) {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @Route("/company/show/{pathSlug}", name="company_show")
     *
     * @param string $pathSlug
     * @return mixed
     * @throws \Exception
     */
    public function show(string $pathSlug)
    {
        $company = $this->companyRepository->readOneEntityBy(['pathSlug' => $pathSlug])->getResult();

        if (!$company) {
            throw new NotFoundHttpException();
        }

        $this->setView('Company/show.html.twig');
        $this->setTemplateEntities([
            'company' => $company
        ]);

        return $this->baseShow();
    }
}