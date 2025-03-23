<x-app-layout>
     <h1>Edycja zadania: {{ $task->name }}</h1>

  <!-- Wyświetlanie błędów walidacji -->
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('tasks.update', $task) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label for="name" class="form-label">Nazwa zadania</label>
      <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $task->name) }}" required maxlength="255">
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Opis (opcjonalnie)</label>
      <textarea name="description" id="description" class="form-control">{{ old('description', $task->description) }}</textarea>
    </div>

    <div class="mb-3">
      <label for="priority" class="form-label">Priorytet</label>
      <select name="priority" id="priority" class="form-select" required>
        <option value="low" {{ old('priority', $task->priority)=='low' ? 'selected' : '' }}>Low</option>
        <option value="medium" {{ old('priority', $task->priority)=='medium' ? 'selected' : '' }}>Medium</option>
        <option value="high" {{ old('priority', $task->priority)=='high' ? 'selected' : '' }}>High</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
      <select name="status" id="status" class="form-select" required>
        <option value="to-do" {{ old('status', $task->status)=='to-do' ? 'selected' : '' }}>To-Do</option>
        <option value="in progress" {{ old('status', $task->status)=='in progress' ? 'selected' : '' }}>In Progress</option>
        <option value="done" {{ old('status', $task->status)=='done' ? 'selected' : '' }}>Done</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="due_date" class="form-label">Termin wykonania</label>
      <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', $task->due_date) }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Aktualizuj zadanie</button>
    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Anuluj</a>
  </form>
</x-app-layout>