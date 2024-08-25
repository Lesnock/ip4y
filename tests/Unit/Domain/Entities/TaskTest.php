<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Project;
use App\Domain\Exceptions\EntityBuildValidationException;
use Tests\TestCase;
use DateTime;

class TaskTest extends TestCase
{
    public function testValidEntityIsCreated()
    {
        $due_date = new DateTime;
        $due_date->modify('+1 day');
        $project = Project::build('title', 'description', $due_date);

        $this->assertEquals($project->toArray(), [
            'id' => null,
            'title' => 'title',
            'description' => 'description',
            'due_date' => $due_date
        ]);
    }

    public function testProjectIsNotCreatedWithEmptyTitle()
    {
        $this->expectException(EntityBuildValidationException::class);
        Project::build('', 'description', new DateTime());
    }

    public function testProjectIsNotCreatedWithEmptyDescription()
    {
        $this->expectException(EntityBuildValidationException::class);
        Project::build('title', '', new DateTime());
    }

    public function testProjectCannotBeCreatedWithPasseddue_date()
    {
        $this->expectException(EntityBuildValidationException::class);
        $yesterday = new DateTime;
        $yesterday->modify('-1 day');
        Project::build('title', '', $yesterday, null);
    }
}