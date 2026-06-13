<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TodoController extends Controller
{
    public function index(): View
    {
        return view('todos.index', [
            'todos' => Todo::latestFirst()->get(),
            'openCount' => Todo::incomplete()->count(),
            'completedCount' => Todo::completed()->count(),
        ]);
    }

    public function store(): RedirectResponse
    {
        $validated = request()->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        Todo::create($validated + [
            'user_id' => request()->user()?->id,
            'is_completed' => false,
        ]);

        return redirect()->route('todos.index')->with('status', 'Todo added.');
    }

    public function toggle(Todo $todo): RedirectResponse
    {
        $todo->toggleCompletion();

        return redirect()->route('todos.index');
    }

    public function destroy(Todo $todo): RedirectResponse
    {
        $todo->delete();

        return redirect()->route('todos.index')->with('status', 'Todo deleted.');
    }
}
