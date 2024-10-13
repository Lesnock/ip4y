<?php

namespace App\UseCases\ExportProjectPdf;

use App\Exceptions\EntityNotFoundException;
use App\Models\Project;
use App\Services\Contracts\PdfService;
use App\Utils\UseCaseExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExportProjectPdf
{
    public function __construct(private PdfService $pdfService)
    { }

    public function execute(int $id): ?string
    {
        try {
            $project = Project::with('tasks.responsible')->findOrFail($id);
            $html = view('project-pdf', compact('project'));
            return $this->pdfService->generate($html);
        } catch (\Exception $error) {
            if ($error instanceof ModelNotFoundException) {
                throw new EntityNotFoundException("Projeto não encontrado");
            }
            UseCaseExceptionHandler::handle($error);
        }
    }
}