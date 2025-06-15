<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Group;
use Illuminate\Support\Facades\Log;
use App\Notifications\TaskStatusUpdatedNotification;


class AcademicStaffController extends Controller
{
    public function dashboard()
    {
        return view('academic_staff.dashboard');
    }

    // ---------------- Tasks ----------------

    public function viewTasks()
    {
        $userId = Auth::id();

        $tasks = Task::whereHas('users', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orWhereHas('group.users', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with(['users', 'group', 'comments']) // eager-load if needed
            ->get();

        return view('academic_staff.tasks.index', compact('tasks'));
    }

    public function show($id)
    {
        $userId = Auth::id();

        $task = Task::with(['users', 'group', 'comments'])
            ->where(function ($q) use ($userId) {
                $q->whereHas('users', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->orWhereHas('group.users', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            })
            ->find($id);

        if (!$task) {
            abort(403, 'You are not authorized to view this task.');
        }

        return view('academic_staff.tasks.show', compact('task'));
    }

    public function edit($id)
    {
        $userId = Auth::id();

        $task = Task::where(function ($q) use ($userId) {
                $q->whereHas('users', fn($q) => $q->where('user_id', $userId))
                ->orWhereHas('group.users', fn($q) => $q->where('user_id', $userId));
            })
            ->with(['users', 'group'])
            ->findOrFail($id);

        return view('academic_staff.tasks.edit', compact('task'));
    }


    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        // Validate input
        $validated = $request->validate([
            'status' => 'required|in:pending,in progress,finished',
        ]);

        // Fetch task
        $task = Task::findOrFail($id);
        $originalStatus = $task->status;

        $task->status = $validated['status'];
        $task->save();

        Log::info('Task updated successfully', [
            'task_id' => $task->id,
            'new_status' => $task->status,
        ]);

        // Notify the Academic Head if status has changed
        if ($originalStatus !== $validated['status'] && $task->created_by) {
            $creator = \App\Models\User::find($task->created_by);
            if ($creator) {
                $creator->notify(new TaskStatusUpdatedNotification($task, Auth::user()));
            }
        }

        return redirect()->route('academic-staff.tasks.index')->with('success', 'Task status updated.');
    }

    
    public function uploadDocument(Request $request, Task $task)
    {
        $request->validate([
            'staff_document' => 'required|file|mimes:pdf,doc,docx,xlsx,ppt,pptx,jpg,jpeg,png|max:20480', // max dalam kilobytes (20MB)
        ]);

        // Optional: check if user is assigned to this task
        if (!$task->users->contains(Auth::id())) {
            abort(403, 'You are not authorized to upload for this task.');
        }

        $file = $request->file('staff_document');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents/staff', $filename, 'public');

        $task->update([
            'staff_document' => $path,
            'staff_original_name' => $file->getClientOriginalName(),
        ]);

        return redirect()->route('academic-staff.tasks.show', $task)->with('success', 'Your document was uploaded successfully.');
    }

    // ---------------- Groups ----------------

    public function viewGroups()
    {
        $userId = Auth::id();

        $groups = Group::whereHas('users', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->withCount('users')
            ->get();

        return view('academic_staff.groups.index', compact('groups'));
    }

    public function showGroup($id)
    {
        $userId = Auth::id();

        $group = Group::whereHas('users', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with(['users', 'creator', 'tasks']) // if you want to show tasks as well
            ->findOrFail($id);

        return view('academic_staff.groups.show', compact('group'));
    }

    public function activities()
    {
        $userId = Auth::id();

        $tasks = Task::whereHas('users', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orWhereHas('group.users', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with(['users', 'documents.user']) // penting!
            ->get();

        return view('academic_staff.tasks.activities', compact('tasks'));
    }

    public function showDocuments($taskId)
    {
        $userId = Auth::id();

        $task = Task::with(['documents.user']) // eager-load uploader info
            ->where(function ($q) use ($userId) {
                $q->whereHas('users', fn($q) => $q->where('user_id', $userId))
                ->orWhereHas('group.users', fn($q) => $q->where('user_id', $userId));
            })
            ->findOrFail($taskId);

        return view('academic_staff.documents.show', compact('task'));
    }


}
