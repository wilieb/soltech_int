<?php

namespace App\Http\Controllers;

use App\Models\UserTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserTaskResource;

use Illuminate\Http\Request;

class User_taskController extends Controller
{
    public function home()
    {
        $userTasks = UserTask::where('user_id', Auth::id())->get();

        return UserTaskResource::collection($userTasks);
    }

    public function saveUserTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'due_date' => 'required|date',
            'start_time' => 'nullable|date_format:Y-m-d H:i:s',
            'end_time' => 'nullable|date_format:Y-m-d H:i:s|after:start_time',
            'remarks' => 'nullable|string',
            'status_id' => 'required|exists:status,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $userTask = new UserTask([
            'user_id' => Auth::id(),
            'task_id' => $request->input('task_id'),
            'due_date' => $request->input('due_date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'remarks' => $request->input('remarks'),
            'status_id' => $request->input('status_id')
        ]);

        $userTask->save();

        return new UserTaskResource($userTask);
    }

    public function showUserTask(UserTask $userTask)
    {
        if ($userTask->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        return new UserTaskResource($userTask);
    }

    public function updateUserTask(Request $request, UserTask $userTask)
    {
        if ($userTask->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'due_date' => 'required|date',
            'start_time' => 'nullable|date_format:Y-m-d H:i:s',
            'end_time' => 'nullable|date_format:Y-m-d H:i:s|after:start_time',
            'remarks' => 'nullable|string',
            'status_id' => 'required|exists:status,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $userTask->task_id = $request->input('task_id');
        $userTask->due_date = $request->input('due_date');
        $userTask->start_time = $request->input('start_time');
        $userTask->end_time = $request->input('end_time');
        $userTask->remarks = $request->input('remarks');
        $userTask->status_id = $request->input('status_id');
    
        $userTask->save();
    
        return new UserTaskResource($userTask);
    }
    
    public function deleteUserTask(UserTask $userTask)
    {
        if ($userTask->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
    
        $userTask->delete();
    
        return response()->json(null, 204);
    }

}
