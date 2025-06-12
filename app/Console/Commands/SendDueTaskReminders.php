<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskDueReminder;
use Carbon\Carbon;

class SendDueTaskReminders extends Command
{
    protected $signature = 'tasks:due-reminders';
    protected $description = 'Send reminders to academic staff about tasks due in 2 days';

    public function handle()
    {
        $dueDate = Carbon::now()->addDays(2)->toDateString();

        $tasks = Task::whereDate('due_date', $dueDate)->get();

        foreach ($tasks as $task) {
            $user = User::find($task->assigned_to_id);
            if ($user) {
                $user->notify(new TaskDueReminder($task));
            }
        }

        $this->info('Reminders sent for tasks due in 2 days.');
    }
}
