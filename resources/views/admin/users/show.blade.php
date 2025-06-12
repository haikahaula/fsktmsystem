@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-lg font-bold mb-4">User Details</h2>

    <div class="mb-4">
        <strong>Staff ID:</strong> {{ $user->staff_id }}
    </div>
    <div class="mb-4">
        <strong>Email:</strong> {{ $user->email }}
    </div>
    <div class="mb-4">
        <strong>Name:</strong> {{ $user->name }}
    </div>
    <div class="mb-4">
        <strong>Role:</strong> {{ $user->role->name ?? '-' }}
    </div>

    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Back to List</a>
</div>
@endsection
