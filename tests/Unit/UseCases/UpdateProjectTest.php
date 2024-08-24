<?php

namespace Tests\Unit\UseCases;

use App\Domain\Entities\Project;
use App\Exceptions\EntityNotFoundException;
use App\Http\Exceptions\ClientException;
use App\Repositories\Contracts\ProjectRepository;
use App\UseCases\CreateProject\CreateProject;
use App\UseCases\UpdateProject\UpdateProject;
use Tests\Builders\UpdateProjectInputDataBuilder;
use Tests\TestCase;
use DateTime;

class UpdateProjectTest extends TestCase
{
    protected function createProject(): Project
    {
        $due_date = new DateTime;
        $due_date->modify('+1 day');
        return Project::build('title', 'description', $due_date, 1);
    }

    public function testUpdateProject()
    {
        $project = $this->createProject();
        $input = UpdateProjectInputDataBuilder::aProject()->build();
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $projectRepositoryMock->expects($this->once())->method('getById')->with(1)->willReturn($project);
        $projectRepositoryMock->expects($this->once())->method('save');

        $usecase = new UpdateProject($projectRepositoryMock);
        $usecase->execute($project->getId(), $input);

        $this->assertEquals($project->getTitle(), $input->title);
        $this->assertEquals($project->getDescription(), $input->description);
        $this->assertEquals($project->getdue_date()->format('Y-m-d h:i:s'), $input->due_date);
    }

    public function testProjectIsNotFound()
    {
        $this->expectException(EntityNotFoundException::class);
        $input = UpdateProjectInputDataBuilder::aProject()->withInvalidTitle()->build();
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $projectRepositoryMock->expects($this->once())->method('getById')->with(1)->willReturn(null);

        $usecase = new UpdateProject($projectRepositoryMock);
        $usecase->execute(id: 1, input: $input);
    }

    public function testProjectIsNotCreatedWithInvalidTitle()
    {
        $this->expectException(ClientException::class);
        $project = $this->createProject();
        $input = UpdateProjectInputDataBuilder::aProject()->withInvalidTitle()->build();
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $projectRepositoryMock->expects($this->once())->method('getById')->with(1)->willReturn($project);

        $usecase = new UpdateProject($projectRepositoryMock);
        $usecase->execute($project->getId(), $input);
    }

    public function testProjectIsNotCreatedWithInvalidDescription()
    {
        $this->expectException(ClientException::class);
        $project = $this->createProject();
        $input = UpdateProjectInputDataBuilder::aProject()->withInvalidDescription()->build();
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $projectRepositoryMock->expects($this->once())->method('getById')->with(1)->willReturn($project);

        $usecase = new UpdateProject($projectRepositoryMock);
        $usecase->execute($project->getId(), $input);
    }
}