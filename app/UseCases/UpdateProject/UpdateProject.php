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

    public function execute(int $id, InputData $input): void
    {
        $project = $this->projectRepository->getById($id);

        if (!$project) {
            throw new EntityNotFoundException("O projeto $id não foi encontrado");
        }

        try {
            $project
                ->setTitle($input->title)
                ->setDescription($input->description)
                ->setDueDate(new DateTime($input->dueDate));
            $this->projectRepository->save($project);
        } catch (\Exception $error) {
            UseCaseExceptionHandler::handle($error);
        }
    }
}