<?php

namespace Tests\Builders;

use App\UseCases\CreateProject\InputData;
use DateTime;

/**
 * CANDIDATO: O padrão "builder" para criação de objetos que serão utilizados nos estes automatizados
 * foi simplesmente uma das coisas mais úteis que eu já aprendi na programação. Com este padrão
 * não precisamos ficar criando dezenas de estruturas diferentes no teste automatizamos. Encapsulamos todas essas
 * regras aqui no Builder.
 */
class CreateProjectInputDataBuilder
{
    private InputData $inputData;

    private function __construct()
    {
        $due_date = new DateTime();
        $due_date->modify('+1 day');
        $this->inputData = new InputData([
            'title' => 'title',
            'description' => 'description',
            'due_date' => $due_date->format('Y-m-d h:i:s'),
        ]);
    }

    public static function aProject(): static
    {
        return new static;
    }

    public function withInvalidTitle()
    {
        $this->inputData->title = '';
        return $this;
    }

    public function withInvalidDescription()
    {
        $this->inputData->description = '';
        return $this;
    }

    public function withInvalidDueDate()
    {
        $yesterday = new DateTime();
        $yesterday->modify('-1 day');
        $this->inputData->due_date = $yesterday->format('Y-m-d h:i:s');
        return $this;
    }

    public function build(): InputData
    {
        return $this->inputData;
    }
}
