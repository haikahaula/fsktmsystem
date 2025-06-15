<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Group;
use App\Models\Comment;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'group_id',
        'due_date',
        'status',
        'assigned_to_id',
        'assigned_to_type',
    ];

    // Cast JSON array field
    protected $casts = [
        'staff_documents' => 'array',
    ];

    // Many users assigned to this task
    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }


}
