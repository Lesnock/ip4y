<?php

namespace App\UseCases\ExportProjectXlsx;

use App\Exceptions\EntityNotFoundException;
use App\Models\Project;
use App\Utils\UseCaseExceptionHandler;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportProjectXlsx
{
    public function execute(int $id): ?string
    {
        try {
            $project = Project::with('tasks.responsible')->findOrFail($id);
            $spreadsheet = new Spreadsheet();
            $activeWorksheet = $spreadsheet->getActiveSheet();
            $activeWorksheet->setCellValue('A1', "# $project->id - $project->title");
            $activeWorksheet->setCellValue('A2', $project->description);
            $activeWorksheet->setCellValue('A4', 'Tarefas');
            $activeWorksheet->setCellValue('A5', 'Título');
            $activeWorksheet->setCellValue('B5', 'Descrição');
            $activeWorksheet->setCellValue('C5', 'Status');
            $activeWorksheet->setCellValue('D5', 'Responsável');
            $activeWorksheet->setCellValue('E5', 'Data de Vencimento');

            $row = 6;
            foreach ($project->tasks as $task) {
                $activeWorksheet->setCellValue("A$row", $task->title);
                $activeWorksheet->setCellValue("B$row", $task->description);
                $activeWorksheet->setCellValue("C$row", $task->status);
                $activeWorksheet->setCellValue("D$row", $task->responsible->name);
                $activeWorksheet->setCellValue("E$row", (new DateTime($task->due_date))->format('d/m/Y'));
                $row++;
            }
            
            $writer = new Xlsx($spreadsheet);
            $filename = "$project->title.xlsx";
            $writer->save($path = storage_path($filename));
            return $path;
        } catch (\Exception $error) {
            if ($error instanceof ModelNotFoundException) {
                throw new EntityNotFoundException("Projeto não encontrado");
            }
            UseCaseExceptionHandler::handle($error);
        }
    }
}