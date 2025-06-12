@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4">
    <h2 class="text-2xl font-semibold mb-4">List of Tasks</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('academic-head.tasks.create') }}" class="inline-block mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        + Create New Task
    </a>

    <table class="w-full table-auto border-collapse bg-white rounded shadow">
        <thead class="bg-gray-200 text-left">
            <tr>
                <th class="px-4 py-2 border">No.</th>
                <th class="px-4 py-2 border">Title</th>
                <th class="px-4 py-2 border">Assigned To</th>
                <th class="px-4 py-2 border">Due Date</th>
                <th class="px-4 py-2 border">Status</th> <!-- Added Status header -->
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $index => $task)
                <tr class="border-t hover:bg-gray-100">
                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 border">{{ $task->title }}</td>
                    <td class="px-4 py-2 border">
                        @if ($task->users && $task->users->count())
                            @foreach ($task->users as $user)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1">
                                    {{ $user->name }}
                                </span>
                            @endforeach
                        @elseif ($task->assignedGroup)
                            <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                {{ $task->assignedGroup->name }}
                            </span>
                        @else
                            <span class="text-gray-500 text-sm">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}</td>
                    <td class="px-4 py-2 border">
                        {{ ucfirst($task->status) }}  <!-- Display status as text -->
                    </td>
                    <td class="px-4 py-2 border space-x-3 whitespace-nowrap">
                        <a href="{{ route('academic-head.tasks.show', $task->id) }}" class="text-blue-600 underline hover:text-blue-800">View</a>
                        <a href="{{ route('academic-head.tasks.edit', $task->id) }}" class="text-yellow-600 underline hover:text-yellow-800">Edit</a>
                        <form action="{{ route('academic-head.tasks.destroy', $task->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600 underline hover:text-red-800">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">No tasks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
