<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Project;
use App\Domain\Exceptions\InvalidProjectParametersException;
use Tests\TestCase;
use DateTime;

class ProjectTest extends TestCase
{
    public function testValidProjectIsCreated()
    {
        $dueDate = new DateTime;
        $dueDate->modify('+1 day');
        $project = Project::build('title', 'description', $dueDate);

        $this->assertEquals($project->toArray(), [
            'id' => null,
            'title' => 'title',
            'description' => 'description',
            'dueDate' => $dueDate
        ]);
    }

    public function testProjectIsNotCreatedWithEmptyTitle()
    {
        $this->expectException(InvalidProjectParametersException::class);
        Project::build('', 'description', new DateTime());
    }

    public function testProjectIsNotCreatedWithEmptyDescription()
    {
        $this->expectException(InvalidProjectParametersException::class);
        Project::build('title', '', new DateTime());
    }

    public function testProjectCannotBeCreatedWithPassedDueDate()
    {
        $this->expectException(InvalidProjectParametersException::class);
        $yesterday = new DateTime;
        $yesterday->modify('-1 day');
        Project::build('title', '', $yesterday, null);
    }
}