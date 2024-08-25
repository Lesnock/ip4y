<?php

namespace App\Repositories\Contracts;

use App\Domain\Entities\Task;

interface TaskRepository
{
    public function getById(int $id): ?Task;
    public function save(Task $task): int;
}