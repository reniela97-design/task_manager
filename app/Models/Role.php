<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
class Role extends Model {
    // Fillable fields matching your SQL structure
    protected $fillable = ['role_name', 'role_inactive'];

    /**
     * Get all users assigned to this role.
     */
    public function users(): HasMany {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}