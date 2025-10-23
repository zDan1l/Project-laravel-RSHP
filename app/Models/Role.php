<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'idrole';  // Tentukan primary key yang benar
    protected $guarded = [];
    
    /**
     * Relasi Many-to-Many dengan User melalui tabel pivot role_user
     * 
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'idrole', 'iduser')
                    ->using(UserRole::class)  // Menggunakan custom pivot model
                    ->withPivot('status');
    }
}
