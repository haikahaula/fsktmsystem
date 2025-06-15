<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;

class DocumentController extends Controller
{
    public function index(Task $task)
    {
        $documents = $task->documents()->with('user')->get();
        return view('academic_staff.documents.index', compact('task', 'documents'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'document' => 'required|file|mimes:pdf,doc,docx,xlsx,ppt,pptx,jpg,jpeg,png|max:20480', // max dalam kilobytes (20MB)
        ]);

        $file = $request->file('document');
        $path = $file->store('documents', 'public');
        $originalName = $file->getClientOriginalName();

        // Debug sementara
        logger("Uploading file: $originalName at $path");

        // SAVE TO DB
        $doc = Document::create([
            'task_id' => $request->task_id,
            'user_id' => Auth::id(),
            'filename' => $path,
            'original_name' => $originalName,
        ]);

        logger("Document ID saved: " . $doc->id); // Tambah ni untuk confirm save

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function show($task_id)
    {
        $task = Task::with('documents.user')->findOrFail($task_id);
        return view('academic_head.documents.show', compact('task'));
    }

    public function download($id)
    {
        $document = Document::with('task.users', 'task.createdBy')->findOrFail($id);
        $user = Auth::user();

        $isUploader = $document->user_id === $user->id;
        $isTaskMember = $document->task && $document->task->users->contains($user->id);
        $isTaskCreator = $document->task && $document->task->created_by === $user->id;

        // Benarkan jika pengguna ialah uploader, ahli task, atau pencipta task
        if (!($isUploader || $isTaskMember || $isTaskCreator)) {
            abort(403, 'Unauthorized access to this document.');
        }

        if (!Storage::disk('public')->exists($document->filename)) {
            abort(404, 'Document not found.');
        }

        return response()->download(
            storage_path('app/public/' . $document->filename),
            $document->original_name
        );
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