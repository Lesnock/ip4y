<?php

namespace Tests\Builders;

use App\UseCases\CreateProject\InputData;
use DateTime;

class CreateProjectInputDataBuilder
{
    private InputData $inputData;

    private function __construct()
    {
        $dueDate = new DateTime();
        $dueDate->modify('+1 day');
        $this->inputData = new InputData([
            'title' => 'title',
            'description' => 'description',
            'dueDate' => $dueDate->format('Y-m-d h:i:s'),
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
        $this->inputData->dueDate = $yesterday->format('Y-m-d h:i:s');
        return $this;
    }

    public function build(): InputData
    {
        return $this->inputData;
    }
}
