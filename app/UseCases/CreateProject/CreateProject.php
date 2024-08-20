<?php

namespace App\UseCases\CreateProject;

use App\Domain\Entities\Project;
use App\Repositories\Contracts\ProjectRepository;
use App\UseCases\CreateProject\OutputData;
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
    public function __construct(private  $projectRepository)
    { }

    public function execute(InputData $input)
    {
        try {
            $project = Project::build($input->title, $input->description, new DateTime($input->dueDate));
            $id = $this->projectRepository->save($project);
            return new OutputData(['id' => $id]);
        } catch (\Exception $error) {
            UseCaseExceptionHandler::handle($error);
        }
    }
}