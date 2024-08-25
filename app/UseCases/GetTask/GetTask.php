<?php

namespace App\UseCases\GetTask;

use App\Exceptions\EntityNotFoundException;
use App\Models\Task;
use App\Utils\UseCaseExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetTask
{
    public function execute(int $id)
    {
        try {
            return Task::findOrFail($id);
        } catch (\Exception $error) {
            if ($error instanceof ModelNotFoundException) {
                throw new EntityNotFoundException("Tarefa não encontrada");
            }
            UseCaseExceptionHandler::handle($error);
        }
    }
}