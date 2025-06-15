<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Group;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskStatusUpdatedNotification;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('users')->get();
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
            'assigned_user_id' => 'nullable|exists:users,id',
            'assigned_group_id' => 'nullable|exists:groups,id',
            'due_date' => 'required|date',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xlsx,ppt,pptx,jpg,jpeg,png|max:20480', // max dalam kilobytes (20MB)
        ]);

        if (!$request->assigned_user_id && !$request->assigned_group_id) {
            return back()->withErrors(['assignment' => 'You must assign the task to either a user or a group.'])->withInput();
        }

        $data = $request->only(['title', 'description', 'due_date']);
        $data['created_by'] = Auth::id();
        $data['status'] = 'not started';

        // Handle polymorphic assignment
        if ($request->assigned_user_id) {
            $data['assigned_user_id'] = $request->assigned_user_id;
            $data['assigned_to_type'] = User::class;
        } elseif ($request->assigned_group_id) {
            $data['assigned_user_id'] = $request->assigned_group_id;
            $data['assigned_to_type'] = Group::class;
        }

        $task = Task::create($data);

        // assign to users in the selected group
        if ($task->assigned_to_type === Group::class) {
            $group = Group::with('users')->find($task->assigned_user_id);
            if ($group) {
                $task->users()->sync($group->users->pluck('id')->toArray());

                // Optional: Notify each user in the group
                foreach ($group->users as $member) {
                    $member->notify(new TaskAssignedNotification($task));
                }
            }
        }


        // Store multiple uploaded documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->store('staff_documents', 'public');

                Document::create([
                    'task_id' => $task->id,
                    'user_id' => Auth::id(),
                    'original_name' => $originalName,
                    'filename' => $path,
                ]);
            }
        }

        // Notify assigned user
        if ($task->assigned_to_type === User::class) {
            $staff = User::find($task->assigned_user_id);
            if ($staff) {
                $staff->notify(new TaskAssignedNotification($task));
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Task created and notification sent.');
    }

    public function show(string $id)
    {
        $task = Task::with(['users', 'comments.user', 'documents.user'])->findOrFail($id);
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

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,id',
            'due_date' => 'required|date',
            'status' => 'nullable|string|in:pending,in progress,finished',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xlsx,ppt,pptx,jpg,jpeg,png|max:20480', // max dalam kilobytes (20MB)
        ]);

        $data = $request->only(['title', 'description', 'assigned_user_id', 'due_date', 'status']);

        $task->update($data);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->store('staff_documents', 'public');

                Document::create([
                    'task_id' => $task->id,
                    'user_id' => Auth::id(),
                    'original_name' => $originalName,
                    'filename' => $path,
                ]);
            }
        }

        if ($request->assigned_user_id && $request->assigned_user_id != $task->getOriginal('assigned_user_id')) {
            $newAssignee = User::find($request->assigned_user_id);
            if ($newAssignee) {
                $newAssignee->notify(new TaskAssignedNotification($task));
            }
        }

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
        $document = Document::findOrFail($id);

        if (!Storage::disk('public')->exists($document->filename)) {
            abort(404, 'Document not found.');
        }

        return response()->download(
            storage_path('app/public/' . $document->filename),
            $document->original_name
        );
    }

    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);

        foreach ($task->documents as $doc) {
            Storage::disk('public')->delete($doc->filename);
            $doc->delete();
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    public function allTaskActivities()
    {
        $head = Auth::user();

        $tasks = Task::with('documents.user')->whereHas('users')->get();

        return view('academic-head.tasks.activities', compact('tasks'));
    }


}
