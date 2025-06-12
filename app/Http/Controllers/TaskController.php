<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskStatusUpdatedNotification;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('users')->get(); // not 'assignedTo'
        return view('tasks.index', compact('tasks'));
    }
    public function create()
    {
        $users = User::all();
        $groups = Group::all();
        return view('tasks.create', compact('users', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to_id' => 'nullable|exists:users,id',
            'assigned_group_id' => 'nullable|exists:groups,id',
            'due_date' => 'required|date',
            'document' => 'nullable|file|mimes:pdf,docx,txt,jpg,png|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'due_date']);
        $data['created_by'] = Auth::id();
        $data['status'] = 'not started';

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $filename, 'public');

            $data['document'] = $path;
            $data['original_filename'] = $file->getClientOriginalName(); // save original name
}


        if ($request->assigned_to_id) {
            $data['assigned_to_id'] = $request->assigned_to_id;
            $data['assigned_to_type'] = User::class;
        } elseif ($request->assigned_group_id) {
            $data['assigned_to_id'] = $request->assigned_group_id;
            $data['assigned_to_type'] = Group::class;
        }

        $task = Task::create($data);

        // Notify the assigned user (if individual assignment)
        if ($task->assigned_to_type === User::class) {
            $staff = User::find($task->assigned_to_id);
            if ($staff) {
                $staff->notify(new TaskAssignedNotification($task));
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Task created and notification sent.');
    }
    
    public function show(string $id)
    {
        $task = Task::with(['users', 'comments.user'])->findOrFail($id);
        return view('tasks.show', compact('task'));
    }

    public function edit(string $id)
    {
        $task = Task::findOrFail($id);
        $users = User::all();
        $groups = Group::all();

        return view('tasks.edit', compact('task', 'users', 'groups'));
    }

public function update(Request $request, Task $task)
{
    $originalStatus = $task->status;

    // Validate all fields, but status is nullable (can be updated or not)
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'assigned_to_id' => 'nullable|exists:users,id', // nullable so it can stay unchanged
        'due_date' => 'required|date',
        'status' => 'nullable|string|in:pending,in progress,finished',
        'document' => 'nullable|file|mimes:pdf,docx,txt,jpg,png|max:2048',
    ]);

    $data = $request->only(['title', 'description', 'assigned_to_id', 'due_date', 'status']);

    if ($request->hasFile('document')) {
        $data['document'] = $request->file('document')->store('documents', 'public');
    }

    // Update task
    $task->update($data);

    // Notify new assignee if changed
    if ($request->assigned_to_id && $request->assigned_to_id != $task->getOriginal('assigned_to_id')) {
        $newAssignee = User::find($request->assigned_to_id);
        if ($newAssignee) {
            $newAssignee->notify(new TaskAssignedNotification($task));
        }
    }

    // Notify creator if status changed
    if (isset($data['status']) && $originalStatus !== $data['status'] && $task->created_by) {
        $creator = User::find($task->created_by);
        if ($creator) {
            $creator->notify(new TaskStatusUpdatedNotification($task, Auth::user()));
        }

        Log::info("Task status changed from '{$originalStatus}' to '{$data['status']}' for task ID: {$task->id}");
    }

    return redirect()->back()->with('success', 'Task updated successfully.');
}
    public function download($id)
    {
        $task = Task::findOrFail($id);

        if (!$task->document || !Storage::disk('public')->exists($task->document)) {
            abort(404, 'Document not found.');
        }

        $filePath = storage_path('app/public/' . $task->document);
        $originalName = $task->original_filename ?? basename($task->document);

        return response()->download($filePath, $originalName);
    }


    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);

        if ($task->document) {
            Storage::disk('public')->delete($task->document);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }
}
