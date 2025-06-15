@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Edit Task</h1>

    <form action="{{ route('academic-head.tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
            <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
            <textarea name="description" id="description" rows="4" class="w-full border border-gray-300 p-2 rounded">{{ old('description', $task->description) }}</textarea>
        </div>

        {{-- Assign to Users --}}
         <div class="mb-4">
            <label class="block font-semibold mb-1">Assign to Users</label>
            <select name="assigned_user_id[]" class="selectpicker w-full" multiple data-live-search="true" title="Select users">
                @foreach ($users->where('role_id', '!=', 1) as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        {{-- Group Assignment --}}
        <div class="mb-6">
            <label class="block font-semibold mb-1">Assign to Group</label>
            <select name="group_id" class="selectpicker w-full" data-live-search="true" title="-- Select Group --">
                <option value="">-- Select Group --</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Due Date --}}
        <div class="mb-4">
            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date:</label>
            <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date) }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div class="mb-4">
            <label for="documents" class="block text-sm font-medium text-gray-700">Upload Documents:</label>
            <input type="file" name="documents[]" id="documents" multiple accept=".pdf,.doc,.docx,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" 
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            <p class="text-sm text-gray-500 mt-1">You can upload multiple files.</p>
        </div>

        {{-- Existing Documents --}}
        @if ($task->documents && $task->documents->count())
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Existing Documents:</label>
                <ul class="list-disc list-inside text-sm text-blue-600">
                    @foreach ($task->documents as $document)
                        <li>
                            <a href="{{ asset('storage/' . $document->filename) }}" target="_blank" class="hover:underline">
                                {{ $document->original_name }}
                            </a>
                            <span class="text-gray-500">— uploaded by {{ $document->user->name ?? 'Unknown' }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        {{-- Submit --}}
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Update Task
        </button>
    </form>

    {{-- Auto toggle script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userSelect = document.querySelector('#assigned_user_id');
            const groupSelect = document.querySelector('#group_id');

            function toggleDisable() {
                const userSelected = userSelect.selectedOptions.length > 0;
                const groupSelected = groupSelect.value !== "";

                groupSelect.disabled = userSelected;
                userSelect.disabled = groupSelected;
            }

            userSelect.addEventListener('change', toggleDisable);
            groupSelect.addEventListener('change', toggleDisable);

            toggleDisable(); // initial state
        });
    </script>
</div>
@endsection
