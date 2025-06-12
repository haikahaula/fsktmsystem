<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function create(Task $task)
    {
        return view('comments.create', compact('task'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment();
        $comment->task_id = $request->task_id;
        $comment->user_id = Auth::id();
        $comment->content = $request->content;
        $comment->save();


        // Redirect to the passed redirect URL or fallback
        return redirect($request->redirect_url ?? '/dashboard')->with('success', 'Comment added successfully.');
    }
    
    public function edit(Comment $comment)
    {
        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment); // Optional: use policy

        $request->validate(['content' => 'required|string']);
        $comment->update(['content' => $request->content]);

        $prefix = request()->segment(1);     
        return redirect()->route("$prefix.tasks.show", $comment->task_id)
                        ->with('success', 'Comment updated.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted.');
    }
}
