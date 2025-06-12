@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-semibold mb-4">Group Details</h2>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="min-w-full table-auto">
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr class="bg-gray-50 dark:bg-gray-700">
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Group Name</th>
                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $group->name }}</td>
                </tr>
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Description</th>
                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $group->description }}</td>
                </tr>
                <tr class="bg-gray-50 dark:bg-gray-700">
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Created By</th>
                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $group->creator->name ?? 'Unknown' }}</td>
                </tr>
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Team Members</th>
                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($group->users as $user)
                                <li>{{ $user->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="{{ route('academic-staff.groups.index') }}" class="btn btn-secondary mt-3">Back to My Groups</a>
    </div>
</div>
@endsection
