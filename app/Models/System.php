<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class System extends Model
{
    // Define the custom primary key
    protected $primaryKey = 'system_id';

    protected $fillable = [
        'system_name',
        'system_user_id',
        'system_log_datetime',
        'system_inactive'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'system_user_id');
    }
}