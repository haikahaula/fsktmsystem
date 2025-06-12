@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Role</h2>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name">Role Name</label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
