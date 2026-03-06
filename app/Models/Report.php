<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Task;
use App\Models\Project;
use App\Models\Client;
use App\Models\User;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_title',
        'report_type',
        'generated_by',
        'report_data',
        'remarks'
    ];

    /**
     * I-cast ang JSON gikan sa database para mahimong Array sa PHP.
     */
    protected $casts = [
        'report_data' => 'array',
    ];

    /**
     * Relationship sa User (Kinsay nag-generate sa report).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}