@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">{{ isset($group) ? 'Update Group' : 'Create Group' }}</h1>

    <form method="POST" action="{{ isset($group) ? route('academic-head.groups.update', $group->id) : route('academic-head.groups.store') }}">
        @csrf
        @if(isset($group))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label for="name" class="block font-semibold">Name:</label>
            <input type="text" name="name" class="w-full border p-2 rounded" value="{{ old('name', $group->name ?? '') }}" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block font-semibold">Description:</label>
            <textarea name="description" class="w-full border p-2 rounded" rows="4">{{ old('description', $group->description ?? '') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2">Team Members:</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach($users as $user)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="users[]" value="{{ $user->id }}"
                            {{ isset($group) && $group->users->contains($user->id) ? 'checked' : '' }}>
                        <span>{{ $user->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div style="margin-top: 50px;">
            <button type="submit" style="background-color: rgb(11, 91, 195); color: white; padding: 10px;">
                {{ isset($group) ? 'Update Group' : 'Create Group' }}
            </button>  
        </div>
    </form>
@endsection
