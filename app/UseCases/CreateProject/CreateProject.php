<?php

namespace App\UseCases\CreateProject;

use App\Domain\Entities\Project;
use App\Repositories\Contracts\ProjectRepository;
use App\Utils\UseCaseExceptionHandler;

class CreateProject
{
    /**
     * CANDIDATO: Eu criei os use cases que são responsáveis por orquestrar o domínio da aplicação.
     * Os controllers não devem possuir regras de negócio, mas devem chamar os use cases que farão essa tarefa.
     * 
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
            $project = Project::build($input->title, $input->description, $input->due_date);
            $id = $this->projectRepository->save($project);
            return new OutputData(['id' => $id]);
        } catch (\Exception $error) {
            UseCaseExceptionHandler::handle($error);
        }
    }
}