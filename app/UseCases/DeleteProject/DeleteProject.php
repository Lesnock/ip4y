<?php

namespace App\UseCases\DeleteProject;

use App\Exceptions\EntityNotFoundException;
use App\Models\Project;
use App\Utils\UseCaseExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteProject
{
    public function execute(int $id)
    {
        try {
            return Project::findOrFail($id)->delete();
        } catch (\Exception $error) {
            if ($error instanceof ModelNotFoundException) {
                throw new EntityNotFoundException("Projeto não encontrado");
            }
            UseCaseExceptionHandler::handle($error);
        }
    }
}