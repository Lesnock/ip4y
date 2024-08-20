<?php

namespace Tests\Unit\UseCases;

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
        $projectRepositoryMock->expects($this->once())->method('save')->willReturn(1);
        $usecase = new CreateProject($projectRepositoryMock);
        $output = $usecase->execute($input);
        $this->assertEquals(1, $output->id);
    }
}