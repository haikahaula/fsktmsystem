<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'description', 'document'];

    // Many-to-Many relationship with users
        public function users()
        {
            return $this->belongsToMany(User::class);
        }

        public function comments()
        {
            return $this->hasMany(Comment::class);
        }
        
        public function creator()
        {
            return $this->belongsTo(User::class, 'created_by');
        }

        public function tasks()
        {
            return $this->hasMany(Task::class);
        }

}
