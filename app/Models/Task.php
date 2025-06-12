<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Group;
use App\Models\Comment;

/**
 * App\Models\Task
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int|null $created_by
 * @property int|null $assigned_to_id
 * @property string|null $assigned_to_type
 * @property int|null $group_id
 * @property string $due_date
 * @property string|null $document
 * @property string $status
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 */

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'assigned_to_id',
        'assigned_to_type',
        'group_id',
        'due_date',
        'document',
        'status',
        'original_filename',
        'staff_document',
        'staff_original_filename',
    ];

    // Many users assigned to this task (pivot table task_user)
    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }

    // Assigned to a specific group
    public function assignedGroup()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    // Shortcut if you still want an alias for the same relation
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    // Who created the task
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
