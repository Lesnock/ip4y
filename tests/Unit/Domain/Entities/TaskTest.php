<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Task;
use App\Domain\Exceptions\EntityBuildValidationException;
use App\Domain\Exceptions\ValueObjectValidationException;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function testValidTaskIsCreated()
    {
        $project = Task::build(
            title: 'title', 
            description: 'description', 
            status: 'pendent', 
            due_date: '2099-01-01', 
            project_id: 1, 
            responsible_id: 1, 
        );

        $this->assertEquals($project->toArray(), [
            'id' => null,
            'title' => 'title',
            'description' => 'description',
            'status' => 'pendent',
            'due_date' => '2099-01-01',
            'project_id' => 1, 
            'responsible_id' => 1, 
        ]);
    }

    public function testTaskIsNotCreatedWithEmptyTitle()
    {
        $this->expectException(EntityBuildValidationException::class);
        Task::build(
            title: '', 
            description: 'description', 
            status: 'pendent', 
            due_date: '2099-01-01', 
            project_id: 1, 
            responsible_id: 1
        );
    }

    public function testTaskIsNotCreatedWithEmptyDescription()
    {
        $this->expectException(EntityBuildValidationException::class);
        Task::build(
            title: 'title', 
            description: '', 
            status: 'pendent', 
            due_date: '2099-01-01', 
            project_id: 1, 
            responsible_id: 1
        );
    }

    public function testTaskCannotBeCreatedWithInvalidStatus()
    {
        $this->expectException(ValueObjectValidationException::class);
        Task::build(
            title: 'title', 
            description: 'description', 
            status: 'invalid-status', 
            due_date: '2099-01-01', 
            project_id: 1, 
            responsible_id: 1
        );

    }
}