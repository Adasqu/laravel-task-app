<x-guest-layout>
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

  <p class="text-muted"><em>Widok publiczny – nie wymaga logowania.</em></p>
  <a href="{{ url()->previous() }}" class="btn btn-secondary">Powrót</a>
</x-guest-layout>