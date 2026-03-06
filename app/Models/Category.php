<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Category extends Model
{
    // Define the custom primary key from your schema
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
        'category_user_id',
        'category_log_datetime',
        'category_inactive'
    ];

    // Disable standard timestamps if your table doesn't have created_at/updated_at
    public $timestamps = false; 

    public function user()
    {
        return $this->belongsTo(User::class, 'category_user_id');
    }
}