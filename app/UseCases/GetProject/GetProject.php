<?php

namespace App\UseCases\GetProject;

use App\Exceptions\EntityNotFoundException;
use App\Models\Project;
use App\Utils\UseCaseExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * CANDIDATO: Falando sobre CQS (vide README.md), use cases que são apenas consultas não precisam
 * receber um repositório como dependência, pois não farão nenhum alteração no sistema. Nestes casos,
 * não há necessidade de interagir com o domínio da aplicação, então apenas chamamos o Eloquent diretamente.
 */
class GetProject
{
    public function execute(int $id)
    {
        try {
            return Project::with('tasks.responsible')->findOrFail($id);
        } catch (\Exception $error) {
            if ($error instanceof ModelNotFoundException) {
                throw new EntityNotFoundException("Projeto não encontrado");
            }
            UseCaseExceptionHandler::handle($error);
        }
    }
}