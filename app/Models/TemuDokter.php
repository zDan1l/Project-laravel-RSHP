<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemuDokter extends Model
{
    protected $table = 'temu_dokter';
    protected $primaryKey = 'idreservasi_dokter';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'idreservasi_dokter',
        'no_urut',
        'waktu_daftar',
        'status',
        'idpet',
        'idrole_user'
    ];

    protected $casts = [
        'waktu_daftar' => 'datetime',
    ];

    /**
     * Relasi ke Pet
     */
    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }

    /**
     * Relasi ke User Role (dokter yang ditugaskan)
     */
    public function roleUser()
    {
        return $this->belongsTo(UserRole::class, 'idrole_user', 'idrole_user');
    }

    /**
     * Relasi ke Rekam Medis
     */
    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class, 'idreservasi_dokter', 'idreservasi_dokter');
    }
}
