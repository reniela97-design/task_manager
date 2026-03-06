<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Role;
use App\Models\Task;

class User extends Authenticatable
{
    // Wala na ang 'HasApiTokens' para dili mag-error
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'user_log_datetime',
        'user_inactive',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'user_log_datetime' => 'datetime',
    ];

    /**
     * Relationship ngadto sa Role model.
     */
    public function role() 
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relationship ngadto sa Task model.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}