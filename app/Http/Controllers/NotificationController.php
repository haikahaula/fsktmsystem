<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function viewTask($task_id, $notification_id)
    {
        $user = Auth::user();

        // Mark notification as read
        $notification = DatabaseNotification::find($notification_id);
        if ($notification && $notification->notifiable_id == $user->id) {
            $notification->markAsRead();
        }

        // Redirect based on user role
        if ($user->role_id === 2) {
            return redirect()->route('academic-head.tasks.show', $task_id);
        } elseif ($user->role_id === 3) {
            return redirect()->route('academic-staff.tasks.show', $task_id);
        }

        return redirect()->route('dashboard')->with('error', 'Unauthorized access');
    }
}
