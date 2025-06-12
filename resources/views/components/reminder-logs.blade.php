<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Task Reminder Logs</h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Reminder Sent At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reminderLogs as $log)
                        <tr>
                            <td>{{ $log->user->name }}</td>
                            <td>
                                <a href="{{ route('tasks.show', $log->task->id) }}">
                                    {{ $log->task->title }}
                                </a>
                            </td>
                            <td>{{ ucfirst($log->task_status) }}</td>
                            <td>{{ $log->reminder_sent_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No reminders sent yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 ms-3">
            {{ $reminderLogs->links() }} {{-- Laravel pagination --}}
        </div>
    </div>
</div>
