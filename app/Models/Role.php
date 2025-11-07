<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'idrole';  // Tentukan primary key yang benar
    protected $guarded = [];
    
    /**
     * Relasi Many-to-Many dengan User melalui tabel pivot user_role
     * 
     * @return HasMany
     */
    public function userRole(): HasMany 
    {
        return $this->hasMany(UserRole::class, 'idrole', 'idrole');
    }
}
