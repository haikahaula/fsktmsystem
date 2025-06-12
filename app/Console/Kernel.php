<?php

namespace App\Console;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskDueReminder;
use Illuminate\Support\Facades\Notification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected $commands = [
        \App\Console\Commands\SendDueTaskReminders::class, // add your custom command here
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $tasks = Task::whereDate('due_date', now()->addDays(2))
                        ->where('status', '!=', 'finished')
                        ->get();

            foreach ($tasks as $task) {
                $user = User::find($task->assigned_to);
                if ($user) {
                    $user->notify(new TaskDueReminder($task));
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
