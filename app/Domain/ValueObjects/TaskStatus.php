<?php

namespace App\Domain\ValueObjects;
use App\Domain\Exceptions\ValueObjectValidationException;

class TaskStatus
{
    public function __construct(private string $status)
    {
        if (!in_array($status, ['pendent', 'in-progress', 'completed'])) {
            throw new ValueObjectValidationException("O status $status não é um status válido");
        }
    }

    public function getStatus()
    {
        return $this->status;
    }
}
