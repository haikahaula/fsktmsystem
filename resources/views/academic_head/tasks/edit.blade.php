@extends('layouts.app')

@section('content')
    <h1>Edit Task</h1>

    <form action="{{ route('academic-head.tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Title:</label>
        <input type="text" name="title" value="{{ $task->title }}" required><br>

        <label>Description:</label>
        <textarea name="description">{{ $task->description }}</textarea><br>

        <label>Assign to:</label>
        <div>
            <label>Users:</label>
            <select name="assigned_user_id[]" multiple {{ $task->assigned_group_id ? 'disabled' : '' }}>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" 
                        {{ in_array($user->id, $task->users->pluck('id')->toArray()) ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Group:</label>
            <select name="group_id" {{ $task->users->count() ? 'disabled' : '' }}>
                <option value="">-- Select Group --</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ $task->group_id == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <label>Due Date:</label>
        <input type="date" name="due_date" value="{{ $task->due_date }}" required><br>

        <label>Document:</label>
        <input type="file" name="document"><br>
        @if ($task->document)
            <a href="{{ asset('storage/' . $task->document) }}" target="_blank">Current Document</a><br>
        @endif

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Update Task
        </button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userSelect = document.querySelector('select[name="assigned_user_id[]"]');
            const groupSelect = document.querySelector('select[name="group_id"]');

            function toggleDisable() {
                groupSelect.disabled = userSelect.selectedOptions.length > 0;
                userSelect.disabled = groupSelect.value !== "";
            }

            userSelect.addEventListener('change', toggleDisable);
            groupSelect.addEventListener('change', toggleDisable);
        });
    </script>
@endsection
