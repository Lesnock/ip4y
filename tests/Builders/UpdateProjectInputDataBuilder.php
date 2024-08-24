<?php

namespace Tests\Builders;

use App\UseCases\UpdateProject\InputData;
use DateTime;

class UpdateProjectInputDataBuilder
{
    private InputData $inputData;

    private function __construct()
    {
        $due_date = new DateTime();
        $due_date->modify('+10 day');
        $this->inputData = new InputData([
            'title' => 'update-title',
            'description' => 'update-description',
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

    public function build(): InputData
    {
        return $this->inputData;
    }
}
