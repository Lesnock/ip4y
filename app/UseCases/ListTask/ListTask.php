<?php

namespace App\UseCases\ListTask;

use App\Models\Task;
use App\Utils\UseCaseExceptionHandler;

class ListTask
{
    public function execute()
    {
        try {
            return Task::orderBy('id', 'asc')->get();
        } catch (\Exception $error) {
            UseCaseExceptionHandler::handle($error);
        }
    }
}