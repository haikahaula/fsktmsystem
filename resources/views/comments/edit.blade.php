@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 shadow rounded">
    <h2 class="text-xl font-bold mb-4">Edit Comment</h2>

    <form action="{{ route('comments.update', $comment) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="content" class="block font-semibold">Comment</label>
            <textarea name="content" id="content" rows="4" class="w-full border px-3 py-2 rounded" required>{{ old('content', $comment->content) }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ url()->previous() }}" class="ml-3 text-gray-600 underline">Cancel</a>
    </form>
</div>
@endsection
