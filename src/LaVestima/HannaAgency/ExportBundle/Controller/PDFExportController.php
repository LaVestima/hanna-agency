<?php

namespace LaVestima\HannaAgency\ExportBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use LaVestima\HannaAgency\CustomerBundle\Entity\Customers;
use LaVestima\HannaAgency\ExportBundle\Controller\Helper\PDF\PDF;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PDFExportController extends BaseController
{
    private $pdf;

    public function __construct()
    {
        // Page dimensions (mm): 210 x 297
        // Usable space: 190 x 277

        $this->pdf = new PDF('P', 'mm', 'A4');

        $this->pdf->SetTitle('Export', true);
        $this->pdf->SetMargins(10, 10, 10);
        $this->pdf->SetAuthor('Hanna Agency', true);

        $this->pdf->AddPage();
    }

    public function invoiceAction(string $pathSlug)
    {
//        $customer = json_decode($jsonInvoice, true)['customer'];

//        $this->addCustomerData($customer);

        $this->pdf->DataTable([], json_decode($jsonInvoice, true));

        return new Response($this->pdf->Output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * @param string $pathSlug
     * @return Response
     */
    public function orderAction(string $pathSlug)
    {
        $order = $this->get('order_crud_controller')
            ->readOneEntityBy(['pathSlug' => $pathSlug]);

        $orderProducts = $this->get('order_product_crud_controller')
            ->readEntitiesBy(['idOrders' => $order])
            ->getEntities();

        if (!$this->isUserAllowedToViewEntity($order)) {
            throw new AccessDeniedHttpException();
        } else {
            $this->pdf->DataTable(
                ['Product Name', 'Quantity', 'Status'],
                json_decode(json_encode($orderProducts), true),
                [120, 30, 40]
            );

            $this->addCustomerData($order->getIdCustomers());
        }

        return new Response($this->pdf->Output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * @param string $pathSlugsJson
     * @return Response
     * @throws EntityNotFoundException
     */
    public function orderListAction(string $pathSlugsJson)
    {
        $pathSlugs = json_decode($pathSlugsJson, true);

        $orders = [];

        foreach ($pathSlugs as $pathSlug) {
            $orders[] = $this->get('order_crud_controller')
                ->readOneEntityBy(['pathSlug' => $pathSlug]);
        }

        $this->pdf->DataTable(
            ['Date Placed', 'Status', 'Customer'],
            json_decode(json_encode($orders), true)
        );

        return new Response($this->pdf->Output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * @param Customers $customer
     */
    private function addCustomerData(Customers $customer)
    {
        // TODO: add utf support
        $this->pdf->SetY(10);
        $this->pdf->SetX(105);
        $this->pdf->SetFont('Arial','',12);

        $this->pdf->Cell(0, 5, $customer->getFullName(), 0, 2);
        $this->pdf->Cell(0, 5, $customer->getStreet(), 0, 2);
        $this->pdf->Cell(0, 5, $customer->getPostalCode() . ' ' . $customer->getIdCities()->getName(), 0, 2);
        $this->pdf->Cell(0, 5, $customer->getIdCountries()->getName(), 0, 2);
    }
}