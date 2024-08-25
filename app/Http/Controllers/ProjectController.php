<?php

namespace App\Http\Controllers;

use App\Http\Exceptions\ClientException;
use App\Http\Exceptions\ServerException;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\UseCases\CreateProject\CreateProject;
use App\UseCases\CreateProject\InputData as CreateProjectInputData;
use App\UseCases\GetProject\GetProject;
use App\UseCases\ListProject\ListProject;
use App\UseCases\UpdateProject\InputData as UpdateProjectInputData;
use App\UseCases\UpdateProject\UpdateProject;
use App\Utils\ControllerExceptionHandler;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListProject $listProject)
    {
        return Inertia::render('Project/Projects', [
            'projects' => $listProject->execute()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('ProjectAdd');
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GetProject $getProject, string $id)
    {
        return Inertia::render('Project/Project', [
            'project' => $getProject->execute($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, UpdateProject $updateProject, string $id)
    {
        try {
            $updateProject->execute($id, new UpdateProjectInputData($request->validated()));

            return response()->json([
                'status' => true,
            ], Response::HTTP_OK); // 200
        } catch (ClientException | ServerException $error) {
            return ControllerExceptionHandler::handle($error);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
