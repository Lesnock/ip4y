<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Project;
use App\Domain\Exceptions\InvalidProjectParametersException;
use Tests\TestCase;
use DateTime;

class ProjectTest extends TestCase
{
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
}