@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Roles</h2>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary mb-3">Add Role</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ ucfirst($role->name) }}</td>
                <td>
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
