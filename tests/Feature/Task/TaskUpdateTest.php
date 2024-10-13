<?php

namespace Tests\Feature\Task;

use App\Http\Exceptions\ClientException;
use App\Http\Exceptions\ServerException;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskUpdated;
use App\UseCases\UpdateTask\UpdateTask;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use DateTime;

class TaskUpdateTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function testTaskIsUpdated()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'responsible_id' => $user->id
        ]);
        $date = new DateTime('2099-01-01');
        $response = $this->put("/tasks/$task->id", [
            'title' => 'title',
            'description' => 'description',
            'status' => 'pendent',
            'project_id' => $project->id,
            'responsible_id' => $user->id,
            'due_date' => $date->format('Y-m-d'),
        ]);
        $response->assertStatus(200);
        $saved = Task::find($task->id);
        $this->assertEquals($saved->title, 'title');
        $this->assertEquals($saved->description, 'description');
        $this->assertEquals($saved->status, 'pendent');
        $this->assertEquals($saved->project_id, $project->id);
        $this->assertEquals($saved->responsible_id, $user->id);
        $this->assertEquals((new DateTime($saved->due_date))->format('Y-m-d'), $date->format('Y-m-d'));
    }

    public function testNotificationIsSent()
    {
        Notification::fake();
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'responsible_id' => $user->id
        ]);
        $response = $this->put("/tasks/$task->id", [
            'title' => 'title',
            'description' => 'description',
            'status' => 'pendent',
            'project_id' => $project->id,
            'responsible_id' => $user->id,
            'due_date' => '2099-01-01',
        ]);
        $response->assertStatus(200);
        Notification::assertSentTo([$user], TaskUpdated::class);
    }

    public function testResponseIsBadRequestWhenClientExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(UpdateTask::class);
        $useCaseMock->method('execute')->willThrowException(new ClientException);
        $this->instance(UpdateTask::class, $useCaseMock);

        $response = $this->put('/tasks/1', [
            'title' => 'title',
            'description' => 'description',
            'status' => 'pendent',
            'project_id' => 1,
            'responsible_id' => 1,
            'due_date' => '2099-01-01',
        ]);

        $response->assertBadRequest();
    }

    public function testResponseIsInternalServerErrorWhenServerExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(UpdateTask::class);
        $useCaseMock->method('execute')->willThrowException(new ServerException);
        $this->instance(UpdateTask::class, $useCaseMock);

        $response = $this->put('/tasks/1', [
            'title' => 'title',
            'description' => 'description',
            'status' => 'pendent',
            'project_id' => 1,
            'responsible_id' => 1,
            'due_date' => '2099-01-01',
        ]);

        $response->assertInternalServerError();
    }
}