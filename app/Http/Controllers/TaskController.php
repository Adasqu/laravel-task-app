<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\TaskRevision;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Task::where('user_id', auth()->id());

            // Filtrowanie według priorytetu, statusu i terminu
            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('due_date')) {
                $query->whereDate('due_date', $request->due_date);
            }

            $tasks = $query->orderBy('due_date')->paginate(10);

            return view('tasks.index', compact('tasks'));
        } catch (\Throwable $th) {
            return view('tasks.index')->with('error', 'Wystąpił błąd podczas wyświetlania zadań.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
                'priority'    => 'required|in:low,medium,high',
                'status'      => 'required|in:to-do,in progress,done',
                'due_date'    => 'required|date',
            ]);

            $data['user_id'] = auth()->id();

            Task::create($data);

            return redirect()->route('tasks.index')->with('success', 'Zadanie zostało dodane.');
        } catch (\Throwable $th) {
            return redirect()->route('tasks.index')->with('error', 'Błąd: Zadanie nie zostało dodane.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
        return view('tasks.edit', compact('task'))->with('success', 'Zadanie zostało zaktualizowane.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
        try {
            $data = $request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
                'priority'    => 'required|in:low,medium,high',
                'status'      => 'required|in:to-do,in progress,done',
                'due_date'    => 'required|date',
            ]);

            // Opcjonalne: zapis historii zmian
            $changes = [];
            foreach ($data as $key => $newValue) {
                if ($task->{$key} != $newValue) {
                    $changes[$key] = [
                        'old' => $task->{$key},
                        'new' => $newValue
                    ];
                }
            }
            if (!empty($changes)) {
                TaskRevision::create([
                    'task_id'  => $task->id,
                    'user_id'  => auth()->id(),
                    'changes'  => $changes,
                    'changed_at' => now(),
                ]);
            }

            $task->update($data);

            return redirect()->route('tasks.index')->with('success', 'Zadanie zostało zaktualizowane.');
        } catch (\Throwable $th) {
            return redirect()->route('tasks.index')->with('error', 'Wystąpił błąd podczas aktualizacji.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
        try {
            $task->delete();
            return redirect()->route('tasks.index')->with('success', 'Zadanie zostało usunięte.');
        } catch (\Throwable $th) {
            return redirect()->route('tasks.index')->with('error', 'Kasowanie nie powiodło się.');
        }
    }
    public function generateShareLink(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        // Generate random token and set expiration to 1h
        try {
            $token = Str::random(32);
            $task->update([
                'share_token'             => $token,
                'share_token_expiration'  => now()->addHour(),
            ]);

            $link = route('tasks.public', ['token' => $token]);

            return view('tasks.share', compact('link'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function addToCalendar(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }


        try {
            $event = new Event;
            $event->name = $task->name;
            $event->description = $task->description;
            $event->startDateTime = Carbon::now();
            $event->endDateTime = Carbon::parse($task->due_date);
            //$event->addAttendee(['email'=>auth()->user()->email]); // uncoment if is G suite account


            $event->save();

            return redirect()->route('tasks.show', $task)
                ->with('success', 'Zadanie zostało dodane do Google Calendar.');
        } catch (\Throwable $th) {
            return redirect()->route('tasks.show', $task)
                ->with('success', 'Wystąpił błąd i zadanie nie zostało dodane do Google Calendar.');
        }
    }

    public function showPublic($token)
    {
        $task = Task::where('share_token', $token)
            ->where('share_token_expiration', '>', now())
            ->firstOrFail();

        return view('tasks.public', compact('task'));
    }
}
