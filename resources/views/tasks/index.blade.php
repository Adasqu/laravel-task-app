<x-app-layout>
    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif
    <form method="GET" action="{{ route('tasks.index') }}">
        <label>Priorytet:
            <select name="priority">
                <option value="">-- wybierz --</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
        </label>

        <label>Status:
            <select name="status">
                <option value="">-- wybierz --</option>
                <option value="to-do">To-Do</option>
                <option value="in progress">In Progress</option>
                <option value="done">Done</option>
            </select>
        </label>

        <label>Termin:
            <input type="date" name="due_date">
        </label>

        <button type="submit">Filtruj</button>
    </form>
    @if ($tasks->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nazwa zadania</th>
                    <th>Priorytet</th>
                    <th>Status</th>
                    <th>Termin</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td><a href="{{ route('tasks.show', $task) }}">{{ $task->name }}</a></td>
                        <td>{{ ucfirst($task->priority) }}</td>
                        <td>{{ ucfirst($task->status) }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td>
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-warning">Edytuj</a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Czy na pewno chcesz usunąć zadanie?')">Usuń</button>
                            </form>
                            <a href="{{ route('tasks.share', $task) }}" class="btn btn-sm btn-info">Udostępnij</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tasks->withQueryString()->links() }}
    @else
        <p>Brak zadań do wyświetlenia.</p>
    @endif

    <a href="{{ route('tasks.create') }}" class="btn btn-success">Dodaj nowe zadanie</a>
</x-app-layout>
