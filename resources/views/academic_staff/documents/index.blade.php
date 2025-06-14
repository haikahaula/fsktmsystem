@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Documents for Task: {{ $task->title }}</h2>

    @if ($documents->isEmpty())
        <p>No documents uploaded for this task.</p>
    @else
        <ul class="list-disc pl-6">
            @foreach ($documents as $document)
                <li class="mb-2">
                    <a href="{{ route('documents.download', $document->id) }}" class="text-blue-600 underline">
                        {{ $document->original_name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('academic-staff.tasks.index') }}" class="inline-block mt-4 text-gray-600 underline">← Back to Task List</a>
</div>
@endsection
