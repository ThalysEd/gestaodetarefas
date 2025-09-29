<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minhas Tarefas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Adicionar Nova Tarefa
                        </a>

                        <div class="flex space-x-2">
                            <a href="{{ route('tasks.filter', ['status' => 'pending']) }}" class="px-3 py-1 text-sm rounded-md border {{ request('status') === 'pending' ? 'bg-indigo-600 text-white' : 'border-gray-300 text-gray-700' }}">Pendentes</a>
                            <a href="{{ route('tasks.filter', ['status' => 'completed']) }}" class="px-3 py-1 text-sm rounded-md border {{ request('status') === 'completed' ? 'bg-indigo-600 text-white' : 'border-gray-300 text-gray-700' }}">Concluídas</a>
                            <a href="{{ route('tasks.index') }}" class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700">Todas</a>
                        </div>
                    </div>
                    
                    @forelse ($tasks as $task)
                        <div class="flex items-center justify-between border-b last:border-b-0 py-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold {{ $task->is_completed ? 'line-through text-gray-500' : '' }}">{{ $task->title }}</h3>
                                <p class="text-sm text-gray-600">{{ $task->description }}</p>
                                <p class="text-xs text-gray-400 mt-1">Criada em: {{ $task->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="flex space-x-2 items-center">
                                <form action="{{ route('tasks.update', $task) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_completed" value="{{ $task->is_completed ? 0 : 1 }}">
                                    <button type="submit" class="px-2 py-1 text-xs rounded-md {{ $task->is_completed ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white">
                                        {{ $task->is_completed ? 'Marcar como Pendente' : 'Marcar como Concluída' }}
                                    </button>
                                </form>
                                <a href="{{ route('tasks.edit', $task) }}" class="px-2 py-1 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                                    Editar
                                </a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 text-xs bg-red-500 hover:bg-red-600 text-white rounded-md" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center">Nenhuma tarefa encontrada.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>