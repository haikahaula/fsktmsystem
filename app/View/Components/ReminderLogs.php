<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\TaskReminderLog;

class ReminderLogs extends Component
{
    public $logs;

    public function __construct()
    {
        // Fetch logs with task and user, paginate 10 per page
        $this->logs = TaskReminderLog::with(['task', 'user'])
            ->orderBy('reminder_sent_at', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        $logs = TaskReminderLog::with(['task', 'user'])
                    ->latest('reminder_sent_at')
                    ->paginate(10);

        return view('components.reminder-logs', [
            'reminderLogs' => $logs
        ]);
    }
}
