@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Role</h2>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name">Role Name</label>
            <input type="text" name="name" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-success">Create</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
