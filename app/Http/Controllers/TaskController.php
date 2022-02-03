<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())->get();

        return response()->json(['status' => 'success', 'data' => $tasks]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'priority' => 'required|string|in:low,medium,high',
            'description' => 'nullable|string'
        ]);

        $task = auth()->user()->tasks()->create($request->all());

        return response()->json(['status' => 'success', 'data' => $task]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'priority' => 'required|string|in:low,medium,high',
            'description' => 'nullable|string'
        ]);

        $task = Task::findOrFail($id);

        $task->update($request->all());

        return response()->json(['status' => 'success', 'data' => $task]);
    }

    public function destroy($id)
    {
        Task::findOrFail($id)->delete();

        return response()->json(['status' => 'success']);
    }
}
