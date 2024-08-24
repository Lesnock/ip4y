<?php

namespace App\UseCases\CreateProject;

use App\Domain\Entities\Project;
use App\Repositories\Contracts\ProjectRepository;
use App\Utils\UseCaseExceptionHandler;
use DateTime;

class CreateProject
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

    public function execute(InputData $input): ?OutputData
    {
        try {
            $project = Project::build($input->title, $input->description, new DateTime($input->due_date));
            $id = $this->projectRepository->save($project);
            return new OutputData(['id' => $id]);
        } catch (\Exception $error) {
            UseCaseExceptionHandler::handle($error);
        }
    }
}