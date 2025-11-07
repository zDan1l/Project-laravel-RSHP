<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model untuk tabel pivot role_user
 * Extends Pivot untuk fitur-fitur khusus pivot table
 */
class UserRole extends Pivot
{
    protected $table = 'role_user';
    protected $guarded = [];
    public $timestamps = false;

    
    /**
     * Relasi ke User
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
    
    /**
     * Relasi ke Role
     * 
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }
}
