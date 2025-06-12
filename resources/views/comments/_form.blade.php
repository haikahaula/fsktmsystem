<form method="POST" action="{{ route($baseRoute . '.comments.store') }}">
    @csrf
    <input type="hidden" name="task_id" value="{{ $task->id }}">

    <div class="mb-2">
        <textarea name="content" class="w-full border p-2 rounded" rows="3" placeholder="Add a comment..." required></textarea>
    </div>

    @if (session('success'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

    <button type="submit"
            class="mt-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
        Submit Comment
    </button>
</form>
