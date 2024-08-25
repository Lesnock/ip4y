<?php

namespace App\UseCases\CreateTask;

use App\Domain\Entities\Task;
use App\Repositories\Contracts\TaskRepository;
use App\Utils\UseCaseExceptionHandler;

class CreateTask
{
    /**
     * Injetamos a dependência do repository que é uma interface,
     * o que irá facilitar MUITO a testabilidade desse código,
     * pois nos testes unitários não precisaremos nos preocupar em salvar a tarefa de fato no banco de dados, 
     * pelo contrário, focaremos apenas no comportamento do caso de uso.
     * Quando estiver rodando via request no entanto, o verdadeiro TaskRepository será injetado pelo Service Container
     * do Laravel.
     */
    public function __construct(private TaskRepository $taskRepository)
    { }

    public function execute(InputData $input): ?OutputData
    {
        try {
            $task = Task::build(
                title: $input->title, 
                description: $input->description, 
                status: $input->status, 
                due_date: $input->due_date, 
                project_id: $input->project_id, 
                responsible_id: $input->responsible_id
            );
            $id = $this->taskRepository->save($task);
            return new OutputData(['id' => $id]);
        } catch (\Exception $error) {
            UseCaseExceptionHandler::handle($error);
        }
    }
}