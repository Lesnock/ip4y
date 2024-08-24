<?php

namespace App\UseCases\UpdateProject;

use App\DTO;

class InputData extends DTO
{
    public string $title;
    public string $description;
    public string $due_date;
}