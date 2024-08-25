<?php

namespace Tests\Feature\Project;

use App\Http\Exceptions\ClientException;
use App\Http\Exceptions\ServerException;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\UseCases\CreateProject\CreateProject;
use App\UseCases\CreateTask\CreateTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStoreTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function testTaskIsCreated()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $response = $this->post('/tasks', [
            'title' => 'title',
            'description' => 'description',
            'status' => 'pendent',
            'project_id' => $project->id,
            'responsible_id' => $user->id,
            'due_date' => '2099-01-01',
        ]);
        $response->assertStatus(201);
        $data = $response->decodeResponseJson();
        $task = Task::find($data['task']['id']);
        $this->assertNotNull($task);
    }

    public function testResponseIsBadRequestWhenClientExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(CreateTask::class);
        $useCaseMock->method('execute')->willThrowException(new ClientException);
        $this->instance(CreateTask::class, $useCaseMock);

        $project = Project::factory()->create();
        $user = User::factory()->create();
        $response = $this->post('/tasks', [
            'title' => 'title',
            'description' => 'description',
            'status' => 'pendent',
            'project_id' => $project->id,
            'responsible_id' => $user->id,
            'due_date' => '2099-01-01',
        ]);

        $response->assertBadRequest();
    }

    public function testResponseIsInternalServerErrorWhenServerExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(CreateTask::class);
        $useCaseMock->method('execute')->willThrowException(new ServerException());
        $this->instance(CreateTask::class, $useCaseMock);

        $project = Project::factory()->create();
        $user = User::factory()->create();
        $response = $this->post('/tasks', [
            'title' => 'title',
            'description' => 'description',
            'status' => 'pendent',
            'project_id' => $project->id,
            'responsible_id' => $user->id,
            'due_date' => '2099-01-01',
        ]);

        $response->assertInternalServerError();
    }
}