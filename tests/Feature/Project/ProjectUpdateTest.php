<?php

namespace Tests\Unit\Controllers;

use App\Http\Exceptions\ClientException;
use App\Http\Exceptions\ServerException;
use App\Models\Project;
use App\Models\User;
use App\UseCases\UpdateProject\UpdateProject;
use Tests\TestCase;
use DateTime;

class ProjectUpdateTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function testProjectTitleIsUpdated()
    {
        $project = Project::factory()->create();
        $response = $this->patch("/projects/$project->id", [
            'title' => 'changed',
        ]);
        $response->assertStatus(200);
        $saved = Project::find($project->id);
        $this->assertEquals($saved->title, 'changed');
    }

    public function testProjectDescriptionIsUpdated()
    {
        $project = Project::factory()->create();
        $response = $this->patch("/projects/$project->id", [
            'description' => 'changed',
        ]);
        $response->assertStatus(200);
        $saved = Project::find($project->id);
        $this->assertEquals($saved->description, 'changed');
    }

    public function testProjectDueDateIsUpdated()
    {
        $project = Project::factory()->create();
        $response = $this->patch("/projects/$project->id", [
            'due_date' => '2099-01-01',
        ]);
        $response->assertStatus(200);
        $saved = Project::find($project->id);
        $this->assertStringContainsString('2099-01-01', $saved->due_date); // Using contains to ignore time...
    }

    public function testResponseIsBadRequestWhenClientExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(UpdateProject::class);
        $useCaseMock->method('execute')->willThrowException(new ClientException);
        $this->instance(UpdateProject::class, $useCaseMock);

        $response = $this->patch('/projects/1', [
            'title' => 'title',
        ]);

        $response->assertBadRequest();
    }

    public function testResponseIsInternalServerErrorWhenServerExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(UpdateProject::class);
        $useCaseMock->method('execute')->willThrowException(new ServerException());
        $this->instance(UpdateProject::class, $useCaseMock);

        $response = $this->patch('/projects/1', [
            'title' => 'title',
        ]);

        $response->assertInternalServerError();
    }
}