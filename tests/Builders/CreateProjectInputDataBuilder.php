<?php

namespace Tests\Builders;

use App\UseCases\CreateProject\InputData;
use DateTime;

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

    public function withInvaliddue_date()
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
