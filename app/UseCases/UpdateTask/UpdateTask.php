<?php

namespace App\UseCases\UpdateTask;

use App\Exceptions\EntityNotFoundException;
use App\Models\User;
use App\Notifications\TaskUpdated;
use App\Repositories\Contracts\TaskRepository;
use App\Utils\UseCaseExceptionHandler;
use Illuminate\Support\Facades\DB;

class UpdateTask
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

    public function execute(int $id, array $input): void
    {
        $task = $this->taskRepository->getById($id);

        if (!$task) {
            throw new EntityNotFoundException("A tarefa $id não foi encontrada");
        }

        try {
            foreach ($input as $field => $value) {
                switch ($field) {
                    case 'title': $task->setTitle($value); break;
                    case 'description': $task->setDescription($value); break;
                    case 'status': $task->setStatus($value); break;
                    case 'project_id': $task->setProjectId($value); break;
                    case 'responsible_id': $task->setResponsibleId($value); break;
                    case 'due_date': $task->setDueDate($value); break;
                }
            }
            DB::beginTransaction();
            $this->taskRepository->save($task);
            $user = User::find($task->getResponsibleId());
            $user->notify(new TaskUpdated($task));
            DB::commit();
        } catch (\Exception $error) {
            DB::rollBack();
            UseCaseExceptionHandler::handle($error);
        }
    }
}