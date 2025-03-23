<x-app-layout>
    <h1>{{ $task->name }}</h1>

    <div class="mb-3">
        <strong>Opis:</strong>
        <p>{{ $task->description ?? 'Brak opisu' }}</p>
    </div>

    <div class="mb-3">
        <strong>Priorytet:</strong> {{ ucfirst($task->priority) }}
    </div>

    <div class="mb-3">
        <strong>Status:</strong> {{ ucfirst($task->status) }}
    </div>

    <div class="mb-3">
        <strong>Termin wykonania:</strong> {{ $task->due_date }}
    </div>

    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">Edytuj</a>
    <a href="{{ route('tasks.share', $task) }}" class="btn btn-info">Udostępnij zadanie</a>

    <form action="{{ route('tasks.calendar', $task) }}" method="POST" style="display:inline-block;">
        @csrf
        <button type="submit" class="btn btn-secondary">Dodaj do Google Calendar</button>
    </form>


    <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
            onclick="return confirm('Czy na pewno chcesz usunąć to zadanie?')">Usuń zadanie</button>
    </form>

    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Powrót do listy</a>


    <h3>Historia zmian</h3>
    @if ($task->revisions->isNotEmpty())
    @foreach ($task->revisions as $revision)
    <div class="card mb-3">
        <div class="card-header">
            Zmiana z dnia: {{ $revision->changed_at->format('d-m-Y H:i') }}
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach ($revision->changes as $field => $change)
                <li class="list-group-item">
                    <strong>{{ ucfirst($field) }}:</strong>
                    zmieniono z <em>{{ $change['old'] }}</em> na <em>{{ $change['new'] }}</em>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endforeach
    @else
    <p>Brak historii zmian dla tego zadania.</p>
    @endif
</x-app-layout>