<?php

namespace App\UseCases\CreateProject;

use App\DTO;

/**
 * CANDIDATO: Criar uma classe de entrada de dados do Use Case é muito últil
 * para delimitar o que o use case está recebendo. 
 */
class InputData extends DTO
{
    public string $title;
    public string $description;
    public string $due_date;
}