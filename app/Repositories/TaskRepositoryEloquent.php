<?php

namespace App\Repositories;

use App\Domain\Entities\Task;
use App\Models\Task as TaskModel;
use App\Repositories\Contracts\TaskRepository;

class TaskRepositoryEloquent implements TaskRepository
{
    public function getById(int $id): ?Task
    {
        $row = TaskModel::find($id);
        if (!$row) {
            return null;
        }
        return Task::build(
            title: $row->title,
            description: $row->description,
            status: $row->status,
            due_date: $row->due_date,
            project_id: $row->project_id,
            responsible_id: $row->responsible_id,
            id: $row->id,
        );
    }

    public function save(Task $task): int
    {
        $data = $task->toArray();

        if (!$task->getId()) {
            $row = TaskModel::create($data);
            return $row->id;
        }

        TaskModel::where('id', $task->getId())->update($data);
        return $task->getId();
    }
}