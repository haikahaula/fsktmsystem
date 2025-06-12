@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-lg font-bold mb-4">Add User</h2>

    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Staff ID</label>
            <input name="staff_id" class="w-full border px-3 py-2" value="{{ old('staff_id') }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border px-3 py-2" value="{{ old('email') }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input name="name" class="w-full border px-3 py-2" value="{{ old('name') }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Role</label>
            <select name="role_id" class="w-full border px-3 py-2">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Password</label>
            <input type="password" name="password" class="w-full border px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border px-3 py-2">
        </div>

        <div class="flex items-center space-x-4">
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create</button>
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:underline">Back to List</a>
        </div>
    </form>
</div>
@endsection
