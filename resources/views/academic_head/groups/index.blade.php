@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Groups</h2>
            <a href="{{ route('academic-head.groups.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                + Create Group
            </a>
        </div>

        <table class="w-full table-auto border-collapse bg-white rounded shadow">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="px-4 py-2 border">No.</th>
                    <th class="px-4 py-2 border">Group Name</th>
                    <th class="px-4 py-2 border">Team Members</th>
                    <th class="px-4 py-2 border">Description</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $index => $group)
                    <tr class="border-t hover:bg-gray-100">
                        <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">{{ $group->name }}</td>
                        <td class="px-4 py-2 border">
                            @foreach($group->users as $user)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1">
                                    {{ $user->name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-4 py-2 border">{{ $group->description }}</td>
                        <td class="px-4 py-2 border space-x-2">
                            <a href="{{ route('academic-head.groups.show', $group->id) }}"
                               class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('academic-head.groups.edit', $group->id) }}"
                               class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('academic-head.groups.destroy', $group->id) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:underline"
                                        onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
