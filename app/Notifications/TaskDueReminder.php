<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskDueReminder extends Notification
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database']; // Only in-system
    }

    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'due_date' => $this->task->due_date,
            'message' => "Reminder: '{$this->task->title}' is due on {$this->task->due_date}",
        ];
    }
}