<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
   public function store(Request $request)
{
    $request->validate([
        'task_id' => 'required|exists:tasks,id',
        'document' => 'required|file|mimes:pdf,doc,docx,xlsx,xls|max:2048',
    ]);

    $task = Task::findOrFail($request->task_id);

    // Optional: permission check
    if (!$task->users->contains(Auth::id()) && !in_array(Auth::user()->role, ['Admin', 'Academic Head'])) {
        abort(403, 'Unauthorized');
    }

    $path = $request->file('document')->store('documents', 'public');
    $originalName = $request->file('document')->getClientOriginalName();

    $task->staff_document = $path;
    $task->staff_original_filename = $originalName;
    $task->save();

    return redirect()->back()->with('success', 'Document uploaded successfully.');
}


}
