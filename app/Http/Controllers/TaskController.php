<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        return TaskResource::collection($tasks);
    }

    public function saveTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $task = new Task([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'due_date' => $request->input('due_date')
        ]);

        $task->save();

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function updateTask(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->due_date = $request->input('due_date');

        $task->save();

        return new TaskResource($task);
    }

    public function deleteTask(Task $task)
    {
        $task->delete();

        return response()->json(null, 204);
    }
}
