<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;

class Project extends Model
{
    use HasFactory;

    // Mao ni ang kulang, Boss. I-allow nato sila ma-save.
    protected $fillable = [
        'name',
        'client_id',
        'branch',
        'address',
        'status', 
        'completion_percent',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}