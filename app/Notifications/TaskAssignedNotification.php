<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database']; // store in DB
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Task Assigned',
            'message' => 'You have been assigned to the task: ' . $this->task->title,
            'task_id' => $this->task->id,
        ];
    }
}
