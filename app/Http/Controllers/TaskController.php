<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer',
        ]);

        $task = Task::create([
            'name' => $request->input('name'),
            'duration' => $request->input('duration'),
            'status' => 0,
            'user_id' => Auth::id(),
        ]);

        return response()->json($task, 201);
    }

    public function index()
    {
        return response()->json(Task::with('user')->get(), 200);
    }

    public function show($id)
    {
        $task = Task::with('user')->findOrFail($id);
        return response()->json($task, 200);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'duration' => 'sometimes|required|integer|min:1',
            'status' => 'sometimes|required|integer|in:0,1,2',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task->update($request->all());

        return response()->json($task, 200);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }

    public function tasksByUser($userId)
    {
        $user = User::findOrFail($userId);
        $tasks = $user->tasks; // İlgili kullanıcının görevleri
        return response()->json($tasks, 200);
    }
    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task->status = $request->status;
        $task->save();

        return response()->json($task, 200);
    }
}
