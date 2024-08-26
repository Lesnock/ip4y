<?php

namespace App\UseCases\CreateProject;

use App\DTO;

/**
 * CANDIDATO: Classes de Output do Use Case são úteis para delimitar o que está sendo enviado de volta para o controller. 
 */
class OutputData extends DTO
{
    public int $id;
}