<?php

namespace App\Console;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskDueReminder;
use App\Notifications\TaskDueReminderNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected $commands = [
        \App\Console\Commands\SendDueDateReminder::class, // add your custom command here
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $dates = [2, 1, 0]; // in 2 days, 1 day, today

            foreach ($dates as $days) {
                $targetDate = now()->addDays($days)->toDateString();

                $tasks = Task::with('users')
                    ->whereDate('due_date', $targetDate)
                    ->where('status', '!=', 'finished')
                    ->get();

                foreach ($tasks as $task) {
                    foreach ($task->users as $user) {
                        $user->notify(new TaskDueReminderNotification($task, $days));
                    }
                }
            }
        })->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
