<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\UseCases\ExportProjectPdf\ExportProjectPdf;
use App\UseCases\ExportProjectXlsx\ExportProjectXlsx;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;

class ProjectExportController extends Controller
{
    public function pdf(int $id, ExportProjectPdf $exportProjectPdf)
    {
        $project = Project::findOrFail($id);

        try {
            $buffer = Cache::rememberForever("project-pdf-$id", function () use ($exportProjectPdf, $id) {
                return base64_encode($exportProjectPdf->execute($id));
            });

            $buffer = base64_decode($buffer);
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

    public function xlsx(int $id, ExportProjectXlsx $exportProjectXlsx)
    {
        Project::findOrFail($id);

        try {
            $filepath = Cache::rememberForever("project-xlsx-$id", function () use ($exportProjectXlsx, $id) {
                return $exportProjectXlsx->execute($id);
            });

            return response()->download($filepath)->deleteFileAfterSend();
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'error' => $error->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
