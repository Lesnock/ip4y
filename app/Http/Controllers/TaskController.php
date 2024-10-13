<?php

namespace App\Http\Controllers;

use App\Http\Exceptions\ClientException;
use App\Http\Exceptions\ServerException;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\UseCases\CreateTask\InputData as CreateTaskInputData;
use App\UseCases\CreateTask\CreateTask;
use App\UseCases\DeleteTask\DeleteTask;
use App\UseCases\GetTask\GetTask;
use App\UseCases\UpdateTask\UpdateTask;
use App\Utils\ControllerExceptionHandler;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request, CreateTask $createTask, GetTask $getTask)
    {
        try {
            $output = $createTask->execute(new CreateTaskInputData($request->validated()));
            $task = $getTask->execute($output->id);

            Cache::forget("project-pdf-{$task->project_id}");
            Cache::forget("project-xlsx-{$task->project_id}");

            return response()->json([
                'status' => true,
                'task' => $task
            ], Response::HTTP_CREATED); // 201
        } catch (ClientException | ServerException $error) {
            return ControllerExceptionHandler::handle($error);
        }
    }

    public function update(UpdateTaskRequest $request, UpdateTask $updateTask, string $id)
    {
        $task = Task::findOrFail($id);

        try {
            $updateTask->execute($id, $request->all());
            
            Cache::forget("project-pdf-{$task->project_id}");
            Cache::forget("project-xlsx-{$task->project_id}");

            return response()->json([
                'status' => true,
            ], Response::HTTP_OK); // 200
        } catch (ClientException | ServerException $error) {
            return ControllerExceptionHandler::handle($error);
        }
    }

    public function destroy(DeleteTask $deleteTask, string $id)
    {
        $task = Task::findOrFail($id);

        try {
            $deleteTask->execute($id);
            Cache::forget("project-pdf-{$task->project_id}");
            Cache::forget("project-xlsx-{$task->project_id}");
            return response()->json([
                'status' => true,
            ], Response::HTTP_OK); // 200
        } catch (ClientException | ServerException $error) {
            return ControllerExceptionHandler::handle($error);
        }
    }
}
