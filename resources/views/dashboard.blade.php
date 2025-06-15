<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                @php
                    $role_id = Auth::user()->role_id;
                @endphp

                @if ($role_id == 1)
                    <p>You are logged in as <strong>Admin</strong></p>
                    <a href="{{ route('admin.users.index') }}" class="text-blue-600 underline">Manage Users</a>
                    <a href="{{ route('admin.roles.create') }}" class="text-blue-600 underline">Manage Roles</a>

                @elseif ($role_id == 2)
                    <p>You are logged in as <strong>Academic Head</strong></p>
                    <a href="{{ route('academic-head.tasks.index') }}" class="text-blue-600 underline">View All Tasks</a><br>
                    <a href="{{ route('academic-head.groups.index') }}" class="text-blue-600 underline">Manage Groups</a>
                @elseif ($role_id == 3)
                    <p>You are logged in as <strong>Academic Staff</strong></p>
                    <a href="{{ route('academic-staff.tasks.index') }}" class="text-blue-600 underline">My Tasks</a><br>
                    <a href="{{ route('academic-staff.groups.index') }}" class="text-blue-600 underline">My Groups</a>
                @else
                    <p class="text-red-600">Unknown role. Please contact the system administrator.</p>
                @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
