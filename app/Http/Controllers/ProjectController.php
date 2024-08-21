<?php

namespace App\Http\Controllers;

use App\Http\Exceptions\ClientException;
use App\Http\Exceptions\ServerException;
use App\Http\Requests\StoreProjectRequest;
use App\UseCases\CreateProject\CreateProject;
use App\UseCases\CreateProject\InputData as CreateProjectInputData;
use App\Utils\ControllerExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request, CreateProject $createProject)
    {
        try {
            $output = $createProject->execute(new CreateProjectInputData($request->validated()));

            return response()->json([
                'status' => true,
                'id' => $output->id
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
