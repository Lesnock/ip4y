<?php

namespace App\Http\Controllers;

use App\Http\Exceptions\ClientException;
use App\Http\Exceptions\ServerException;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\User;
use App\UseCases\CreateProject\CreateProject;
use App\UseCases\CreateProject\InputData as CreateProjectInputData;
use App\UseCases\DeleteProject\DeleteProject;
use App\UseCases\GetProject\GetProject;
use App\UseCases\ListProject\ListProject;
use App\UseCases\UpdateProject\UpdateProject;
use App\Utils\ControllerExceptionHandler;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

/**
 * CANDIDATO: Por se tratar de um CRUD praticamente, eu optei por criar um Resouce Controller.
 * Existia a possibilidade de criarmos um controller para cada ação (ListProjectController, StoreProjectController, etc.), mas
 * não enxerguei muita necessidade de fazer isso. Todavia, há casos em que se aplicam.
 */
class ProjectController extends Controller
{
    public function index(ListProject $listProject)
    {
        return Inertia::render('Project/Projects', [
            'projects' => $listProject->execute()
        ]);
    }

    public function create()
    {
        return Inertia::render('ProjectAdd');
    }

    public function store(StoreProjectRequest $request, CreateProject $createProject, GetProject $getProject)
    {
        try {
            $output = $createProject->execute(new CreateProjectInputData($request->validated()));
            $project = $getProject->execute($output->id);

            return response()->json([
                'status' => true,
                'project' => $project
            ], Response::HTTP_CREATED); // 201
        } catch (ClientException | ServerException $error) {
            return ControllerExceptionHandler::handle($error);
        }
    }

    public function edit(GetProject $getProject, string $id)
    {
        return Inertia::render('Project/ProjectEdit', [
            'project' => $getProject->execute($id),
            'users' => User::all()
        ]);
    }

    public function update(UpdateProjectRequest $request, UpdateProject $updateProject, string $id)
    {
        try {
            $updateProject->execute($id, $request->all());
            Cache::forget("project-pdf-$id");
            Cache::forget("project-xlsx-$id");
            return response()->json([
                'status' => true,
            ], Response::HTTP_OK); // 200
        } catch (ClientException | ServerException $error) {
            return ControllerExceptionHandler::handle($error);
        }
    }

    public function destroy(DeleteProject $deleteProject, string $id)
    {
        try {
            $deleteProject->execute($id);
            Cache::forget("project-pdf-$id");
            Cache::forget("project-xlsx-$id");
            return response()->json([
                'status' => true,
            ], Response::HTTP_OK); // 200
        } catch (ClientException | ServerException $error) {
            return ControllerExceptionHandler::handle($error);
        }
    }
}
