<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Group;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;

class AcademicHeadController extends Controller
{
    public function dashboard()
    {
        return view('academic_head.dashboard');
    }

    // ---------------- Tasks ----------------

    public function viewTasks()
    {
        $tasks = Task::with(['users', 'group'])
                    ->where('created_by', Auth::id()) // hanya task yang dia sendiri assign
                    ->paginate(10);

        return view('academic_head.tasks.index', compact('tasks'));
    }

    public function createTask()
    {
        $users = User::where('role_id', '!=', 1)->get();
        $groups = Group::all();
        return view('academic_head.tasks.create', compact('users', 'groups'));
    }

    public function storeTask(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'assigned_user_id' => 'nullable|array',
            'assigned_user_id.*' => 'exists:users,id',
            'group_id' => 'nullable|exists:groups,id',
            'documents.*' => 'nullable|mimes:pdf,doc,docx,xlsx,ppt,pptx,jpg,jpeg,png|max:20480', // max dalam kilobytes (20MB)
        ]);
        $validated['created_by'] = Auth::id();

        if (!empty($validated['assigned_user_id']) && !empty($validated['group_id'])) {
            return back()->withErrors('Please assign the task to either users or a group, not both.')->withInput();
        }

        // Store task
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'group_id' => $validated['group_id'] ?? null,
            'created_by' => Auth::id(),
        ]);

        // Save documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $originalName = $file->getClientOriginalName();
                $filename = time() . '_' . $originalName;
                $path = $file->storeAs('documents', $filename, 'public');

                \App\Models\Document::create([
                    'task_id' => $task->id,
                    'user_id' => Auth::id(),
                    'filename' => $path,
                    'original_name' => $originalName,
                ]);
            }
        }

        // Sync users (manual selection)
        if (!empty($validated['assigned_user_id'])) {
            $task->users()->sync($validated['assigned_user_id']);

            // Notify manually assigned users
            foreach ($task->users as $user) {
                $user->notify(new TaskAssignedNotification($task));
            }
        }

        // Assign group members
        elseif (!empty($validated['group_id'])) {
            $group = Group::with('users')->find($validated['group_id']);
            if ($group) {
                $task->users()->sync($group->users->pluck('id')->toArray());

                // Notify group-assigned users
                foreach ($group->users as $user) {
                    $user->notify(new TaskAssignedNotification($task));
                }
            }
        }

        return redirect()->route('academic-head.tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['users', 'group', 'comments.user']);
        return view('academic_head.tasks.show', compact('task')); // ← fixed path
    }

    public function edit(Task $task)
    {
        $task->load(['users', 'group']);
        $users = User::where('role_id', '!=', 1)->get();
        $groups = Group::all();
        return view('academic_head.tasks.edit', compact('task', 'users', 'groups'));
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_user_id' => 'nullable|array',
            'assigned_user_id.*' => 'exists:users,id',
            'group_id' => 'nullable|exists:groups,id',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,txt|max:2048',
        ]);

        if (!empty($validated['assigned_user_id']) && !empty($validated['group_id'])) {
            return back()->withErrors('Please assign the task to either users or a group, not both.')->withInput();
        }

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'group_id' => $validated['group_id'] ?? null,
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $originalName = $file->getClientOriginalName();
                $filename = time() . '_' . $originalName;
                $path = $file->storeAs('documents', $filename, 'public');

                \App\Models\Document::create([
                    'task_id' => $task->id,
                    'user_id' => Auth::id(),
                    'filename' => $path,
                    'original_name' => $originalName,
                ]);
            }
        }

        if (!empty($validated['assigned_user_id'])) {
            $task->users()->sync($validated['assigned_user_id']);
        } else {
            $task->users()->detach();
        }

        return redirect()->route('academic-head.tasks.index')->with('success', 'Task updated successfully.');
    }


    public function download($id)
    {
        $task = Task::findOrFail($id);

        if (!$task->document || !Storage::disk('public')->exists($task->document)) {
            abort(404, 'Document not found.');
        }

        $filePath = storage_path('app/public/' . $task->document);
        $originalName = $task->original_name ?? basename($task->document);

        return response()->download($filePath, $originalName);
    }

    public function destroy(Task $task)
    {
        $task->users()->detach();
        $task->delete();
        return redirect()->route('academic-head.tasks.index')->with('success', 'Task deleted successfully.');
    }

    // ---------------- Groups ----------------

    public function viewGroups()
    {
        $groups = Group::all();
        return view('academic_head.groups.index', compact('groups'));
    }

    public function createGroup()
    {
        $users = User::all();
        return view('academic_head.groups.form', ['users' => $users]);
    }

    public function storeGroup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $group = Group::create($validated);
        if ($request->filled('users')) {
            $group->users()->sync($request->users);
        }

        return redirect()->route('academic-head.groups.index')->with('success', 'Group created successfully.');
    }

    public function showGroup($id)
    {
        $group = Group::with('users')->findOrFail($id);
        return view('academic_head.groups.show', compact('group'));
    }

    public function editGroup($id)
    {
        $group = Group::with('users')->findOrFail($id);
        $users = User::all();
        return view('academic_head.groups.form', compact('group', 'users'));
    }

    public function updateGroup(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $group->update($validated);
        if ($request->filled('users')) {
            $group->users()->sync($request->users);
        }

        return redirect()->route('academic-head.groups.index')->with('success', 'Group updated successfully.');
    }

    public function destroyGroup($id)
    {
        $group = Group::findOrFail($id);
        $group->users()->detach();
        $group->delete();
        return redirect()->route('academic-head.groups.index')->with('success', 'Group deleted successfully.');
    }

    // ---------------- Comments ----------------

    public function storeComment(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'task_id' => $validated['task_id'],
            'user_id' => Auth::id(),
            'content' => $validated['content'], // Match your database field
        ]);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    public function editComment($id)
    {
        $comment = Comment::findOrFail($id);
        return view('academic_head.comments.edit', compact('comment'));
    }

    public function updateComment(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string|max:1000', // Make sure it matches the form input
        ]);

        $comment->update($validated);
        return redirect()->back()->with('success', 'Comment updated successfully.');
    }

    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

    public function allTaskActivities()
    {
        $tasks = Task::with(['users', 'documents.user'])
            ->where('created_by', Auth::id()) // hanya ambil task yang user ni buat
            ->paginate(10);

        return view('academic_head.tasks.activities', compact('tasks'));
    }

}
