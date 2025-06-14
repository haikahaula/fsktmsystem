@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Documents for Task: {{ $task->title }}</h2>

    @if($task->documents->isEmpty())
        <p class="text-gray-500">No documents uploaded yet.</p>
    @else
        <ul class="list-disc ml-6">
            @foreach ($task->documents as $document)
                <li class="mb-2">
                    <a href="{{ route('documents.download', $document->id) }}" class="text-blue-600 underline">
                        {{ $document->original_name }}
                    </a>
                    <div class="text-sm text-gray-500">
                        Uploaded by {{ $document->user->name ?? 'Unknown' }} 
                        on {{ $document->created_at->format('d M Y, h:i A') }}
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
