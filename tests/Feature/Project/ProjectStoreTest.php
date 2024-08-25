<?php

namespace Tests\Feature\Project;

use App\Http\Exceptions\ClientException;
use App\Http\Exceptions\ServerException;
use App\Models\Project;
use App\Models\User;
use App\UseCases\CreateProject\CreateProject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectStoreTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function testProjectIsCreated()
    {
        $response = $this->post('/projects', [
            'title' => 'title',
            'description' => 'description',
            'due_date' => '2099-01-01',
        ]);

        $response->assertStatus(201);
        $data = $response->decodeResponseJson();
        $project = Project::find($data['project']['id']);
        $this->assertNotNull($project);
    }

    public function testResponseIsBadRequestWhenClientExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(CreateProject::class);
        $useCaseMock->method('execute')->willThrowException(new ClientException);
        $this->instance(CreateProject::class, $useCaseMock);

        $response = $this->post('/projects', [
            'title' => 'title',
            'description' => 'description',
            'due_date' => '2001-01-01 00:00:00',
        ]);

        $response->assertBadRequest();
    }

    public function testResponseIsInternalServerErrorWhenServerExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(CreateProject::class);
        $useCaseMock->method('execute')->willThrowException(new ServerException());
        $this->instance(CreateProject::class, $useCaseMock);

        $response = $this->post('/projects', [
            'title' => 'title',
            'description' => 'description',
            'due_date' => '2001-01-01 00:00:00',
        ]);

        $response->assertInternalServerError();
    }
}