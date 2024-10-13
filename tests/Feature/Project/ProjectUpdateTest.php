<?php

namespace Tests\Feature\Project;

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

    public function testProjectIsUpdated()
    {
        $project = Project::factory()->create();
        $date = new DateTime('2099-01-01');
        $response = $this->put("/projects/$project->id", [
            'title' => 'title',
            'description' => 'description',
            'due_date' => $date->format('Y-m-d'),
        ]);
        $response->assertStatus(200);
        $saved = Project::find($project->id);
        $this->assertEquals($saved->title, 'title');
        $this->assertEquals($saved->description, 'description');
        $this->assertEquals((new DateTime($saved->due_date))->format('Y-m-d'), $date->format('Y-m-d'));
    }

    public function testResponseIsBadRequestWhenClientExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(UpdateProject::class);
        $useCaseMock->method('execute')->willThrowException(new ClientException);
        $this->instance(UpdateProject::class, $useCaseMock);

        $response = $this->put('/projects/1', [
            'title' => 'title',
            'description' => 'description',
            'due_date' => '2001-01-01',
        ]);

        $response->assertBadRequest();
    }

    public function testResponseIsInternalServerErrorWhenServerExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(UpdateProject::class);
        $useCaseMock->method('execute')->willThrowException(new ServerException());
        $this->instance(UpdateProject::class, $useCaseMock);

        $response = $this->put('/projects/1', [
            'title' => 'title',
            'description' => 'description',
            'due_date' => '2001-01-01',
        ]);

        $response->assertInternalServerError();
    }
}