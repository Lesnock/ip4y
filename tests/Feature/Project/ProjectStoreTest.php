<?php

namespace Tests\Unit\Controllers;

use App\Http\Exceptions\ClientException;
use App\Http\Exceptions\ServerException;
use App\Models\User;
use App\UseCases\CreateProject\CreateProject;
use App\UseCases\CreateProject\OutputData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use DateTime;

class ProjectStoreTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function testResponseIsOk()
    {
        $useCaseMock = $this->createMock(CreateProject::class);
        $useCaseMock->method('execute')->willReturn(new OutputData(['id' => 1]));
        $this->instance(CreateProject::class, $useCaseMock);

        $yesterday = new DateTime();
        $yesterday->modify('+1 day');
        $dueDate = $yesterday->format('Y-m-d h:i:s');

        $response = $this->post('/projects', [
            'title' => 'title',
            'description' => 'description',
            'dueDate' => $dueDate,
        ]);

        $response->assertStatus(201);
    }

    public function testResponseIsBadRequestWhenClientExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(CreateProject::class);
        $useCaseMock->method('execute')->willThrowException(new ClientException);
        $this->instance(CreateProject::class, $useCaseMock);

        $response = $this->post('/projects', [
            'title' => 'title',
            'description' => 'description',
            'dueDate' => '2001-01-01 00:00:00',
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
            'dueDate' => '2001-01-01 00:00:00',
        ]);

        $response->assertInternalServerError();
    }
}