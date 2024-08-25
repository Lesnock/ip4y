<?php

namespace App\Services;
use App\Services\Contracts\PdfService;
use Barryvdh\DomPDF\Facade\Pdf;

class DomPdfService implements PdfService
{
    public function generate(string $html): string
    {
        return Pdf::loadHTML($html)->output();
    }
}