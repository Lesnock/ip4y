<?php

namespace App\Services\Contracts;

interface PdfService
{
    public function generate(string $html): string;
}