<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    use Queueable;

    public $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database']; // Importante: I-save sa database
    }

    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title'   => 'New Task Assigned',
            'message' => 'You have been assigned to: ' . $this->task->title,
            'url'     => route('tasks.index'),
        ];
    }
}