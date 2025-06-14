@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">My Task Activities</h1>

    <table class="w-full table-auto border-collapse bg-white rounded shadow">
        <thead class="bg-gray-200 text-left">
            <tr>
                <th class="px-4 py-2 border">No.</th>
                <th class="px-4 py-2 border">Title</th>
                <th class="px-4 py-2 border">Due Date</th>
                <th class="px-4 py-2 border">Status</th>
                <th class="px-4 py-2 border">Documents</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $index => $task)
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 border">{{ $task->title }}</td>
                    <td class="px-4 py-2 border">{{ $task->due_date }}</td>
                    <td class="px-4 py-2 border">{{ $task->status }}</td>
                    <td class="px-4 py-2 border">
                        @if ($task->documents && $task->documents->isNotEmpty())
                            <ul class="list-disc ml-4">
                                @foreach ($task->documents as $document)
                                    <li>
                                        <a href="{{ route('documents.download', $document->id) }}" class="text-blue-600 underline">
                                            {{ $document->original_name }}
                                        </a>
                                        <small class="text-gray-500 block">
                                            Uploaded by {{ $document->user->name ?? 'Unknown' }} on {{ $document->created_at->format('d M Y') }}
                                        </small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-500 italic">No documents</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 border space-x-2">
                        <a href="{{ route('academic-staff.tasks.documents', $task->id) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">                            
                            View Documents
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
