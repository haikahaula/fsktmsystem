@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <a href="{{ route('academic-staff.tasks.show', $task->id) }}" class="text-blue-600 underline">← Back to Task</a>

    <h2 class="text-xl font-bold mb-4">Upload Document for Task: {{ $task->title }}</h2>

    @if(session('success'))
        <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('documents.store', $task->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700">Choose a file</label>
            <input type="file" name="document" required class="mt-2 p-2 border w-full">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Upload</button>
    </form>
</div>
@endsection
