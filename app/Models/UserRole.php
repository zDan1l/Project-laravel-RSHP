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
    
    // Definisikan composite primary key
    protected $primaryKey = ['iduser', 'idrole'];
    public $incrementing = false;

    
    /**
     * Override getKeyName untuk composite key
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    /**
     * Override setKeysForSaveQuery untuk composite key
     */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Override getKeyForSaveQuery untuk composite key
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    
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

    /**
     * Relasi ke TemuDokter (melalui idrole_user)
     * Digunakan untuk validasi delete
     */
    public function temuDokter()
    {
        return $this->hasMany(TemuDokter::class, 'idrole_user', 'idrole_user');
    }

    /**
     * Check if this UserRole is being used by other entities
     * 
     * @return bool
     */
    public function isUsedByOtherEntities(): bool
    {
        // Cek apakah ada TemuDokter yang menggunakan idrole_user ini
        $hasTemuDokter = $this->temuDokter()->exists();
        
        return $hasTemuDokter;
    }

    /**
     * Get usage details for error message
     * 
     * @return array
     */
    public function getUsageDetails(): array
    {
        $details = [];
        
        $temuDokterCount = $this->temuDokter()->count();
        if ($temuDokterCount > 0) {
            $details[] = "$temuDokterCount janji temu dokter";
        }
        
        return $details;
    }
}
