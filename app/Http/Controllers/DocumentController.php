<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'document' => 'required|file|mimes:pdf,doc,docx,xlsx,xls|max:2048',
        ]);

        // Store file and get necessary details
        $file = $request->file('document');
        $path = $file->store('documents', 'public');
        $originalName = $file->getClientOriginalName();

        // Save to database
        Document::create([
            'task_id' => $request->task_id,
            'user_id' => Auth::id(), // assuming you've changed staff_id to user_id
            'filename' => $path,
            'original_filename' => $originalName,
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function show($task_id)
    {
        $task = Task::with('documents.user')->findOrFail($task_id);
        return view('academic_head.documents.show', compact('task'));
    }

    public function download(Document $document)
    {
        return Storage::disk('public')->download($document->filename, $document->original_name);
    }

    public function edit(Document $document)
    {
        if (Auth::id() !== $document->user_id && Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized');
        }

        return view('academic_head.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        if (Auth::id() !== $document->user_id && Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'original_name' => 'required|string|max:255'
        ]);

        $document->update([
            'original_name' => $request->original_name
        ]);

        return redirect()->route('academic-head.documents.show', $document->task_id)
            ->with('success', 'Document updated successfully.');
    }


    public function destroy(Document $document)
    {
        if (Auth::id() !== $document->user_id && Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized');
        }

        Storage::disk('public')->delete($document->filename);
        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
    
}