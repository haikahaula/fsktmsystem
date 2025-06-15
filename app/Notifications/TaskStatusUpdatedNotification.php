<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TaskStatusUpdatedNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $updatedBy;

    public function __construct(Task $task, $updatedBy)
    {
        $this->task = $task;
        $this->updatedBy = $updatedBy;
    }

    public function via($notifiable)
    {
        return ['database']; // Store in DB
    }

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'title' => 'Task Status Updated',
            'message' => "The task '{$this->task->title}' has been updated to '{$this->task->status}' by {$this->updatedBy->name}.",
            'task_id' => $this->task->id,
        ]);
    }
}
