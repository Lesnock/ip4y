<?php

namespace App\Domain\ValueObjects;
use App\Domain\Exceptions\ValueObjectValidationException;

/**
 * Value Object
 * CANDIDATO: Os value objects são responsáveis por criar um domínio rico,
 * com comportamente e validação. Eu poderia ter criado mais value objects, como por exemplo,
 * ProjectName (que validaria se o nome do projeto é valido), ProjectDueDate (que faria todos os cálculos e validações
 * sobre a data de conclusão), mas infelizmente não tive tempo :(
 * Observe como a regra sobre o status de uma tarefa fica armazenada em um único lugar.
 */
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
