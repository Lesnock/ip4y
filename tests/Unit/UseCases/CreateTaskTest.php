<?php

namespace Tests\Unit\UseCases;

use App\Http\Exceptions\ClientException;
use App\Repositories\Contracts\ProjectRepository;
use App\Repositories\Contracts\TaskRepository;
use App\UseCases\CreateProject\CreateProject;
use App\UseCases\CreateTask\CreateTask;
use Tests\Builders\CreateTaskInputDataBuilder;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    public function testCreateTask()
    {
        $input = CreateTaskInputDataBuilder::aTask()->build();
        $taskRepositoryMock = $this->createMock(TaskRepository::class);
        $taskRepositoryMock->expects($this->once())->method('save')->willReturn(10);
        $usecase = new CreateTask($taskRepositoryMock);
        $output = $usecase->execute($input);
        $this->assertEquals(10, $output->id);
    }

    public function testTaskIsNotCreatedWithInvalidTitle()
    {
        $this->expectException(ClientException::class);
        $input = CreateTaskInputDataBuilder::aTask()->withInvalidTitle()->build();
        $taskRepositoryMock = $this->createMock(TaskRepository::class);
        $usecase = new CreateTask($taskRepositoryMock);
        $usecase->execute($input);
    }

    public function testTaskIsNotCreatedWithInvalidDescription()
    {
        $this->expectException(ClientException::class);
        $input = CreateTaskInputDataBuilder::aTask()->withInvalidDescription()->build();
        $taskRepositoryMock = $this->createMock(TaskRepository::class);
        $usecase = new CreateTask($taskRepositoryMock);
        $usecase->execute($input);
    }

    public function testTaskIsNotCreatedWithInvalidDueDate()
    {
        $this->expectException(ClientException::class);
        $input = CreateTaskInputDataBuilder::aTask()->withInvalidDueDate()->build();
        $taskRepositoryMock = $this->createMock(TaskRepository::class);
        $usecase = new CreateTask($taskRepositoryMock);
        $usecase->execute($input);
    }

    public function testTaskIsNotCreatedWithInvalidStatus()
    {
        $this->expectException(ClientException::class);
        $input = CreateTaskInputDataBuilder::aTask()->withInvalidStatus()->build();
        $taskRepositoryMock = $this->createMock(TaskRepository::class);
        $usecase = new CreateTask($taskRepositoryMock);
        $usecase->execute($input);
    }
}