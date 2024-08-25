<?php

namespace Tests\Builders;

use App\UseCases\CreateTask\InputData;

class CreateTaskInputDataBuilder
{
    private InputData $inputData;

    private function __construct()
    {
        $this->inputData = new InputData([
            'title' => 'title',
            'description' => 'description',
            'status' => 'pendent',
            'project_id' => 1,
            'responsible_id' => 1,
            'due_date' => '2099-01-01',
        ]);
    }

    public static function aTask(): static
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
        $this->inputData->due_date = '1999-01-01';
        return $this;
    }

    public function withInvalidStatus()
    {
        $this->inputData->status = 'invalid-status';
        return $this;
    }

    public function build(): InputData
    {
        return $this->inputData;
    }
}
