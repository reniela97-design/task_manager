<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Type extends Model
{
    protected $primaryKey = 'type_id'; // Custom PK
    public $timestamps = false;

    protected $fillable = [
        'type_name',
        'type_user_id',
        'type_log_datetime',
        'type_inactive'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'type_user_id');
    }
}