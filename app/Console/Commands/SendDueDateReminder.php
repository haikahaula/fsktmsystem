<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Illuminate\Support\Carbon;
use App\Notifications\TaskDueReminderNotification;

class SendDueDateReminder extends Command
{
    protected $signature = 'reminder:duedate';
    protected $description = 'Send reminder notifications for tasks nearing their due date';

    public function handle()
    {
        $today = now()->startOfDay();

        $reminderDays = [2, 1, 0]; // 2 days before, 1 day before, today

        foreach ($reminderDays as $daysBefore) {
            $date = $today->copy()->addDays($daysBefore);
            $tasks = Task::with('users')->whereDate('due_date', $date)->get();

            foreach ($tasks as $task) {
                foreach ($task->users as $user) {
                    $user->notify(new TaskDueReminderNotification($task, $daysBefore));
                }
            }
        }

        $this->info('Due date reminders sent successfully.');
    }
}
