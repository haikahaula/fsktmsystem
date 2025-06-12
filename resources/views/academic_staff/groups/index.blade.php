@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">My Groups</h2>

    <table class="w-full table-auto border border-gray-300">
        <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">No.</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Group Name</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Description</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Team Members</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($groups as $index => $group)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $group->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $group->description }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $group->users_count }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('academic-staff.groups.show', $group->id) }}" 
                                class="inline-block px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No groups assigned.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
