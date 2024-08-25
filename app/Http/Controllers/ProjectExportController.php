<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\UseCases\ExportProjectPdf\ExportProjectPdf;
use App\UseCases\ExportProjectXlsx\ExportProjectXlsx;
use DateTime;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProjectExportController extends Controller
{
    public function preview(int $id)
    {
        $project = Project::with('tasks.responsible')->findOrFail($id);
        return view('project-pdf', compact('project'));
    }

    public function pdf(int $id, ExportProjectPdf $exportProjectPdf)
    {
        try {
            [$filename, $buffer] = $exportProjectPdf->execute($id);

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
        try {
            $filepath = $exportProjectXlsx->execute($id);
            return response()->download($filepath)->deleteFileAfterSend();
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'error' => $error->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
