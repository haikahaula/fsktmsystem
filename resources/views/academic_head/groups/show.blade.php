@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-semibold mb-6">Group Details</h2>

        <div class="mb-4">
            <strong class="block text-gray-700">Group:</strong>
            <p class="text-gray-900">{{ $group->name }}</p>
        </div>

        <div class="mb-4">
            <strong class="block text-gray-700">Team Members:</strong>
            <div class="mt-1 space-y-1">
                @forelse ($group->users as $user)
                    <p class="text-gray-900">- {{ $user->name }}</p>
                @empty
                    <p class="text-gray-500 italic">No members assigned.</p>
                @endforelse
            </div>
        </div>

        <div class="mb-6">
            <strong class="block text-gray-700">Description:</strong>
            <p class="text-gray-900">{{ $group->description }}</p>
        </div>

        <a href="{{ route('academic-head.groups.index') }}"
           class="inline-block bg-gray-600 text-black px-4 py-2 rounded hover:bg-blue-700 transition">
            ← Back to Groups
        </a>
    </div>
@endsection
