@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">My Tasks</h1>

    <table class="w-full table-auto border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border">No.</th>
                <th class="px-4 py-2 border">Assigned By</th>
                <th class="px-4 py-2 border">Title</th>
                <th class="px-4 py-2 border">Description</th>
                <th class="px-4 py-2 border">Due Date</th>
                <th class="px-4 py-2 border">Status</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $index => $task)
                <tr>
                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 border">{{ $task->createdBy->name ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $task->title }}</td>
                    <td class="px-4 py-2 border">{{ Str::limit($task->description, 50) }}</td>
                    <td class="px-4 py-2 border">{{ $task->due_date }}</td>
                    <td class="px-4 py-2 border">{{ ucfirst($task->status) }}</td>
                    <td class="px-4 py-2 border space-x-2">
                        <button
                            onclick="document.getElementById('modal-{{ $task->id }}').classList.remove('hidden')"
                            class="text-blue-600 underline"
                        >
                            View
                        </button>
                        <a href="{{ route('academic-staff.tasks.edit', $task->id) }}" class="text-green-600 underline">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-4 text-center text-gray-500">No tasks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

        <!-- Place ALL modals after the table -->
        @foreach ($tasks as $task)
            <div id="modal-{{ $task->id }}" class="fixed inset-0 bg-gray-800 bg-opacity-50 items-center justify-center z-50 hidden flex">
                <div class="bg-white rounded shadow-lg max-w-lg w-full relative max-h-[90vh] flex flex-col">
                    <div class="p-6 overflow-y-auto">
                        <button onclick="document.getElementById('modal-{{ $task->id }}').classList.add('hidden')" class="absolute top-2 right-4 text-gray-600 text-xl font-bold">&times;</button>

                        <h2 class="text-xl font-semibold mb-4">Task Details</h2>
                        <p><strong>Title:</strong> {{ $task->title }}</p>
                        <p><strong>Description:</strong> {{ $task->description }}</p>
                        <p><strong>Assigned By:</strong> {{ $task->createdBy->name ?? '-' }}</p>
                        <p><strong>Due Date:</strong> {{ $task->due_date }}</p>

                        {{-- <p class="mt-2"><strong>Staff Upload:</strong>
                            @if ($task->staff_document)
                                <a href="{{ asset('storage/' . $task->staff_document) }}" target="_blank" class="text-blue-500 underline">
                                    {{ $task->staff_original_name }}
                                </a>
                            @else
                                No Staff Upload
                            @endif
                        </p> --}}
                        @if($task->document)
                            <div class="mb-4">
                                <strong>Document:</strong>
                                <p>
                                    <a href="{{ route('academic-head.tasks.download', $task->id) }}"
                                    class="text-blue-600 underline hover:text-blue-800">
                                    Download {{ $task->original_name ?? 'Document' }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        <div class="mt-4">
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

                            <form action="{{ route('academic-staff.comments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                <textarea name="content" class="w-full border p-2 rounded mb-2" rows="2" placeholder="Add a comment..." required></textarea>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">Submit Comment</button>
                            </form>
                        </div>
                    </div> 
                </div>
            </div>
        @endforeach        
</div>
@endsection
