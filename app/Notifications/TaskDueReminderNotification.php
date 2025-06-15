<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskDueReminderNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $daysLeft;

    public function __construct(Task $task, $daysLeft)
    {
        $this->task = $task;
        $this->daysLeft = $daysLeft;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $title = 'Task Reminder';

        $message = match ($this->daysLeft) {
            2 => "Task \"{$this->task->title}\" is due in 2 days.",
            1 => "Task \"{$this->task->title}\" is due tomorrow.",
            0 => "Task \"{$this->task->title}\" is due today!",
            default => "Task \"{$this->task->title}\" is nearing its deadline.",
        };

        return [
            'title' => $title,
            'message' => $message,
            'task_id' => $this->task->id,
        ];
    }
}
