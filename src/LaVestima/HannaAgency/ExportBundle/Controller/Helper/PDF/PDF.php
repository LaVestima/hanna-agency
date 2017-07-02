<?php

namespace LaVestima\HannaAgency\ExportBundle\Controller\Helper\PDF;


class PDF extends \FPDF
{
    public function Header()
    {
        $this->SetFont('Arial','B',15);
        $this->Cell(30, 30, 'LOGO', 1, 0,'C');
        $this->SetY(40);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);

        $this->SetX(10);
        $this->Cell(0, 10, 'Generated: ' . (new \DateTime('now'))->format('d.m.Y H:i'));

        $this->SetX(-30);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    public function DataTable(array $header, array $data, array $columnWidths = null)
    {
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');

        $cellHeight = 8;

        $this->Ln();

        if (!$columnWidths) {
            $columnWidths = [];
            for ($i = 0; $i < count($header); $i++) {
                $columnWidths[] = floor(190 / count($header));
            }
        }

        for($i = 0; $i < count($header); $i++) {
            $this->Cell($columnWidths[$i], $cellHeight, $header[$i], 1, 0, 'C', true);
        }

        $this->Ln();

        $fill = false;

        foreach($data as $rowKey => $row)  {
            $j = 0;

            if (is_array($row)) {
                foreach ($row as $columnKey => $column) {
                    if (is_string($columnKey) and !is_array($column)) {
                        $this->Cell($columnWidths[$j], $cellHeight, $row[$columnKey], 'LR', 0, 'C', $fill);

                        $j++;
                    }
                }
            } else {
                $this->Cell($columnWidths[0], $cellHeight, $row[$rowKey], 'LR', 0, 'C', $fill);
            }

            $this->Ln();

            $fill = !$fill;
        }

        $this->Cell(array_sum($columnWidths), 0, '', 'T');
    }
}