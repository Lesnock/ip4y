<?php

namespace Tests\Feature\Task;

use App\Http\Exceptions\ClientException;
use App\Http\Exceptions\ServerException;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\UseCases\UpdateTask\UpdateTask;
use Tests\TestCase;

class TaskUpdateTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function testTaskTitleIsUpdated()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'responsible_id' => $user->id
        ]);
        $response = $this->patch("/tasks/$task->id", [
            'title' => 'changed',
        ]);
        $response->assertStatus(200);
        $saved = Task::find($task->id);
        $this->assertEquals($saved->title, 'changed');
    }

    public function testProjectDescriptionIsUpdated()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'responsible_id' => $user->id
        ]);
        $response = $this->patch("/tasks/$task->id", [
            'description' => 'changed',
        ]);
        $response->assertStatus(200);
        $saved = Task::find($task->id);
        $this->assertEquals($saved->description, 'changed');
    }

    public function testProjectDueDateIsUpdated()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'responsible_id' => $user->id
        ]);
        $response = $this->patch("/tasks/$task->id", [
            'due_date' => '2099-01-01',
        ]);
        $response->assertStatus(200);
        $saved = Task::find($task->id);
        $this->assertStringContainsString('2099-01-01', $saved->due_date); // Using contains to ignore time...
    }

    public function testResponseIsBadRequestWhenClientExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(UpdateTask::class);
        $useCaseMock->method('execute')->willThrowException(new ClientException);
        $this->instance(UpdateTask::class, $useCaseMock);

        $response = $this->patch('/tasks/1', [
            'title' => 'title',
        ]);

        $response->assertBadRequest();
    }

    public function testResponseIsInternalServerErrorWhenServerExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(UpdateTask::class);
        $useCaseMock->method('execute')->willThrowException(new ServerException);
        $this->instance(UpdateTask::class, $useCaseMock);

        $response = $this->patch('/tasks/1', [
            'title' => 'title',
        ]);

        $response->assertInternalServerError();
    }
}