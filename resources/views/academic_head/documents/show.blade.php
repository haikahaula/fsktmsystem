{{-- resources/views/academic_head/documents/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="mt-6">

    <!-- Head Documents Section -->
    <h2 class="text-lg font-semibold mb-2">Head Documents</h2>
    <table class="table-auto w-full border mb-6">
        <thead class="bg-gray-200">
            <tr>
                <th class="border px-4 py-2">No.</th>
                <th class="border px-4 py-2">File</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @php
                $headDocs = $task->documents->filter(fn($doc) =>
                    $doc->user && $doc->user->role_id === 2
                );
            @endphp
            @forelse ($headDocs as $i => $doc)
                <tr>
                    <td class="border px-4 py-2">{{ $i + 1 }}</td>
                    <td class="border px-4 py-2">{{ $doc->original_name }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <a href="{{ route('academic-head.documents.download', $doc->id) }}" class="text-blue-600 underline">View</a>
                        <a href="{{ route('academic-head.documents.edit', $doc->id) }}" class="text-yellow-600 underline ml-2">Edit</a>
                        @if(auth()->id() === $doc->user_id || auth()->user()->role_id === 1)
                            <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="border px-4 py-2 italic text-gray-500">No documents uploaded by head.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Staff Documents Section -->
    <h2 class="text-lg font-semibold mb-2">Staff Documents</h2>
    <table class="table-auto w-full border">
        <thead class="bg-gray-200">
            <tr>
                <th class="border px-4 py-2">No.</th>
                <th class="border px-4 py-2">File</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @php
                $staffDocs = $task->documents->filter(fn($doc) =>
                    $doc->user && $doc->user->role_id === 3
                );
            @endphp
            @forelse ($staffDocs as $i => $doc)
                <tr>
                    <td class="border px-4 py-2">{{ $i + 1 }}</td>
                    <td class="border px-4 py-2">{{ $doc->original_name }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('academic-head.documents.download', $doc->id) }}" class="text-blue-600 underline">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="border px-4 py-2 italic text-gray-500">No documents uploaded by staff.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
