<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'staff_id',
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // A user can belong to many groups (many-to-many)
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id');
    }

        public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

}
