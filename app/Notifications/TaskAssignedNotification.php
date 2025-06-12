<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'title' => 'New Task Assigned',
            'message' => "You have been assigned a task: {$this->task->title}",
            'task_id' => $this->task->id,
            'assigned_by' => $this->task->creator->name ?? 'System',
        ]);
    }
}
