@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Comment</h2>

    <form method="POST" action="{{ route('academic-head.comments.update', $comment->id) }}">
        @csrf
        @method('PUT')

        <textarea name="content" rows="5" class="w-full border rounded p-2">{{ old('content', $comment->content) }}</textarea>
        @error('content')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror

        <div class="mt-4 flex justify-between">
            <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
        </div>

        <input type="hidden" name="redirect_url" value="{{ url()->previous() }}">
    </form>
</div>
@endsection
