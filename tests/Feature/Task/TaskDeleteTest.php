<?php

namespace Tests\Feature\Project;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class TaskDeleteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function testTaskIsDeleted()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'responsible_id' => $user->id
        ]);
        $response = $this->delete("/tasks/$task->id");
        $response->assertStatus(200);
        $saved = Task::find($task->id);
        $this->assertNull($saved);
    }
}