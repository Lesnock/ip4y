<?php

namespace App\UseCases\ListProject;

use App\Models\Project;
use App\Utils\UseCaseExceptionHandler;

class ListProject
{
    public function execute()
    {
        try {
            return Project::with('tasks.user')->orderBy('id', 'desc')->get();
        } catch (\Exception $error) {
            UseCaseExceptionHandler::handle($error);
        }
    }
}