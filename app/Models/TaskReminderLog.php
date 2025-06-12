<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class TaskReminderLog extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'reminder_sent_at',
        'task_status',
    ];

    // Ensure reminder_sent_at is treated as a Carbon date
    protected $casts = [
        'reminder_sent_at' => 'datetime',
    ];

    /**
     * The task related to this reminder.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * The user (academic staff) who was reminded.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
