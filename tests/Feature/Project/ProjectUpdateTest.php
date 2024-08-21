<?php

namespace Tests\Unit\Controllers;

use App\Http\Exceptions\ClientException;
use App\Http\Exceptions\ServerException;
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

    public function testResponseIsOk()
    {
        $useCaseMock = $this->createMock(UpdateProject::class);
        $useCaseMock->method('execute');
        $this->instance(UpdateProject::class, $useCaseMock);

        $yesterday = new DateTime();
        $yesterday->modify('+1 day');
        $dueDate = $yesterday->format('Y-m-d h:i:s');

        $response = $this->put('/projects/1', [
            'title' => 'title',
            'description' => 'description',
            'dueDate' => $dueDate,
        ]);

        $response->assertStatus(200);
    }

    public function testResponseIsBadRequestWhenClientExceptionIsThrown()
    {
        $useCaseMock = $this->createMock(UpdateProject::class);
        $useCaseMock->method('execute')->willThrowException(new ClientException);
        $this->instance(UpdateProject::class, $useCaseMock);

        $response = $this->put('/projects/1', [
            'title' => 'title',
            'description' => 'description',
            'dueDate' => '2001-01-01 00:00:00',
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
            'dueDate' => '2001-01-01 00:00:00',
        ]);

        $response->assertInternalServerError();
    }
}