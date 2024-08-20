<?php

namespace Tests\Unit\UseCases;

use App\Http\Exceptions\ClientException;
use App\Repositories\Contracts\ProjectRepository;
use App\UseCases\CreateProject\CreateProject;
use Tests\Builders\CreateProjectInputDataBuilder;
use Tests\TestCase;

class CreateProjectTest extends TestCase
{
    public function testCreateProject()
    {
        $input = CreateProjectInputDataBuilder::aProject()->build();
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $projectRepositoryMock->expects($this->once())->method('save')->willReturn(10);
        $usecase = new CreateProject($projectRepositoryMock);
        $output = $usecase->execute($input);
        $this->assertEquals(10, $output->id);
    }

    public function testProjectIsNotCreatedWithInvalidTitle()
    {
        $this->expectException(ClientException::class);
        $input = CreateProjectInputDataBuilder::aProject()->withInvalidTitle()->build();
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $usecase = new CreateProject($projectRepositoryMock);
        $usecase->execute($input);
    }

    public function testProjectIsNotCreatedWithInvalidDescription()
    {
        $this->expectException(ClientException::class);
        $input = CreateProjectInputDataBuilder::aProject()->withInvalidDescription()->build();
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $usecase = new CreateProject($projectRepositoryMock);
        $usecase->execute($input);
    }

    public function testProjectIsNotCreatedWithInvalidDueDate()
    {
        $this->expectException(ClientException::class);
        $input = CreateProjectInputDataBuilder::aProject()->withInvalidDueDate()->build();
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $usecase = new CreateProject($projectRepositoryMock);
        $usecase->execute($input);
    }
}