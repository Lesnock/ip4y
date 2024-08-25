<?php

namespace App\UseCases\UpdateProject;

use App\Domain\Entities\Project;
use App\Exceptions\EntityNotFoundException;
use App\Repositories\Contracts\ProjectRepository;
use App\Utils\UseCaseExceptionHandler;
use DateTime;

class UpdateProject
{
    /**
     * Injetamos a dependência do repository que é uma interface,
     * o que irá facilitar MUITO a testabilidade desse código,
     * pois nos testes unitários não precisaremos nos preocupar em salvar o projeto de fato no banco de dados, 
     * pelo contrário, focaremos apenas no comportamento do caso de uso.
     * Quando estiver rodando via request no entanto, o verdadeiro ProjectRepository será injetado pelo Service Container
     * do Laravel.
     */
    public function __construct(private ProjectRepository $projectRepository)
    { }

    public function execute(int $id, array $input): void
    {
        $project = $this->projectRepository->getById($id);

        if (!$project) {
            throw new EntityNotFoundException("O projeto $id não foi encontrado");
        }

        try {
            foreach ($input as $field => $value) {
                switch ($field) {
                    case 'title': $project->setTitle($value); break;
                    case 'description': $project->setDescription($value); break;
                    case 'due_date': $project->setDueDate(new DateTime($value)); break;
                }
            }
            $this->projectRepository->save($project);
        } catch (\Exception $error) {
            UseCaseExceptionHandler::handle($error);
        }
    }
}