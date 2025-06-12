<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;


class TaskStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;
    protected $updatedBy;

    public function __construct(Task $task, User $updatedBy)
    {
        $this->task = $task;
        $this->updatedBy = $updatedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'title' => 'Task Status Updated',
            'message' => "Task '{$this->task->title}' status has been updated to '{$this->task->status}'.",
            'task_id' => $this->task->id,
            'updated_by' => $this->updatedBy->name,
        ]);
    }
}
