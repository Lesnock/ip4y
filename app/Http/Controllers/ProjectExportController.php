<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\Contracts\PdfService;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;

class ProjectExportController extends Controller
{
    public function preview(int $id)
    {
        $project = Project::with('tasks.responsible')->findOrFail($id);
        return view('project-pdf', compact('project'));
    }

    public function pdf(int $id, PdfService $pdfService)
    {
        try {
            $project = Project::with('tasks.responsible')->findOrFail($id);
            $html = view('project-pdf', compact('project'));
            $buffer = $pdfService->generate($html);
            $filename = "$project->title.pdf";

            return response($buffer, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => HeaderUtils::makeDisposition('inline', $filename),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'error' => $error->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
