@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-4xl mx-auto">
    <a href="{{ route('academic-staff.tasks.index') }}" class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mt-4">
        ← Back to Task List
    </a>

    <h2 class="text-xl font-bold mb-4">Task Details</h2>

    <p><strong>Title:</strong> {{ $task->title }}</p>
    <p><strong>Description:</strong> {{ $task->description }}</p>
    <p><strong>Assigned By:</strong> {{ $task->createdBy->name ?? '-' }}</p>
    <p><strong>Due Date:</strong> {{ $task->due_date }}</p>

    {{-- Documents Section --}}
    {{-- @if ($task->documents && $task->documents->isNotEmpty())
        @foreach($task->documents as $document)
            <div class="mb-2">
                <a href="{{ route('documents.download', $document->id) }}" class="text-blue-600 underline">
                    {{ $document->original_name }}
                </a>
                <small class="block text-gray-500">
                    Uploaded by {{ $document->user->name ?? 'Unknown' }} on {{ $document->created_at->format('d M Y') }}
                </small>

                @if(auth()->id() === $document->user_id || auth()->user()->role === 'Admin')
                    <form action="{{ route('documents.destroy', $document->id) }}" method="POST" class="inline ml-2"
                        onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline">Delete</button>
                    </form>
                @endif
            </div>
        @endforeach
    @else
        <p class="text-gray-500 italic">No documents uploaded.</p>
    @endif --}}

    {{-- Status --}}
    <p><strong>Status:</strong> {{ ucfirst($task->status) }}</p>

    <!-- Show upload form only if user is assigned to the task -->
    {{-- @if ($task->users->contains(Auth::id()))
        <hr class="my-4">

        <h3 class="text-lg font-semibold mb-2">Upload Document</h3>
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <input type="hidden" name="task_id" value="{{ $task->id }}">
            <div class="mb-3">
                <label for="document" class="block text-sm font-medium text-gray-700 mb-1">Choose a document</label>
                <input type="file" name="document" id="document" class="w-full border border-gray-300 rounded p-2" required>
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Upload
            </button>
        </form>
    @endif --}}

    <hr class="my-4">

    <h3 class="text-lg font-semibold">Comments</h3>

    @forelse ($task->comments as $comment)
        <div class="border rounded p-3 mb-2">
            <strong>{{ $comment->user->name }}:</strong>
            <p>{{ $comment->content }}</p>
            <small class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</small>

            @if (Auth::id() === $comment->user_id)
                <div class="mt-2">
                    <a href="{{ route('academic-staff.comments.edit', $comment) }}" class="text-blue-600 text-sm mr-2">Edit</a>
                    <form action="{{ route('academic-staff.comments.destroy', $comment) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 text-sm" onclick="return confirm('Delete this comment?')">Delete</button>
                    </form>
                </div>
            @endif
        </div>
    @empty
        <p>No comments yet.</p>
    @endforelse

    @include('comments._form', ['task' => $task, 'baseRoute' => 'academic-staff'])
</div>
@endsection
