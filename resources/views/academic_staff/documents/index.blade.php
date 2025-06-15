@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-6">Documents for Task: {{ $task->title }}</h2>

    {{-- Success message --}}
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error messages --}}
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Upload Form --}}
    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="mb-8">
        @csrf
        <input type="hidden" name="task_id" value="{{ $task->id }}">

        <div class="mb-4">
            <label for="document" class="block font-medium">
                Upload Document (pdf,doc,docx,xlsx,ppt,pptx,jpg,jpeg,png - max 20MB)
            </label>
            <input type="file" name="document" id="document" class="mt-2">
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Upload
        </button>
    </form>

    {{-- Document List --}}
    @if ($documents->isEmpty())
        <p class="text-gray-600">No documents uploaded for this task.</p>
    @else
        <h3 class="text-lg font-semibold mb-2">Uploaded Documents:</h3>
        <ul class="space-y-3">
            @foreach ($documents as $document)
                <li class="bg-gray-50 p-3 rounded flex justify-between items-center">
                    <div>
                        <a href="{{ route('documents.download', $document->id) }}" class="text-blue-700 underline">
                            {{ $document->original_name }}
                        </a>
                        <span class="text-sm text-gray-500 ml-2">
                            — uploaded by {{ $document->user->name ?? 'N/A' }} on {{ $document->created_at->format('d M Y, h:i A') }}
                        </span>
                    </div>

                    {{-- Show delete button only to uploader --}}
                    @if (auth()->id() === $document->user_id)
                        <form action="{{ route('documents.destroy', $document->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this file?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm ml-4">
                                Delete
                            </button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    {{-- Back link --}}
    <a href="{{ route('academic-staff.tasks.activities') }}" class="inline-block mt-6 text-gray-600 hover:underline">
        ← Back to Task List
    </a>
</div>
@endsection
