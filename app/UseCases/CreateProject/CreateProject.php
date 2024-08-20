<?php

namespace App\UseCases\CreateProject;

use App\Domain\Entities\Project;
use App\Domain\Repositories\ProjectRepository;
use App\UseCases\CreateProject\OutputData;
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
     * @param ProjectRepository $projectRepository
     */
    public function __construct(private ProjectRepository $projectRepository)
    { }

    public function execute(InputData $input)
    {
        $project = Project::build($input->title, $input->description, new DateTime($input->dueDate));
        $id = $this->projectRepository->save($project);
        return new OutputData(['id' => $id]);
    }
}