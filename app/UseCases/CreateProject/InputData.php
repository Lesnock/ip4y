<?php

namespace App\UseCases\CreateProject;

use App\DTO;

class InputData extends DTO
{
    public readonly string $title;
    public readonly string $description;
    public readonly string $dueDate;
}