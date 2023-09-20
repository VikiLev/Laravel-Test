<?php

namespace App\Http\Controllers;

use App\Models\TestTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
//        $this->authorize('index', TestTable::class);
//        $query = TestTable::where('user_id', auth()->id());
//
//        if ($request->has('status')) {
//            $query->where('status', $request->input('status'));
//        }
//
//        if ($request->has('priority')) {
//            $query->where('priority', $request->input('priority'));
//        }
//
//        if ($request->has('title')) {
//            $query->where('title', $request->input('title'));
//        }
//
//        if ($request->has('sort') && $request->input('sort') === 'created_at') {
//            $query->orderBy('created_at', 'asc'); // or 'desc' for descending order
//        }
//
//        if ($request->has('sort') && $request->input('sort') === 'priority') {
//            $query->orderBy('priority', 'asc'); // or 'desc' for descending order
//        }
//
//        $tasks = $query->get();

        $tasks = TestTable::where(function ($query) use ($request) {
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }
        })->get();

        return response()->json(['tasks' => $tasks]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:todo,done',
            'priority' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id', // Validate user_id exists in the 'users' table
            'parent_id' => 'nullable|exists:tasks,id', // Validate parent_id exists in the 'tasks' table
        ]);

        $task = TestTable::create($request->all());

        return response()->json(['task' => $task], 201); // Return the created task with a 201 status code
    }

    public function show(TestTable $task)
    {
        return response()->json(['task' => $task]);
    }

    public function update(Request $request, TestTable $task)
    {
        $request->validate([
            'status' => 'in:todo,done',
            'priority' => 'integer|min:1|max:5',
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'exists:users,id', // Validate user_id exists in the 'users' table
            'parent_id' => 'nullable|exists:tasks,id', // Validate parent_id exists in the 'tasks' table
        ]);

        $task->update($request->all());

        return response()->json(['task' => $task]);
    }

    public function destroy(TestTable $task)
    {
//        $this->authorize('delete', $task);

        if ($task->status === 'done') {
            return response()->json(['message' => 'Cannot delete a completed task'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted']);
    }

    public function markComplete(TestTable $task)
    {
//        $this->authorize('markComplete', $task);

        // Check if the task has uncompleted subtasks
        if ($task->subtasks()->where('status', '!=', 'done')->exists()) {
            return response()->json(['message' => 'Task has uncompleted subtasks'], 422);
        }

        $task->update(['status' => 'done']);

        return response()->json(['task' => $task]);
    }
}
