<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * Ang $fillable nagtugot sa mass assignment. 
     * Gidugang nato ang started_at ug finished_at aron ma-save sila sa database.
     */
    protected $fillable = [
        'title', 
        'description', 
        'task_date', 
        'status', 
        'priority', 
        'user_id', 
        'project_id', 
        'client_id',
        'category',
        'type',
        'started_at',  // Gi-dugang para sa Start logic
        'finished_at'  // Gi-dugang para sa Finish logic
    ];

    /**
     * Ang $casts mag-convert sa columns ngadto sa saktong PHP types.
     * Importante kini aron magamit nimo ang Carbon dates (e.g., $task->started_at->format('H:i')).
     */
    protected $casts = [
        'task_date' => 'date',
        'started_at' => 'datetime', 
        'finished_at' => 'datetime',
    ];

    // Relationships (Optional pero mapuslanon)
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}