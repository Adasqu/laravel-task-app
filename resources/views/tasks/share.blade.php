<x-app-layout>
     <h1>Udostępnianie zadania</h1>
  @if(isset($link))
    <p>Poniżej znajduje się link publiczny do zadania:</p>
    <div class="alert alert-info">
      <a href="{{ $link }}" target="_blank">{{ $link }}</a>
    </div>
    <p>Link będzie aktywny przez ograniczony czas.</p>
  @else
    <p>Link do udostępnienia nie został wygenerowany.</p>
  @endif
  <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Powrót do listy</a>
</x-app-layout>