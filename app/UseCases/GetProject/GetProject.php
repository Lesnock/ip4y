<?php

namespace App\UseCases\GetProject;

use App\Exceptions\EntityNotFoundException;
use App\Models\Project;
use App\Utils\UseCaseExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetProject
{
    public function execute(int $id)
    {
        try {
            return Project::with('tasks.user')->findOrFail($id);
        } catch (\Exception $error) {
            if ($error instanceof ModelNotFoundException) {
                throw new EntityNotFoundException("Projeto não encontrado");
            }
            UseCaseExceptionHandler::handle($error);
        }
    }
}