@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Edit Task</h2>

    <form action="{{ route('academic-staff.tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Status -->
        <div class="mb-4">
            <label for="status" class="block font-medium text-gray-700">Task Status</label>
            <select name="status" id="status" class="w-full border rounded p-2 mt-1 bg-white dark:bg-gray-900">
                <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in progress" {{ $task->status === 'in progress' ? 'selected' : '' }}>In Progress</option>
                <option value="finished" {{ $task->status === 'finished' ? 'selected' : '' }}>Finished</option>
            </select>
        </div>

        <!-- Optional: Staff Upload (with current document display) -->
        {{-- <div class="mb-4">
            <label for="document" class="block font-semibold mb-1">Documents</label>
            <input type="file" name="document" id="document" accept=".pdf,.doc,.docx,.txt,.pptx"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">

            @if ($task->staff_document)
                <p class="mt-2 text-sm text-gray-600">
                    Current: 
                    <a href="{{ asset('storage/' . $task->staff_document) }}" class="text-blue-600 underline" target="_blank">
                        {{ $task->staff_original_name }}
                    </a>
                </p>
            @endif
        </div> --}}

        <!-- Submit -->
        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
