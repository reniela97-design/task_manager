<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    // ✅ FIX: Mao ni ang solusyon sa error.
    // Gisultian nato ang Laravel nga wala tay 'updated_at' column, so dili siya mamugos ug insert.
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record($action, $module = null)
    {
        return self::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'module'     => $module,
            'ip_address' => request()->ip(),
        ]);
    }
}