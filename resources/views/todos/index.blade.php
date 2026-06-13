<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Todos - {{ config('app.name', 'Laravel') }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-50 text-zinc-900">
        <main class="mx-auto flex min-h-screen w-full max-w-4xl flex-col px-6 py-10">
            <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <h1 class="text-3xl font-semibold">Todo List</h1>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-md border border-zinc-200 bg-white px-4 py-3 shadow-sm">
                        <p class="text-zinc-500">Open</p>
                        <p class="text-2xl font-semibold">{{ $openCount }}</p>
                    </div>
                    <div class="rounded-md border border-zinc-200 bg-white px-4 py-3 shadow-sm">
                        <p class="text-zinc-500">Completed</p>
                        <p class="text-2xl font-semibold">{{ $completedCount }}</p>
                    </div>
                </div>
            </header>

            @if (session('status'))
                <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('todos.store') }}" class="mb-6 rounded-md border border-zinc-200 bg-white p-4 shadow-sm">
                @csrf

                <div class="flex flex-col gap-3 sm:flex-row">
                    <div class="flex-1">
                        <label for="title" class="sr-only">Todo title</label>
                        <input
                            id="title"
                            name="title"
                            type="text"
                            value="{{ old('title') }}"
                            placeholder="Add a todo"
                            class="w-full rounded-md border border-zinc-300 px-4 py-2 text-base outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                            required
                            maxlength="255"
                        >
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="rounded-md bg-emerald-700 px-5 py-2 font-medium text-white transition hover:bg-emerald-800">
                        Add Todo
                    </button>
                </div>
            </form>

            <section class="overflow-hidden rounded-md border border-zinc-200 bg-white shadow-sm">
                @forelse ($todos as $todo)
                    <article class="flex flex-col gap-3 border-b border-zinc-100 p-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start gap-3">
                            <form method="POST" action="{{ route('todos.toggle', $todo) }}">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full border {{ $todo->is_completed ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-zinc-300 text-transparent hover:border-emerald-600' }}"
                                    aria-label="{{ $todo->is_completed ? 'Mark incomplete' : 'Mark complete' }}"
                                >
                                    <span aria-hidden="true">&check;</span>
                                </button>
                            </form>

                            <div>
                                <h2 class="text-base font-medium {{ $todo->is_completed ? 'text-zinc-400 line-through' : 'text-zinc-900' }}">
                                    {{ $todo->title }}
                                </h2>
                                <p class="mt-1 text-sm text-zinc-500">
                                    {{ $todo->is_completed ? 'Completed' : 'Open' }} - Created {{ $todo->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('todos.destroy', $todo) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-md border border-red-200 px-3 py-1.5 text-sm font-medium text-red-700 transition hover:bg-red-50">
                                Delete
                            </button>
                        </form>
                    </article>
                @empty
                    <div class="p-8 text-center">
                        <h2 class="text-lg font-semibold">No todos yet</h2>
                        <p class="mt-2 text-sm text-zinc-500">Add your first todo above.</p>
                    </div>
                @endforelse
            </section>
        </main>
    </body>
</html>
