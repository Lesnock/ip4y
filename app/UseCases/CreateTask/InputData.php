<?php

namespace App\UseCases\CreateTask;

use App\DTO;

class InputData extends DTO
{
    public string $title;
    public string $description;
    public string $status;
    public string $project_id;
    public string $responsible_id;
    public string $due_date;
}