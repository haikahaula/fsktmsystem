@extends('layouts.app')

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
            <div>
                <a href="{{ route('admin.users.create') }}"
                   class="inline-block mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Create New User
                </a>
            </div>

            <form action="{{ route('admin.users.index') }}" method="GET" class="flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name, email, staff ID"
                       class="border rounded px-3 py-2" />
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Search</button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">No.</th>
                        <th class="border px-4 py-2">Staff ID</th>
                        <th class="border px-4 py-2">Email</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Role</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr class="text-center">
                            <td class="border px-4 py-2">{{ $users->firstItem() + $index }}</td>
                            <td class="border px-4 py-2">{{ $user->staff_id }}</td>
                            <td class="border px-4 py-2">{{ $user->email }}</td>
                            <td class="border px-4 py-2">{{ $user->name }}</td>
                            <td class="border px-4 py-2">{{ $user->role->name ?? '-' }}</td>
                            <td class="border px-4 py-2 space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:underline">View</a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete this user?')" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border px-4 py-4 text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="mt-4">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

@section('header')
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Manage Users
    </h2>
@endsection
