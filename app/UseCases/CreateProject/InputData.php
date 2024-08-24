<?php

namespace App\UseCases\CreateProject;

use App\DTO;

class InputData extends DTO
{
    public string $title;
    public string $description;
    public string $due_date;
}