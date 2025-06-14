@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-3xl font-bold mb-6">{{ $task->title }}</h2>

    <div class="mb-4">
        <strong>Description:</strong>
        <p class="mt-1 whitespace-pre-line">{{ $task->description ?? 'No description provided.' }}</p>
    </div>

    <div class="mb-4">
        <strong>Due Date:</strong>
        <p>{{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}</p>
    </div>

    <p><strong>Status:</strong> {{ ucfirst($task->status) }}</p>

    <div class="mb-4">
        <strong>Assigned To:</strong>
        <div class="mt-1">
            @if ($task->users && $task->users->count())
                @foreach ($task->users as $user)
                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1">
                        {{ $user->name }} ({{ $user->email }})
                    </span>
                @endforeach
            @elseif ($task->assignedGroup)
                <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                    {{ $task->assignedGroup->name }}
                </span>
            @else
                <span class="text-gray-500 text-sm">Unassigned</span>
            @endif
        </div>
    </div>

    {{-- Documents Section --}}
    @if($task->documents && $task->documents->isNotEmpty())
        <div class="mb-4">
            <strong>Documents:</strong>
            <ul class="list-disc ml-5">
                @foreach ($task->documents as $document)
                    <li class="mb-1">
                        <a href="{{ route('documents.download', $document->id) }}" class="text-blue-600 underline">
                            {{ $document->original_name }}
                        </a>
                        <span class="text-sm text-gray-500">
                            uploaded by {{ $document->user->name ?? 'Unknown' }} on {{ $document->created_at->format('d M Y') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <p class="text-gray-500 italic">No documents uploaded.</p>
    @endif

    {{-- Comments Section --}}
    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-4">Comments</h3>

        @if ($task->comments->isEmpty())
            <p class="text-gray-500">No comments yet.</p>
        @else
            <ul class="space-y-4">
                @foreach ($task->comments as $comment)
                    <li class="p-4 bg-gray-100 rounded shadow">
                        <div class="flex justify-between items-center">
                            <strong>{{ $comment->user->name }}</strong>
                            <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-2 text-gray-700 whitespace-pre-line">{{ $comment->content }}</p>

                        @if (auth()->id() === $comment->user_id)
                            <div class="mt-2 space-x-2">
                                <a href="{{ route('academic-head.comments.edit', $comment->id) }}"
                                   class="text-sm text-blue-600 hover:underline">Edit</a>

                                <form action="{{ route('academic-head.comments.destroy', $comment->id) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Add New Comment Form --}}
    <div class="mt-6">
        @include('comments._form', ['task' => $task, 'baseRoute' => 'academic-head'])
    </div>

    <div class="mt-8">
        <a href="{{ route('academic-head.tasks.index') }}"
           class="text-blue-600 underline hover:text-blue-800">
            ← Back to Tasks
        </a>
    </div>
</div>
@endsection
