<?php

namespace Tests\Unit\UseCases;

use App\Domain\Entities\Project;
use App\Exceptions\EntityNotFoundException;
use App\Http\Exceptions\ClientException;
use App\Repositories\Contracts\ProjectRepository;
use App\UseCases\UpdateProject\UpdateProject;
use Tests\TestCase;
use DateTime;

class UpdateProjectTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function createProject(): Project
    {
        $due_date = new DateTime;
        $due_date->modify('+1 day');
        return Project::build('title', 'description', $due_date->format('Y-m-d'), 1);
    }

    public function testUpdateProjectFields()
    {
        $project = $this->createProject();
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $projectRepositoryMock->expects($this->once())->method('getById')->with(1)->willReturn($project);
        $projectRepositoryMock->expects($this->once())->method('save');

        $usecase = new UpdateProject($projectRepositoryMock);
        $usecase->execute($project->getId(), [
            'title' => 'New title',
            'description' => 'New description',
            'due_date' => '2099-01-01'
        ]);

        $this->assertEquals($project->getTitle(), 'New title');
        $this->assertEquals($project->getDescription(), 'New description');
        $this->assertEquals($project->getDueDate(), '2099-01-01');
    }

    public function testProjectIsNotFound()
    {
        $this->expectException(EntityNotFoundException::class);
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $projectRepositoryMock->expects($this->once())->method('getById')->with(1)->willReturn(null);

        $usecase = new UpdateProject($projectRepositoryMock);
        $usecase->execute(1, []);
    }

    public function testProjectIsNotUpdatedWithInvalidTitle()
    {
        $this->expectException(ClientException::class);
        $project = $this->createProject();
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $projectRepositoryMock->expects($this->once())->method('getById')->with(1)->willReturn($project);

        $usecase = new UpdateProject($projectRepositoryMock);
        $usecase->execute($project->getId(), [
            'title' => '',
            'description' => 'New description',
            'due_date' => '2099-01-01'
        ]);
    }

    public function testProjectIsNotUpdatedWithInvalidDescription()
    {
        $this->expectException(ClientException::class);
        $project = $this->createProject();
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $projectRepositoryMock->expects($this->once())->method('getById')->with(1)->willReturn($project);

        $usecase = new UpdateProject($projectRepositoryMock);
        $usecase->execute($project->getId(), [
            'title' => 'New title',
            'description' => '',
            'due_date' => '2099-01-01'
        ]);
    }
}