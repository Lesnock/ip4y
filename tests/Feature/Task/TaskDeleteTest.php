<?php

namespace Tests\Feature\Project;

use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class TaskDeleteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function testProjectIsDeleted()
    {
        $project = Project::factory()->create();
        $response = $this->delete("/projects/$project->id");
        $response->assertStatus(200);
        $saved = Project::find($project->id);
        $this->assertNull($saved);
    }
}