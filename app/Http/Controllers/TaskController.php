<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);
    
        Auth::user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
    
        return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso!');
    }

    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }
        return view('tasks.edit', compact('task'));
    }
public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        if ($request->has('is_completed') && !$request->has('title')) {
        $task->update([
            'is_completed' => $request->input('is_completed'),
        ]);
        return redirect()->route('tasks.index')->with('success', 'Status da tarefa atualizado!');
    }


        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'is_completed' => 'boolean',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => $request->has('is_completed'),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Tarefa excluída com sucesso!');
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $query = $user->tasks();

        if ($request->has('status') && in_array($request->status, ['completed', 'pending'])) {
            $is_completed = ($request->status === 'completed');
            $query->where('is_completed', $is_completed);
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();
        return view('tasks.index', compact('tasks'));
    }
}
